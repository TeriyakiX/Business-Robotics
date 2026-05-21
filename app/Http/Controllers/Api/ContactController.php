<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactCreateRequest;
use App\Http\Requests\Contact\ContactListRequest;
use App\Http\Requests\Contact\ContactUpdateStatusRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Services\Contact\ContactService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ContactController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly ContactService $service,
    ) {}

    public function create(ContactCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ContactResource($this->service->create($request->toDto())),
            successMessageKey: 'contact.create',
            successStatus: Response::HTTP_CREATED
        );
    }


    public function list(ContactListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => ContactResource::collection(
                $this->service->list($request->toDto())
            ),
            successMessageKey: 'contact.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ContactResource($this->service->item($id)),
            successMessageKey: 'contact.item'
        );
    }

    public function updateStatus(string $id, ContactUpdateStatusRequest $request): JsonResponse
    {
        $status = $request->input('status');
        $notes = $request->input('notes');

        $contact = match ($status) {
            'processed' => $this->service->markAsProcessed($id, $notes),
            'contacted' => $this->service->markAsContacted($id),
            'rejected' => $this->service->markAsRejected($id, $notes),
            default => $this->service->item($id),
        };

        return $this->executeAction(
            action: fn() => new ContactResource($contact),
            successMessageKey: 'contact.status_updated'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'contact.delete'
        );
    }
}
