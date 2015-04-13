<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeStepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recipe_steps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('step_title');
			$table->mediumText('step');
			$table->integer('recipe_id')->unsigned();
			$table->foreign('recipe_id')->references('id')->on('recipe');
			$table->integer('step_number')->unsigned();
			$table->integer('machine_instruction')->unsigned();
			$table->boolean('user_step')->unsigned();
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
		Schema::drop('recipe_steps');
	}

}
?>
