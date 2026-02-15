<?php

namespace Legacy\God;


class Notifier
{
    private string $adminEmail;
    private string $userEmail;
    private string $debugFlag;
    public function __construct(string $adminEmail, string $userEmail, string $debugFlag)
    {
        $this->adminEmail = $adminEmail;
        $this->userEmail = $userEmail;
        $this->debugFlag = $debugFlag;
    }
    public function setAdminEmail(string $adminEmail): void
    {
        $this->adminEmail = $adminEmail;
    }
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }
    public function setDebugFlag(string $debugFlag): void
    {
        $this->debugFlag = $debugFlag;
    }
    public function notifyAdmin(string $message): void
    {
        if ($this->debug) {
            error_log(
                $message,
            );
        }
    }
    public function notifyUser(string $message): void
    {
        if ($this->debug) {
            error_log(
                $message,
            );
        }
    }
}