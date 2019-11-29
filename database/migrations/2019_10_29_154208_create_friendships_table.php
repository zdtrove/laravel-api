<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFriendshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('friendships', function(Blueprint $table)
		{
			$table->integer('requester_id')->index('fk_friendships_profiles1_idx');
			$table->integer('addressee_id')->index('fk_friendships_profiles2_idx');
			$table->integer('status')->nullable()->comment('0: pedding; 1: Accepted; 2: Declined; 3: Blocked');
			$table->integer('specifier_id')->index('fk_friendships_profiles3_idx');
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
		Schema::drop('friendships');
	}

}
