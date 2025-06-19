<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the column without constraint
        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('name');
        });

        // Update existing records with a default user if any exist
        if (Schema::hasTable('bills')) {
            $firstUser = DB::table('users')->first();
            if ($firstUser) {
                DB::table('bills')->whereNull('created_by')->update(['created_by' => $firstUser->id]);
            }
        }

        // Now add the foreign key constraint
        Schema::table('bills', function (Blueprint $table) {
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
