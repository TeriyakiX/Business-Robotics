<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleCreateRequest;
use App\Http\Requests\Article\ArticleListRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\Article\ArticleFullResource;
use App\Http\Resources\Article\ArticleListResource;
use App\Services\Article\ArticleService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ArticleController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly ArticleService $service,
    ) {}


    public function list(ArticleListRequest $request): JsonResponse
    {
        $result = $this->service->list($request->toDto());

        $isCursorPaginator = method_exists($result, 'hasMorePages');

        if ($isCursorPaginator) {
            return response()->json([
                'success' => true,
                'message' => __('responses.article.list'),
                'data' => ArticleListResource::collection($result->items()),
                'meta' => [
                    'next_cursor' => $result->nextCursor()?->encode(),
                    'prev_cursor' => $result->previousCursor()?->encode(),
                    'per_page' => $result->perPage(),
                ],
            ]);
        }

        return $this->executeAction(
            action: fn() => ArticleListResource::collection($result),
            successMessageKey: 'article.list'
        );
    }

    public function item(string $id): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ArticleFullResource($this->service->item($id)),
            successMessageKey: 'article.item'
        );
    }

    public function show(string $slug): JsonResponse
    {
        $article = $this->service->findBySlug($slug);

        if (request()->input('increment_views', true)) {
            $this->service->incrementViews($slug);
        }

        return $this->executeAction(
            action: fn() => new ArticleFullResource($article),
            successMessageKey: 'article.item'
        );
    }


    public function create(ArticleCreateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ArticleFullResource($this->service->create($request->toDto())),
            successMessageKey: 'article.create',
            successStatus: Response::HTTP_CREATED
        );
    }

    public function update(string $id, ArticleUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new ArticleFullResource($this->service->update($id, $request->toDto())),
            successMessageKey: 'article.update'
        );
    }

    public function delete(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->delete($id),
            successMessageKey: 'article.delete'
        );
    }

    public function restore(string $id): JsonResponse
    {
        return $this->executeVoidAction(
            action: fn() => $this->service->restore($id),
            successMessageKey: 'article.restore'
        );
    }
}
