<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Foreign key to issues table. onDelete('cascade') ensures comments are deleted with their issue.
            $table->foreignId('issue_id')->constrained()->onDelete('cascade');
            $table->string('author_name'); // Author name field (for comments)
            $table->text('body');         // Comment body
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
