<?php

namespace App\Core\Invoice\Domain\Exception;

class MessageManager
{
    private static array $messages = [
        'invoice_amount_zero_or_less' => 'Kwota faktury musi być większa niż zero.',
        'invoice_inactive_user' => 'Użytkownik jest nieaktywny. Nie można tworzyć faktury.',
    ];

    public static function get(string $key): string
    {
        return self::$messages[$key] ?? '';
    }
}