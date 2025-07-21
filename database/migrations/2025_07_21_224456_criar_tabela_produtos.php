<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id('produtos_id');
            $table->string('produtos_descricao');
            $table->decimal('produtos_preco', 10, 2);
            $table->unsignedBigInteger('produtos_variacaode')->nullable();

            $table->foreign('produtos_variacaode')->references('produtos_id')->on('produtos')
                ->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
