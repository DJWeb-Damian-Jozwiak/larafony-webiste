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
        Schema::create('external_link_clicks', function ($table) {
            $table->id();
            $table->string('session_id', 100)->nullable(false);
            $table->string('link_type', 50)->nullable(false); // 'github_framework', 'github_demo', 'masterphp'
            $table->string('link_url')->nullable(false);
            $table->string('source_page')->nullable(false); // Which page the click came from
            $table->string('user_agent')->nullable(true);
            $table->string('ip_address', 45)->nullable(true);
            $table->string('backend_fingerprint', 64)->nullable(true);
            $table->timestamp('clicked_at')->nullable(false);

            // Indexes
            $table->index('session_id');
            $table->index('link_type');
            $table->index('clicked_at');
            $table->index(['link_type', 'clicked_at']);
        }) |> Schema::execute(...);
    }

    /**
     * rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_link_clicks') |> Schema::execute(...);
    }
};