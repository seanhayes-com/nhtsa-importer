<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpicMakeModelNameIdTable extends Migration
{
	//    'Make_ID', 'Make_Name', 'Model_ID', 'Model_Name'

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vpic_make_model_name_id', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('Make_ID')->unsigned()->index();
			$table->string('Make_Name')->index();
			$table->integer('Model_ID')->unsigned()->index();
			$table->string('Model_Name')->index();
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
		Schema::drop('vpic_make_model_name_id');
	}
}
