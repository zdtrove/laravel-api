<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePortfoliosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portfolios', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('image', 100)->nullable();
			$table->string('youtube_link', 100)->nullable();
			$table->string('title', 45);
			$table->string('link', 100);
			$table->string('description')->nullable();
			$table->date('made_in')->nullable();
			$table->integer('profile_id')->nullable()->index('fk_portfolios_profiles1_idx');
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
		Schema::drop('portfolios');
	}

}
