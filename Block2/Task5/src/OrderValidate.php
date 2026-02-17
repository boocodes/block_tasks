<?php

namespace Task5;

class OrderValidate
{
    private bool $statusResponse;
    private string $valueResponse;

    public function __construct()
    {
    }
    public function getStatusResponse(): bool
    {
        return $this->statusResponse;
    }
    public function getValueResponse(): string
    {
        return $this->valueResponse;
    }
    public function validate(&$input): bool
    {
        if (empty($input['email'])) {
            $this->statusResponse = false;
            $this->valueResponse = 'email required';
            return false;
        }

        $email = trim((string)$input['email']);
        if (strpos($email, '@') === false) {
            $this->statusResponse = false;
            $this->valueResponse = 'email invalid';
            return false;
        }
        return true;
    }
}