<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('name', 150)->nullable();
			$table->string('email', 45)->nullable();
			$table->string('uuid', 45)->nullable();
			$table->string('password', 45)->nullable();
			$table->string('image', 150)->nullable();
			$table->integer('is_manager')->default(0);
			$table->integer('is_master')->default(0);
			$table->integer('status')->default(1);
			$table->timestamps();
			$table->string('created_by', 45)->nullable();
			$table->string('updated_by', 45)->nullable();
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
		Schema::drop('admins');
	}

}
