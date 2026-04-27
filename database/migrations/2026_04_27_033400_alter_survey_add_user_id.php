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
        DB::statement("
            IF NOT EXISTS (SELECT * FROM sys.schemas WHERE name = 'srv')
            BEGIN
                EXEC('CREATE SCHEMA srv');
            END");
        Schema::table('srv.survery', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
