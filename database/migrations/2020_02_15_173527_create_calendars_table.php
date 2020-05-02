<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {            
            $table->bigIncrements('id');
            $table->string('title');
            $table->date('start');
            $table->date('end');            
            $table->string('room_number');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->decimal('totol_fee',8,2);
            $table->decimal('number_members',2,0);
            $table->string('status');
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
        Schema::dropIfExists('calendars');
    }
}
