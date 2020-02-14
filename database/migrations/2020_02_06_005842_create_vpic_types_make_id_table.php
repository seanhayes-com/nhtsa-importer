<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpicTypesMakeIdTable extends Migration
{
	//    'Make_ID', 'VehicleTypeId', 'VehicleTypeName'

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vpic_types_make_id', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('Make_ID')->unsigned()->index();
			$table->integer('VehicleTypeId')->unsigned()->index();
			$table->string('VehicleTypeName')->index();
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
		Schema::drop('vpic_types_make_id');
	}
}
