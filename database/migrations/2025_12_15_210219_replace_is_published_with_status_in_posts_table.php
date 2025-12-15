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
        Schema::table('posts', function (Blueprint $table) {
            // Add the new 'status' column
            $table->enum('status', ['scheduled', 'published', 'declined'])->default('scheduled')->after('content');
        });

        // Update existing records based on 'is_published' value
        // Note: This part might need adjustment if you have a lot of existing data
        // and want more nuanced mapping. For simplicity, we assume 'published' or 'scheduled'.
        DB::table('posts')->where('is_published', true)->update(['status' => 'published']);
        DB::table('posts')->where('is_published', false)->update(['status' => 'scheduled']);


        Schema::table('posts', function (Blueprint $table) {
            // Drop the old 'is_published' column
            $table->dropColumn('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Re-add the 'is_published' column
            $table->boolean('is_published')->default(false)->after('status');
        });

        // Revert status based on 'status' value
        DB::table('posts')->where('status', 'published')->update(['is_published' => true]);
        DB::table('posts')->whereIn('status', ['scheduled', 'declined'])->update(['is_published' => false]);

        Schema::table('posts', function (Blueprint $table) {
            // Drop the new 'status' column
            $table->dropColumn('status');
        });
    }
};