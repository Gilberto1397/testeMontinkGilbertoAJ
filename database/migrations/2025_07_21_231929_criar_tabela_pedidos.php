<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('pedidos_id');
            $table->unsignedSmallInteger('pedidos_statuspedido');
            $table->decimal('pedidos_valor', 10, 2);
            $table->date('pedidos_data');

            $table->foreign('pedidos_statuspedido')->references('statuspedido_id')->on('statuspedido')
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
        Schema::dropIfExists('pedidos');
    }
}
