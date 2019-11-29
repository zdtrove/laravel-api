<?php

namespace App\Repositories;

use App\Models\AdminRole;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminRoleRepository.
 */
class AdminRoleRepository extends BaseRepository
{
    public function setModel()
    {
        return AdminRole::class;
    }

    /**
     * @param array $attributes
     * @return AdminRole
     * @throws Exception
     *
     */
    public function create(array $attributes)
    {
        $adminRole = null;
        DB::transaction(function () use ($attributes, &$adminRole) {
            try {
                $adminRole = $this->query()->create($attributes);
            } catch (Exception $e) {
                throw new Exception(__('api.admin_role.create.error'));
            }
        });

        return $adminRole;
    }
    
    public function getRoleById($adminId)
    {
        $adminRole = null;
        DB::transaction(function () use ($adminId, &$adminRole) {
            try {
                $adminRole = $this->query()
                ->select('role')
                ->where('admin_id', $adminId)
                ->get();
            } catch (Exception $e) {
                throw new Exception(__('api.admin_role.get.error'));
            }
        });

        return $adminRole;
    }

    public function sync($adminId, array $savedAdminRoleNames)
    {
        try {
            $currentAdminRoles = $this->query()
                ->where('admin_id', $adminId)
                ->get();

            DB::transaction(function () use ($adminId, $savedAdminRoleNames, $currentAdminRoles) {
                $currentAdminRoleNames = $currentAdminRoles->pluck('role')->all();
                // create new admin role if it does not exist in current role
                foreach ($savedAdminRoleNames as $savedAdminRoleName) {
                    if (!in_array($savedAdminRoleName, $currentAdminRoleNames)) {
                        $this->query()->create([
                            'admin_id' => $adminId,
                            'role' => $savedAdminRoleName,
                        ]);
                    }
                }
                // delete current admin role if it does not exist in saved roles
                $deletedRoleIds = [];
                foreach ($currentAdminRoles as $currentAdminRole) {
                    if (!in_array($currentAdminRole->role, $savedAdminRoleNames)) {
                        $deletedRoleIds[] = $currentAdminRole->id;
                    }
                }
                if (count($deletedRoleIds) > 0) {
                    $this->query()->whereIn('id', $deletedRoleIds)->delete();
                }
            });
        } catch (Exception $e) {
            throw new Exception(__('api.admin_role.sync.error'));
        }

        return true;
    }
}
