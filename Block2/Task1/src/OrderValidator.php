<?php

namespace Task1;

class OrderValidator implements OrderValidatorInterface
{
    private bool $responseStatus;
    private string $responseValue;
    private int $cardNumberLength;
    private bool $emailRequiredFlag;

    public function setCardNumberLength(int $cardNumberLength): void
    {
        $this->cardNumberLength = $cardNumberLength;
    }
    public function setEmailRequiredFlag(bool $emailRequiredFlag): void
    {
        $this->emailRequiredFlag = $emailRequiredFlag;
    }

    public function __construct()
    {
        $this->cardNumberLength = 12;
        $this->emailRequiredFlag = true;
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
        if (!isset($input['customer']['email']) && $this->emailRequiredFlag) {
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
        if (strlen($card) < $this->cardNumberLength && $input['payment']['method'] === 'card') {
            $this->responseStatus = false;
            $this->responseValue = 'card number required';
            return false;
        }
        return true;
    }
}