<?php

namespace App\Contracts\Services\MailService;

interface MailServiceInterface
{
    public function send(): void;
}
