<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\Agent\AgentNotFoundException;
use App\Models\Agent;

final readonly class AgentValidator
{
    public function validateAgentExists(?Agent $agent): void
    {
        if (!$agent) {
            throw new AgentNotFoundException();
        }
    }

    public function validateCreateData(array $data): void
    {

    }

    public function validateUpdateData(array $data): void
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Нет данных для обновления');
        }
    }
}
