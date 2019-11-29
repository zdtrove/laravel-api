<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAdminRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('admin_roles', function(Blueprint $table)
		{
			$table->foreign('admin_id', 'fk_admin_roles_admins1')->references('id')->on('admins')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('admin_roles', function(Blueprint $table)
		{
			$table->dropForeign('fk_admin_roles_admins1');
		});
	}

}
