<?php

declare(strict_types=1);

namespace App\Exceptions\Contact;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class ContactCreateFailedException extends BaseException
{
    protected string $messageKey = 'contact.create_failed';
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
}
