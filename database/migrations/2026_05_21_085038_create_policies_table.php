<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'policies';

    private const string ID = 'id';
    private const string TITLE = 'title';
    private const string SLUG = 'slug';
    private const string CONTENT = 'content';
    private const string IS_ACTIVE = 'is_active';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';
    private const string DELETED_AT = 'deleted_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::TITLE, 255);
            $table->string(self::SLUG, 255)->unique();
            $table->longText(self::CONTENT);
            $table->boolean(self::IS_ACTIVE)->default(true);

            $table->timestamps();
            $table->softDeletes(self::DELETED_AT);

            $table->index(self::SLUG);
            $table->index(self::IS_ACTIVE);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
