<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\Agent\AgentListDto;
use App\DTOs\Article\ArticleListDto;
use App\DTOs\Case\CaseListDto;
use App\Services\Agent\AgentService;
use App\Services\Article\ArticleService;
use App\Services\Case\CaseService;
use App\Services\MarqueeItemService;
use App\Services\Partner\PartnerService;
use App\Services\ProcessStepService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __construct(
        private readonly AgentService $agentService,
        private readonly CaseService $caseService,
        private readonly ArticleService $articleService,
        private readonly ProcessStepService $processStepService,
        private readonly MarqueeItemService $marqueeItemService,
        private readonly PartnerService $partnerService,
    ) {}

    public function index(): Response
    {
        // Временно закомментируем сервисы для проверки
        return Inertia::render('HomePage', [
            'agents' => [],
            'cases' => [],
            'articles' => [],
            'processSteps' => [],
            'marqueeItems' => [],
            'partnerVariants' => [],
            'partnerSteps' => [],
            'partnerBenefits' => [],
        ]);
    }
}
