<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reference');
            $table->bigInteger('amount');
            $table->string('source');
            $table->string('transfer_code');
            $table->string('reason');
            $table->string('paystack_id');
            $table->string('currency');
            $table->string('integration');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
