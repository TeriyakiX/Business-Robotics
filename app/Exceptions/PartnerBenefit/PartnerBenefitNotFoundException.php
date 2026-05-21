<?php

declare(strict_types=1);

namespace App\Exceptions\PartnerBenefit;

use Exception;

final class PartnerBenefitNotFoundException extends Exception
{
    protected $message = 'Partner benefit not found';
    protected $code = 404;
}
