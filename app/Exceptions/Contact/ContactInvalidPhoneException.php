<?php

declare(strict_types=1);

namespace App\Exceptions\Contact;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ContactInvalidPhoneException extends BaseException
{
    protected string $messageKey = 'contact.invalid_phone';
    protected int $statusCode = Response::HTTP_BAD_REQUEST;
}
