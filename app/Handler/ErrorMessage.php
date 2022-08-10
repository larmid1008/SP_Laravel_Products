<?php

namespace App\Handler;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class ErrorMessage extends \Exception implements Arrayable
{
    public function __construct(private array $errors)
    {
        parent::__construct("", 422);
        if (!isset($this->errors['_messages'])) {
            $this->errors['_messages'] = [];
        }

        if (!isset($this->errors['_keys'])) {
            $this->errors['_keys'] = [];
        }
    }

    public function toArray()
    {
        $result = ['message' => 'The given data was invalid.'];
        foreach ($this->errors['_messages'] as $message) {
            $result['errorMessagesForUser'][] = $message;
        }

        if ($this->errors['_keys'] > 0) {
            $errors = Arr::dot($this->errors['_keys']);
            $result['errors'] = $errors;
        }

        return $result;
    }
}
