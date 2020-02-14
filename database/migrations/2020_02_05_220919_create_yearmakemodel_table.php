<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearmakemodelTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yearmakemodel', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ModelYear')->unsigned()->index();
			$table->string('Make')->index();
			$table->string('Model')->index();
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
		Schema::drop('yearmakemodel');
	}
}
