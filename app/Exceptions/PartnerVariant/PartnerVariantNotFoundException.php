<?php

declare(strict_types=1);

namespace App\Exceptions\PartnerVariant;

use Exception;

final class PartnerVariantNotFoundException extends Exception
{
    protected $message = 'Partner variant not found';
    protected $code = 404;
}
