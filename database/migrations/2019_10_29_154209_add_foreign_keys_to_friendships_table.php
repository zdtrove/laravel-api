<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFriendshipsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('friendships', function(Blueprint $table)
		{
			$table->foreign('requester_id', 'fk_friendships_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('addressee_id', 'fk_friendships_profiles2')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('specifier_id', 'fk_friendships_profiles3')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('friendships', function(Blueprint $table)
		{
			$table->dropForeign('fk_friendships_profiles1');
			$table->dropForeign('fk_friendships_profiles2');
			$table->dropForeign('fk_friendships_profiles3');
		});
	}

}
