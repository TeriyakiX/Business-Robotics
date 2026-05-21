<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CaseController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MarqueeItemController;
use App\Http\Controllers\Api\PartnerBenefitController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\PartnerStepController;
use App\Http\Controllers\Api\PartnerVariantController;
use App\Http\Controllers\Api\ProcessStepController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/me', [AdminAuthController::class, 'me'])->middleware('auth:sanctum');
    });

    Route::get('agents', [AgentController::class, 'list']);
    Route::get('agents/{id}', [AgentController::class, 'item']);

    Route::get('cases', [CaseController::class, 'list']);
    Route::get('cases/{id}', [CaseController::class, 'item']);

    Route::get('articles', [ArticleController::class, 'list']);
    Route::get('articles/{id}', [ArticleController::class, 'item']);
    Route::get('articles/slug/{slug}', [ArticleController::class, 'show']);

    Route::get('partner/variants', [PartnerVariantController::class, 'index']);
    Route::get('partner/steps', [PartnerStepController::class, 'index']);
    Route::get('partner/benefits', [PartnerBenefitController::class, 'index']);

    Route::get('process-steps', [ProcessStepController::class, 'index']);
    Route::get('marquee-items', [MarqueeItemController::class, 'index']);

    Route::post('contact', [ContactController::class, 'create']);

    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

        // Agents CRUD
        Route::get('/agents', [AgentController::class, 'list']);
        Route::get('/agents/{id}', [AgentController::class, 'item']);
        Route::post('/agents', [AgentController::class, 'create']);
        Route::put('/agents/{id}', [AgentController::class, 'update']);
        Route::delete('/agents/{id}', [AgentController::class, 'delete']);
        Route::post('/agents/{id}/restore', [AgentController::class, 'restore']);

        // Cases CRUD
        Route::get('/cases', [CaseController::class, 'list']);
        Route::get('/cases/{id}', [CaseController::class, 'item']);
        Route::post('/cases', [CaseController::class, 'create']);
        Route::put('/cases/{id}', [CaseController::class, 'update']);
        Route::delete('/cases/{id}', [CaseController::class, 'delete']);
        Route::post('/cases/{id}/restore', [CaseController::class, 'restore']);

        // Articles CRUD
        Route::get('/articles', [ArticleController::class, 'list']);
        Route::get('/articles/{id}', [ArticleController::class, 'item']);
        Route::post('/articles', [ArticleController::class, 'create']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'delete']);
        Route::post('/articles/{id}/restore', [ArticleController::class, 'restore']);

        // Contacts CRUD
        Route::get('/contacts', [ContactController::class, 'list']);
        Route::get('/contacts/{id}', [ContactController::class, 'item']);
        Route::put('/contacts/{id}/status', [ContactController::class, 'updateStatus']);
        Route::delete('/contacts/{id}', [ContactController::class, 'delete']);

        // Marquee Items CRUD
        Route::get('/marquee-items', [MarqueeItemController::class, 'list']);
        Route::get('/marquee-items/{id}', [MarqueeItemController::class, 'item']);
        Route::post('/marquee-items', [MarqueeItemController::class, 'create']);
        Route::put('/marquee-items/{id}', [MarqueeItemController::class, 'update']);
        Route::delete('/marquee-items/{id}', [MarqueeItemController::class, 'delete']);

        // Partner Variants CRUD
        Route::get('/partner-variants', [PartnerVariantController::class, 'list']);
        Route::get('/partner-variants/{id}', [PartnerVariantController::class, 'item']);
        Route::post('/partner-variants', [PartnerVariantController::class, 'create']);
        Route::put('/partner-variants/{id}', [PartnerVariantController::class, 'update']);
        Route::delete('/partner-variants/{id}', [PartnerVariantController::class, 'delete']);

        // Partner Steps CRUD
        Route::get('/partner-steps', [PartnerStepController::class, 'list']);
        Route::get('/partner-steps/{id}', [PartnerStepController::class, 'item']);
        Route::post('/partner-steps', [PartnerStepController::class, 'create']);
        Route::put('/partner-steps/{id}', [PartnerStepController::class, 'update']);
        Route::delete('/partner-steps/{id}', [PartnerStepController::class, 'delete']);

        // Partner Benefits CRUD
        Route::get('/partner-benefits', [PartnerBenefitController::class, 'list']);
        Route::get('/partner-benefits/{id}', [PartnerBenefitController::class, 'item']);
        Route::post('/partner-benefits', [PartnerBenefitController::class, 'create']);
        Route::put('/partner-benefits/{id}', [PartnerBenefitController::class, 'update']);
        Route::delete('/partner-benefits/{id}', [PartnerBenefitController::class, 'delete']);

        // Process Steps CRUD
        Route::get('/process-steps', [ProcessStepController::class, 'list']);
        Route::get('/process-steps/{id}', [ProcessStepController::class, 'item']);
        Route::post('/process-steps', [ProcessStepController::class, 'create']);
        Route::put('/process-steps/{id}', [ProcessStepController::class, 'update']);
        Route::delete('/process-steps/{id}', [ProcessStepController::class, 'delete']);
    });
});
