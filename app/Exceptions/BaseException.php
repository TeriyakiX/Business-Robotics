<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseException extends Exception
{
    protected string $messageKey = 'common.error';
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function __construct(?string $message = null, ?int $code = null)
    {
        $message = $message ?? __("responses.{$this->messageKey}");
        $code = $code ?? $this->statusCode;

        parent::__construct($message, $code);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getMessageKey(): string
    {
        return $this->messageKey;
    }
}
