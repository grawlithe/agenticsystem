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
        if (env("DB_CONNECTION") === "sqlsrv") {
            DB::statement("
                IF NOT EXISTS (SELECT * FROM sys.schemas WHERE name = 'srv')
                BEGIN
                    EXEC('CREATE SCHEMA srv');
                END");
        }
        Schema::create('srv.survery', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv.survery');
    }
};
