<?php

declare(strict_types=1);

namespace App\Exceptions\Case;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class CaseCreateForbiddenException extends BaseException
{
    protected string $messageKey = 'case.create_forbidden';
    protected int $statusCode = Response::HTTP_FORBIDDEN;
}
