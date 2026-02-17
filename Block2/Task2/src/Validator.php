<?php

namespace Task2;

class Validator
{
    private bool $currencyRequiredFlag;
    private bool $emailRequiredFlag;
    public function __construct()
    {
        $this->currencyRequiredFlag = true;
        $this->emailRequiredFlag = true;
    }
    public function setCurrencyRequiredFlag(bool $flag): void
    {
        $this->currencyRequiredFlag = $flag;
    }
    public function setEmailRequiredFlag(bool $flag): void
    {
        $this->emailRequiredFlag = $flag;
    }
    public function validate($input): bool
    {

        if (!isset($input->payment['currency']) && $this->currencyRequiredFlag) {
            return false;
        }

        if (!isset($input->customer['email']) && $this->emailRequiredFlag) {
            return false;
        };
        $email = trim((string)$input->customer['email']) ?? '';
        if ($email === '' || !str_contains($email, '@')) {
            return false;
        };

        if (!isset($input->items) || !is_array($input->items) || count($input->items) === 0) {
            return false;
        };
        $card = isset($input->payment['cardNumber']) ? preg_replace('/\s+/', '', (string)$input->payment['cardNumber']) : '';
        if (strlen($card) < 12 && $input->payment['method'] === 'card') {
            return false;
        }
        return true;
    }
}