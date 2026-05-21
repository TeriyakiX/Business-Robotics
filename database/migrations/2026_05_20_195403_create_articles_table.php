<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'articles';
    private const string ID = 'id';
    private const string SLUG = 'slug';
    private const string TITLE = 'title';
    private const string CATEGORY = 'category';
    private const string CATEGORY_COLOR = 'category_color';
    private const string CATEGORY_BG_COLOR = 'category_bg_color';
    private const string DESCRIPTION = 'description';
    private const string CONTENT = 'content';
    private const string READING_TIME = 'reading_time';
    private const string PUBLISHED_AT = 'published_at';
    private const string IS_PUBLISHED = 'is_published';
    private const string VIEWS_COUNT = 'views_count';
    private const string COVER_PATH = 'cover_path';
    private const string GALLERY = 'gallery';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';
    private const string DELETED_AT = 'deleted_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::SLUG, 255)->unique();
            $table->string(self::TITLE, 500);
            $table->string(self::CATEGORY, 100);
            $table->string(self::CATEGORY_COLOR, 20)->nullable();
            $table->string(self::CATEGORY_BG_COLOR, 50)->nullable();
            $table->text(self::DESCRIPTION);
            $table->longText(self::CONTENT);
            $table->integer(self::READING_TIME)->default(5);
            $table->timestamp(self::PUBLISHED_AT)->nullable();
            $table->boolean(self::IS_PUBLISHED)->default(false);
            $table->integer(self::VIEWS_COUNT)->default(0);
            $table->string(self::COVER_PATH)->nullable();
            $table->json(self::GALLERY)->nullable();
            $table->timestamps();
            $table->softDeletes(self::DELETED_AT);

            $table->index(self::SLUG);
            $table->index(self::CATEGORY);
            $table->index(self::IS_PUBLISHED);
            $table->index(self::PUBLISHED_AT);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
