<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecallTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recall', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ModelYear')->unsigned()->index();
			$table->string('Make')->index();
			$table->string('Model')->index();
			$table->string('Manufacturer');
			$table->string('NHTSACampaignNumber');
			$table->string('ReportReceivedDate');
			$table->string('Component');
			$table->text('Summary', 65535);
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
		Schema::drop('recall');
	}
}
