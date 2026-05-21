<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarqueeItem\MarqueeItemCreateRequest;
use App\Http\Requests\MarqueeItem\MarqueeItemListRequest;
use App\Http\Requests\MarqueeItem\MarqueeItemUpdateRequest;
use App\Http\Resources\MarqueeItem\MarqueeItemFullResource;
use App\Http\Resources\MarqueeItem\MarqueeItemListResource;
use App\Services\MarqueeItem\MarqueeItemService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MarqueeItemController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly MarqueeItemService $service,
    ) {}


    public function index(MarqueeItemListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => MarqueeItemListResource::collection(
                $this->service->list($request->input('only_active', true))
            ),
            successMessageKey: 'marquee.list'
        );
    }


    public function list(MarqueeItemListRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => MarqueeItemListResource::collection(
                $this->service->list(
                    onlyActive: $request->input('only_active', false),
                    search: $request->input('search'),
                    isActive: $request->input('is_active')
                )
            ),
            successMessageKey: 'marquee.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new MarqueeItemFullResource($this->service->item($id)),
            successMessageKey: 'marquee.item'
        );
    }

    public function create(MarqueeItemCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new MarqueeItemFullResource($this->service->create($request->toDto())),
            successMessageKey: 'marquee.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, MarqueeItemUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new MarqueeItemFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'marquee.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'marquee.delete'
        );
    }
}
