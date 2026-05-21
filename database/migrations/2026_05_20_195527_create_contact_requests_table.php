<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string TABLE_NAME = 'contact_requests';
    private const string ID = 'id';
    private const string NAME = 'name';
    private const string PHONE = 'phone';
    private const string COMPANY = 'company';
    private const string STATUS = 'status';
    private const string PROCESSED_AT = 'processed_at';
    private const string NOTES = 'notes';
    private const string CREATED_AT = 'created_at';
    private const string UPDATED_AT = 'updated_at';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid(self::ID)->primary();
            $table->string(self::NAME, 255);
            $table->string(self::PHONE, 50);
            $table->string(self::COMPANY, 255)->nullable();
            $table->string(self::STATUS, 50)->default('new');
            $table->timestamp(self::PROCESSED_AT)->nullable();
            $table->text(self::NOTES)->nullable();
            $table->timestamps();

            $table->index(self::STATUS);
            $table->index(self::PHONE);
            $table->index(self::CREATED_AT);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
