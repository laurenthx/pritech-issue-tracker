<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Adds a foreign key 'user_id' that refers to the 'id' column on the 'users' table.
            // ->nullable() allows existing projects (created before this migration) to have no owner initially.
            // ->constrained() adds the foreign key constraint.
            // ->onDelete('cascade') means if a user is deleted, all their projects are also deleted.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id'); // Correctly drops the foreign key and column
        });
    }
};