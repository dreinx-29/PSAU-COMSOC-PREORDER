<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->string('cancellation_request_status')
                ->nullable()
                ->after('status');

            $table->text('cancellation_reason')
                ->nullable()
                ->after('cancellation_request_status');

            $table->timestamp('cancellation_requested_at')
                ->nullable()
                ->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_request_status',
                'cancellation_reason',
                'cancellation_requested_at',
            ]);
        });
    }
};
