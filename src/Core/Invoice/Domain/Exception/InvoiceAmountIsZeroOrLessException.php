<?php

namespace App\Core\Invoice\Domain\Exception;

class InvoiceAmountIsZeroOrLessException extends InvoiceException
{
    public function __construct()
    {
        $message = MessageManager::get('invoice_amount_zero_or_less');
        parent::__construct($message);
    }
}
