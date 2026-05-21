<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'marquee_items';
    private const string ID = 'id';
    private const string NAME = 'name';
    private const string SORT_ORDER = 'sort_order';
    private const string IS_ACTIVE = 'is_active';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::NAME, 255);
            $table->integer(self::SORT_ORDER)->default(0);
            $table->boolean(self::IS_ACTIVE)->default(true);
            $table->timestamps();

            $table->index(self::IS_ACTIVE);
            $table->index(self::SORT_ORDER);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
