<?php

namespace App\Core\Invoice\Domain\Exception;

class InvoiceInactiveUserException extends InvoiceException
{
    public function __construct()
    {
        $message = MessageManager::get('invoice_inactive_user');
        parent::__construct($message);
    }
}
