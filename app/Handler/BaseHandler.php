<?php

namespace App\Handler;

use Spatie\DataTransferObject\DataTransferObject;

abstract class BaseHandler
{
    protected bool $isTransactional = true;
    protected int $numberOfAttempts = 1;
    private array $errors = ["_keys" => [], "_messages" => []];

    abstract protected function handleCommand($command);

    final public function handle(DataTransferObject $dto) {
        if (!$this->isTransactional) {
            return $this->processResult($this->handleCommand($dto));
        }

        $self = $this;
        return \DB::transaction(function() use($self, $dto) {
            return $this->processResult($self->handleCommand($dto));
        }, $this->numberOfAttempts);
    }

    protected function addError($val, ?bool $throwImmediately = true) {
        if (!(is_string($val) || is_array($val))) {
            throw new \Exception(sprintf('String or Array expected %s given', gettype($val)));
        }
        if (is_string($val)) {
            $this->errors['_messages'][] = $val;
        } else {
            $this->errors['_keys'] = array_merge($this->errors['_keys'], $val);
        }
        if ($throwImmediately) {
            throw new ErrorMessage($this->errors);
        }
    }

    private function processResult(mixed $data): mixed
    {
        if (count($this->errors['_keys']) || count($this->errors['_messages']) > 0) {
            throw new ErrorMessage($this->errors);
        }
        return $data;
    }

}
