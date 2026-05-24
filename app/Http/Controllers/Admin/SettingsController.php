<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsUpdateRequest;
use App\Http\Resources\SettingsResource;
use App\Services\Settings\SettingsService;
use App\Traits\HandlesApiResponsesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SettingsController extends Controller
{
    use HandlesApiResponsesTrait;

    public function __construct(
        private readonly SettingsService $service,
    ) {}

    public function index(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new SettingsResource($this->service->getAll()),
            successMessageKey: 'settings.list'
        );
    }

    public function publicIndex(): JsonResponse
    {
        return $this->executeAction(
            action: fn() => new SettingsResource($this->service->getAll()),
            successMessageKey: 'settings.list'
        );
    }

    public function update(SettingsUpdateRequest $request): JsonResponse
    {
        return $this->executeAction(
            action: fn() => $this->service->update($request->toDto()),
            successMessageKey: 'settings.update',
            successStatus: Response::HTTP_OK
        );
    }

    public function updateHeroWithFiles(Request $request): JsonResponse
    {
        $textData = [
            'hero_title_line_1' => $request->input('hero_title_line_1'),
            'hero_title_line_2' => $request->input('hero_title_line_2'),
            'hero_title_line_3' => $request->input('hero_title_line_3'),
            'hero_eyebrow' => $request->input('hero_eyebrow'),
            'hero_button_text' => $request->input('hero_button_text'),
            'hero_use_spline' => $request->input('hero_use_spline') === 'true' ? 'true' : 'false',
        ];

        $this->service->updateHeroWithFiles(
            $textData,
            $request->file('hero_background'),
            $request->file('hero_media')
        );

        return response()->json(['success' => true, 'message' => 'Настройки героя обновлены']);
    }

    public function updateSocials(Request $request): JsonResponse
    {
        // Убираем жесткую валидацию
        $socials = $request->input('socials', []);

        // Фильтруем только валидные записи
        $validSocials = array_filter($socials, function($social) {
            return !empty($social['name']) &&
                !empty($social['url']) &&
                filter_var($social['url'], FILTER_VALIDATE_URL);
        });

        $this->service->updateSocials($validSocials);

        return response()->json(['success' => true, 'message' => 'Соцсети обновлены']);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        // ВРЕМЕННО: посмотрим что приходит
        \Log::info('UPDATE SETTINGS DATA:', $request->all());

        $this->service->updateSettings($request->all());

        // Проверяем что сохранилось
        $saved = $this->service->getAll();
        \Log::info('SAVED DATA:', $saved);

        return response()->json([
            'success' => true,
            'message' => 'Настройки обновлены',
            'data' => new SettingsResource($saved)
        ]);
    }
}
