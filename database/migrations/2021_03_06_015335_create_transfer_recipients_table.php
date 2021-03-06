<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_code');
            $table->string('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('account_number');
            $table->string('account_name');
            $table->string('bank_code');
            $table->string('bank_name');
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
        Schema::dropIfExists('transfer_recipients');
    }
}
