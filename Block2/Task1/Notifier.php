<?php


namespace Task;

class Notifier
{
    private string $customerEmail;
    private string $adminEmail;
    private bool $debugFlag;

    public function __construct(string $customerEmail, string $adminEmail, bool $debugFlag)
    {
        $this->customerEmail = $customerEmail;
        $this->adminEmail = $adminEmail;
        $this->debugFlag = $debugFlag;

    }
    public function setDebugFlag(bool $flag): void
    {
        $this->debugFlag = $flag;
    }
    public function setCustomerEmail(string $email): void
    {
        $this->customerEmail = $email;
    }
    public function setAdminEmail(string $email): void
    {
        $this->adminEmail = $email;
    }
    public function notifyBoth(string $message): void
    {
        $this->notifyAdmin($message);
        $this->notifyCustomer($message);
    }

    public function notifyCustomer(string $message): void
    {
        if ($this->debugFlag) {
            error_log('[MAIL to ' . $this->customerEmail . '] ' . $message . PHP_EOL);
        }
    }
    public function notifyAdmin(string $message): void
    {
        if ($this->debugFlag) {
            error_log('[MAIL to ' . $this->adminEmail . '] ' . $message . PHP_EOL);
        }
    }
}