<?php


namespace App\Console\Commands;

use App\Repositories\AdminRepository;
use App\Repositories\AdminRoleRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Schema;

class MigrateAdminRolesCommand extends Command
{
    protected $signature = 'migrate:admin-roles {--drop} {--rollback}';

    protected $description = '';

    protected $adminRepository;
    protected $adminRoleRepository;

    public function __construct(AdminRepository $adminRepository, AdminRoleRepository $adminRoleRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->adminRoleRepository = $adminRoleRepository;

        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('rollback')) {
            $this->rollBack();
            return;
        }
        $this->syncAdminRoles();
        if ($this->option('drop')) {
            $this->dropIsManagerColumn();
        }
    }

    private function syncAdminRoles()
    {
        $this->warn('Migrating `admin_roles` table data..');
        foreach ($this->adminRepository->getAll() as $admin) {
            $savedAdminRoles = [ADMIN_ROLE_REGULAR];
            if ($admin->is_manager == 1) {
                $savedAdminRoles[] = ADMIN_ROLE_MANAGER;
            }
            try {
                $this->adminRoleRepository->sync($admin->id, $savedAdminRoles);
                $this->info('Roles of Admin ID ' . $admin->id . ' were synced successfully');
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
    }

    private function dropIsManagerColumn()
    {
        $this->warn('Dropping `is_manager` column..');
        try {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('is_manager');
            });
            $this->info('Column admins.is_manager was dropped successfully');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    private function rollBack()
    {
        if (count(DB::select('SHOW COLUMNS FROM admins like \'is_manager\'')) <= 0) {
            $this->warn('Re-add column `is_manager`...');
            Schema::table('admins', function (Blueprint $table) {
                $table->boolean('is_manager')->nullable()->default(0)->after('image');
            });
            $this->info('Column `is_manager` was re-added...');
        }
        $this->warn('Re-set `is_manager` value...');
        foreach ($this->adminRoleRepository->getAll() as $adminRole) {
            if ($adminRole->role == ADMIN_ROLE_MANAGER) {
                $adminRole->admin()->update([
                    'is_manager' => 1,
                ]);
                $this->info('Admin ID ' . $adminRole->admin_id . ': `is_manager` was set to 1...');
            }
        }
    }
}
