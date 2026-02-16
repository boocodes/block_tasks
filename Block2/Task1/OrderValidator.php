<?php

namespace Legacy;

class OrderValidator
{
    private bool $responseStatus;
    private string $responseValue;

    public function __construct()
    {
    }
    public function getResponseStatus(): bool
    {
        return $this->responseStatus;
    }
    public function getResponseValue(): string
    {
        return $this->responseValue;
    }

    public function validate(array $input): bool
    {
        if (!isset($input['customer']['email'])) {
            $this->responseStatus = false;
            $this->responseValue = 'customer email required';
            return false;
        };
        $email = trim((string)$input['customer']['email']);
        if ($email === '' || !str_contains($email, '@')) {
            $this->responseStatus = false;
            $this->responseValue = 'customer email required';
            return false;
        };
        if (!isset($input['items']) || !is_array($input['items']) || count($input['items']) === 0) {
            $this->responseStatus = false;
            $this->responseValue = 'items required';
            return false;
        };
        $card = isset($input['payment']['cardNumber']) ? preg_replace('/\s+/', '', (string)$input['payment']['cardNumber']) : '';
        if (strlen($card) < 12 && $input['payment']['method'] === 'card') {
            $this->responseStatus = false;
            $this->responseValue = 'card number required';
            return false;
        }
        return true;
    }
}