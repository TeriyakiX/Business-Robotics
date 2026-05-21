<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\Contact\ContactInvalidPhoneException;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Models\ContactRequest;

final readonly class ContactValidator
{
    public function validateContactExists(?ContactRequest $contact): void
    {
        if (!$contact) {
            throw new ContactNotFoundException();
        }
    }

    public function validatePhone(string $phone): void
    {

        $pattern = '/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/';

        if (!preg_match($pattern, $phone)) {
            throw new ContactInvalidPhoneException();
        }
    }

    public function validateCreateData(string $name, string $phone): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException(__('responses.contact.name_required'));
        }

        if (empty(trim($phone))) {
            throw new \InvalidArgumentException(__('responses.contact.phone_required'));
        }

        $this->validatePhone($phone);
    }
}
