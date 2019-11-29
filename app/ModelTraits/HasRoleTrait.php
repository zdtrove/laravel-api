<?php


namespace App\ModelTraits;

trait HasRoleTrait
{
    public function hasRole($roles)
    {
        if (empty($roles)) {
            return true;
        }

        $roles = (array)$roles;
        $currentRoles = [$this->role];
        if ($currentRoles[0] == ADMIN) {
            // get sub roles of admin
            array_push($currentRoles, ...$this->adminRoles->pluck('role')->all());
        }
        foreach ($currentRoles as $currentRole) {
            if (in_array($currentRole, $roles)) {
                return true;
            }
        }

        return false;
    }
}
