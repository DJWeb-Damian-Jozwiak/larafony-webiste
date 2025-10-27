<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    /**
     * run migration.
     */
    public function up(): void
    {
        Schema::create('user_activity', function ($table) {
            $table->id();
            $table->string('session_id', 100)->nullable(false);
            $table->string('page_url')->nullable(false);
            $table->string('page_title')->nullable(true);
            $table->string('referrer')->nullable(true);
            $table->string('user_agent')->nullable(true);
            $table->string('ip_address', 45)->nullable(true);
            $table->string('backend_fingerprint', 64)->nullable(true); // SHA-256 hash
            $table->string('country_code', 2)->nullable(true);
            $table->integer('screen_width')->nullable(true);
            $table->integer('screen_height')->nullable(true);
            $table->timestamps();

            // Indexes
            $table->index('session_id');
            $table->index('created_at');
            $table->index(['page_url', 'created_at']);
        }) |> Schema::execute(...);
    }

    /**
     * rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity') |> Schema::execute(...);
    }
};