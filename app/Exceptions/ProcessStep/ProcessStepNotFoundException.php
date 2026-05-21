<?php

declare(strict_types=1);

namespace App\Exceptions\ProcessStep;

use Exception;

final class ProcessStepNotFoundException extends Exception
{
    protected $message = 'Process step not found';
    protected $code = 404;
}
