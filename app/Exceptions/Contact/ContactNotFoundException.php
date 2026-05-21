<?php

declare(strict_types=1);

namespace App\Exceptions\Contact;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ContactNotFoundException extends BaseException
{
    protected string $messageKey = 'contact.not_found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
