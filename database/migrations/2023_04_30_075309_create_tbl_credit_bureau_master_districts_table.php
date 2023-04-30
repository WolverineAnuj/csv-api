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
        Schema::create('tbl_credit_bureau_master_districts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->comment = 'Credit Bureau Master - District - Monthly Monitor Industry';
            $table->bigInteger('id');
            $table->string('Month', 50)->nullable();
            $table->string('Quarter', 100)->nullable();
            $table->string('State', 100)->nullable();
            $table->string('District', 100)->nullable();
            $table->longText('jsonData')->comment('All Credit Bureau District Master');
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
        Schema::dropIfExists('tbl_credit_bureau_master_districts');
    }
};
