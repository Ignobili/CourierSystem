<?php
/* → MAY BE ADDED IN FUTURE.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    //*
    //* Run the migrations.
    //*
    //* @return void
    //*
    public function up()
    {
        Schema::create('roles', function (Blueprint $table)
        {
            $table->id();
            $table->string('role', 100)->unique(); // :role
            $table->timestamps();
        });
    }

    //*
    //* Reverse the migrations.
    //*
    //* @return void
    //*
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
*/
