<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpicMakeTable extends Migration
{
	
	//'Make_ID', 'Make_Name'
		
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vpic_make', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('Make_ID')->unsigned()->index();
			$table->string('Make_Name')->index();
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
		Schema::drop('vpic_make');
	}
}
