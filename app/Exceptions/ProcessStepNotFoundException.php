<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class ProcessStepNotFoundException extends BaseException
{
    protected string $messageKey = 'process_step.not_found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
