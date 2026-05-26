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
            'hero_top_text' => $request->input('hero_top_text'),
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
        $socials = $request->input('socials', []);

        $validSocials = array_filter($socials, function ($social) {
            return !empty($social['name']) && !empty($social['url']);
        });

        $this->service->updateSocials(array_values($validSocials));

        return response()->json(['success' => true, 'message' => 'Соцсети обновлены']);
    }

    /**
     * Обновление соцсетей с загрузкой кастомных иконок
     */
    public function updateSocialsWithIcons(Request $request): JsonResponse
    {
        $socialsJson = $request->input('socials', '[]');
        $socials = is_string($socialsJson) ? json_decode($socialsJson, true) : $socialsJson;

        // Обрабатываем кастомные иконки
        $customIcons = $request->file('custom_icons', []);

        foreach ($socials as &$social) {
            $idx = $social['custom_icon_index'] ?? null;
            if ($idx !== null && isset($customIcons[$idx])) {
                $file = $customIcons[$idx];
                $path = $file->store('settings/social-icons', 'public');
                $social['custom_icon_url'] = asset('storage/' . $path);
                unset($social['custom_icon_index']);
            }
            // Убираем служебные поля
            unset($social['custom_icon_preview'], $social['_customFile']);
        }
        unset($social);

        $validSocials = array_filter($socials, fn($s) => !empty($s['name']) && !empty($s['url']));

        $this->service->updateSocials(array_values($validSocials));

        return response()->json(['success' => true, 'message' => 'Соцсети с иконками обновлены']);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $this->service->updateSettings($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Настройки обновлены',
            'data' => new SettingsResource($this->service->getAll())
        ]);
    }
}
