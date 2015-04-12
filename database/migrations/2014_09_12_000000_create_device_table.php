<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('device', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('device_step')->unsigned()->nullable();
			$table->foreign('device_step')->references('id')->on('recipe_steps');			
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
		Schema::drop('device');
	}

}

?>
