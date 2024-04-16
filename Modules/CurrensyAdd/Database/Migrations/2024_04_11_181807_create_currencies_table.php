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
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->timestamps();
        });

        // Insert test data
        DB::table('currencies')->insert([
            ['code' => 'USD', 'description' => 'US Dollar'],
            ['code' => 'UAH', 'description' => 'Ukraine Hryvnia'],
            ['code' => 'EUR', 'description' => 'Euro'],
            ['code' => 'FRF', 'description' => 'French Franc'],
            ['code' => 'HKD', 'description' => 'Hong Kong Dollar'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
