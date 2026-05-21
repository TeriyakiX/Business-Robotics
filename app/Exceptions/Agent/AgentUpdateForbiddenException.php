<?php

declare(strict_types=1);

namespace App\Exceptions\Agent;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class AgentUpdateForbiddenException extends BaseException
{
    protected string $messageKey = 'agent.update_forbidden';
    protected int $statusCode = Response::HTTP_FORBIDDEN;
}
