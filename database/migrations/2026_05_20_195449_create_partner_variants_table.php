<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'partner_variants';
    private const string ID = 'id';
    private const string TYPE = 'type';
    private const string TITLE = 'title';
    private const string DESCRIPTION = 'description';
    private const string PERCENTAGE = 'percentage';
    private const string MIN_AMOUNT = 'min_amount';
    private const string AMOUNT_LABEL = 'amount_label';
    private const string BADGE_COLOR = 'badge_color';
    private const string BADGE_BG = 'badge_bg';
    private const string TAGS = 'tags'; // JSON array
    private const string SORT_ORDER = 'sort_order';
    private const string IS_ACTIVE = 'is_active';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::TYPE, 50);
            $table->string(self::TITLE, 255);
            $table->text(self::DESCRIPTION);
            $table->integer(self::PERCENTAGE);
            $table->integer(self::MIN_AMOUNT);
            $table->string(self::AMOUNT_LABEL, 255);
            $table->string(self::BADGE_COLOR, 20)->nullable();
            $table->string(self::BADGE_BG, 50)->nullable();
            $table->json(self::TAGS)->nullable();
            $table->integer(self::SORT_ORDER)->default(0);
            $table->boolean(self::IS_ACTIVE)->default(true);
            $table->timestamps();

            $table->index(self::TYPE);
            $table->index(self::IS_ACTIVE);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
