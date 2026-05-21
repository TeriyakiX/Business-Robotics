<?php

declare(strict_types=1);

namespace App\Exceptions\Agent;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class AgentNotFoundException extends BaseException
{
    protected string $messageKey = 'agent.not_found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
