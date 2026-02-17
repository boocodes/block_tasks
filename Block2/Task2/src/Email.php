<?php


namespace Task2;



use http\Exception\BadMethodCallException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (trim($email) === '' || !str_contains($email, '@')) {
            throw new BadMethodCallException('Email not valid');
        }
        $this->email = $email;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
}