<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\DTOs\Contact\ContactCreateDto;
use App\DTOs\Contact\ContactListDto;
use App\DTOs\Contact\ContactUpdateDto;
use App\Exceptions\Contact\ContactCreateFailedException;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Models\ContactRequest;
use App\Repositories\ContactRepository;
use App\Validators\ContactValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final readonly class ContactService
{
    public function __construct(
        private ContactRepository $repository,
        private ContactValidator $validator,
    ) {}

    public function list(ContactListDto $dto): Collection
    {
        return $this->repository->list($dto);
    }

    /**
     * @throws ContactNotFoundException
     */
    public function item(string $id): ContactRequest
    {
        $contact = $this->repository->item($id);
        $this->validator->validateContactExists($contact);

        return $contact;
    }

    /**
     * @throws ContactCreateFailedException
     */
    public function create(ContactCreateDto $dto): ContactRequest
    {
        $this->validator->validateCreateData($dto->name, $dto->phone);

        try {
            $contact = DB::transaction(function () use ($dto) {
                return $this->repository->create($dto);
            });

            $this->sendToCrmWebhook($dto);

            return $contact;
        } catch (\Exception $e) {
            throw new ContactCreateFailedException();
        }
    }

    /**
     * @throws ContactNotFoundException
     */
    public function update(string $id, ContactUpdateDto $dto): ContactRequest
    {
        $contact = $this->repository->item($id);
        $this->validator->validateContactExists($contact);

        $updated = $this->repository->update($contact, $dto);

        if (!$updated) {
            throw new ContactNotFoundException();
        }

        return $updated;
    }

    /**
     * @throws ContactNotFoundException
     */
    public function delete(string $id): bool
    {
        $contact = $this->repository->item($id);
        $this->validator->validateContactExists($contact);

        return $this->repository->delete($contact);
    }

    public function markAsProcessed(string $id, ?string $notes = null): ContactRequest
    {
        $contact = $this->item($id);
        $contact->markAsProcessed($notes);

        return $contact->fresh();
    }

    public function markAsContacted(string $id): ContactRequest
    {
        $contact = $this->item($id);
        $contact->markAsContacted();

        return $contact->fresh();
    }

    public function markAsRejected(string $id, ?string $notes = null): ContactRequest
    {
        $contact = $this->item($id);
        $contact->markAsRejected($notes);

        return $contact->fresh();
    }

    private function sendToCrmWebhook(ContactCreateDto $dto): void
    {
        try {
            Http::asForm()->post(
                'https://wdg.biz-crm.ru/inserv/in.php?token=05f9168f9be854e419ba90024771573f',
                [
                    'name' => $dto->name,
                    'phone' => $dto->phone,
                    'company' => $dto->company,
                ]
            );
        } catch (\Exception $e) {
            Log::error('CRM webhook failed', ['error' => $e->getMessage()]);
        }
    }
}
