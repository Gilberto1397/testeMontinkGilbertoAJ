<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaCupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupons', function (Blueprint $table) {
            $table->id('cupons_id');
            $table->string('cupons_codigo');
            $table->decimal('cupons_valorminimo', 10, 2);
            $table->decimal('cupons_valordesconto', 10, 2);
            $table->date('cupons_expiracao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupons');
    }
}
