<?php

namespace Fluxlabs\FluxIliasRestApi\Channel\UserRole\Command;

use Fluxlabs\FluxIliasRestApi\Adapter\Api\Role\RoleDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\User\UserDto;
use Fluxlabs\FluxIliasRestApi\Adapter\Api\UserRole\UserRoleDto;
use Fluxlabs\FluxIliasRestApi\Channel\Role\Port\RoleService;
use Fluxlabs\FluxIliasRestApi\Channel\User\Port\UserService;
use ILIAS\DI\RBACServices;

class AddUserRoleCommand
{

    private RBACServices $rbac;
    private RoleService $role;
    private UserService $user;


    public static function new(UserService $user, RoleService $role, RBACServices $rbac) : /*static*/ self
    {
        $command = new static();

        $command->user = $user;
        $command->role = $role;
        $command->rbac = $rbac;

        return $command;
    }


    public function addUserRoleByIdByRoleId(int $id, int $role_id) : ?UserRoleDto
    {
        return $this->addUserRole(
            $this->user->getUserById(
                $id
            ),
            $this->role->getRoleById(
                $role_id
            )
        );
    }


    public function addUserRoleByIdByRoleImportId(int $id, string $role_import_id) : ?UserRoleDto
    {
        return $this->addUserRole(
            $this->user->getUserById(
                $id
            ),
            $this->role->getRoleByImportId(
                $role_import_id
            )
        );
    }


    public function addUserRoleByImportIdByRoleId(string $import_id, int $role_id) : ?UserRoleDto
    {
        return $this->addUserRole(
            $this->user->getUserByImportId(
                $import_id
            ),
            $this->role->getRoleById(
                $role_id
            )
        );
    }


    public function addUserRoleByImportIdByRoleImportId(string $import_id, string $role_import_id) : ?UserRoleDto
    {
        return $this->addUserRole(
            $this->user->getUserByImportId(
                $import_id
            ),
            $this->role->getRoleByImportId(
                $role_import_id
            )
        );
    }


    private function addUserRole(?UserDto $user, ?RoleDto $role) : ?UserRoleDto
    {
        if ($user === null || $role === null) {
            return null;
        }

        if (!$this->rbac->review()->isAssigned($user->getId(), $role->getId())) {
            $this->rbac->admin()->assignUser($role->getId(), $user->getId());
        }

        return UserRoleDto::new(
            $user->getId(),
            $user->getImportId(),
            $role->getId(),
            $role->getImportId()
        );
    }
}