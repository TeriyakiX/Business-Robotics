<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'cases';
    private const string ID = 'id';
    private const string TITLE = 'title';
    private const string CLIENT_NAME = 'client_name';
    private const string CLIENT_ROLE = 'client_role';
    private const string CLIENT_AVATAR_INITIALS = 'client_avatar_initials';
    private const string INDUSTRY = 'industry';
    private const string METRICS = 'metrics';
    private const string DESCRIPTION = 'description';
    private const string TESTIMONIAL = 'testimonial';
    private const string SORT_ORDER = 'sort_order';
    private const string IS_VISIBLE = 'is_visible';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';
    private const string DELETED_AT = 'deleted_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::TITLE, 255);
            $table->string(self::CLIENT_NAME, 255);
            $table->string(self::CLIENT_ROLE, 255);
            $table->string(self::CLIENT_AVATAR_INITIALS, 10)->nullable();
            $table->string(self::INDUSTRY, 100);
            $table->json(self::METRICS);
            $table->text(self::DESCRIPTION);
            $table->text(self::TESTIMONIAL)->nullable();
            $table->integer(self::SORT_ORDER)->default(0);
            $table->boolean(self::IS_VISIBLE)->default(true);
            $table->timestamps();
            $table->softDeletes(self::DELETED_AT);

            $table->index(self::INDUSTRY);
            $table->index(self::IS_VISIBLE);
            $table->index(self::SORT_ORDER);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
