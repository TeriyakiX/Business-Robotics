<?php

declare(strict_types=1);

namespace App\Exceptions\Case;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class CaseNotFoundException extends BaseException
{
    protected string $messageKey = 'case.not_found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
