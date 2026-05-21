<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\Case\CaseNotFoundException;
use App\Models\BusinessCase;

final readonly class CaseValidator
{
    public function validateCaseExists(?BusinessCase $case): void
    {
        if (!$case) {
            throw new CaseNotFoundException();
        }
    }

    public function validateMetrics(array $metrics): void
    {
        foreach ($metrics as $metric) {
            if (!isset($metric['value']) || !isset($metric['label'])) {
                throw new \InvalidArgumentException('Каждая метрика должна содержать value и label');
            }
        }
    }
}
