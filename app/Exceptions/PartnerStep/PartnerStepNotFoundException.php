<?php

declare(strict_types=1);

namespace App\Exceptions\PartnerStep;

use Exception;

final class PartnerStepNotFoundException extends Exception
{
    protected $message = 'Partner step not found';
    protected $code = 404;
}
