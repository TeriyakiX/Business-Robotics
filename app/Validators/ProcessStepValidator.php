<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\ProcessStepNotFoundException;
use App\Models\ProcessStep;

final readonly class ProcessStepValidator
{
    public function validateProcessStepExists(?ProcessStep $step): void
    {
        if (!$step) {
            throw new ProcessStepNotFoundException();
        }
    }
}
