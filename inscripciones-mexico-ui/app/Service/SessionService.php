<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\AuthenticationSourceByUser;
use Illuminate\Support\Carbon;
use App\Database\UsersDao;
use App\Database\AuthenticationSourceByUserDao;
use App\Database\AuthorizationDao;
use Hashids\Hashids;
use Illuminate\Http\Request;

class SessionService {

    private UsersDao $usersDao;
    private AuthenticationSourceByUserDao $authenticationSourceByUserDao;
    private AuthorizationDao $authorizationDao;
    private Hashids $hashids;

    public function __construct(UsersDao $usersDao, AuthenticationSourceByUserDao $authenticationSourceByUserDao,
            AuthorizationDao $authorizationDao) {
        $this->usersDao = $usersDao;
        $this->authenticationSourceByUserDao = $authenticationSourceByUserDao;
        $this->authorizationDao = $authorizationDao;
        $this->hashids = new Hashids(env('HASHIDS_SALT'), env('HASHIDS_MIN_LENGTH'));
    }

    public function getRoles() {
        $rolesResult = array();
        $roles = $this->authorizationDao->getRoles();
        foreach ($roles as $role) {
            $role->encoded_id = $this->encode($role->id);
            array_push($rolesResult, $role);
        }
        return $rolesResult;
    }

    public function getRoleByEncodedId($encodedRoleId): Role {
        $role = $this->authorizationDao->getRoleById($this->decode($encodedRoleId));
        $role->encoded_id = $this->encode($role->id);
        return $role;
    }

    public function getAllPermissions() {
        $permissionsResult = array();
        $permissions = $this->authorizationDao->getPermissions();
        foreach ($permissions as $permission) {
            $permission->encoded_id = $this->encode($permission->id);
            array_push($permissionsResult, $permission);
        }
        return $permissionsResult;
    }

    public function getPermissionsByEncodedRoleId($encodedRoleId) {
        $permissionsResult = array();
        $permissions = $this->authorizationDao->getPermissionsByRoleId($this->decode($encodedRoleId));
        foreach ($permissions as $permission) {
            $permission->encoded_id = $this->encode($permission->id);
            array_push($permissionsResult, $permission);
        }
        return $permissionsResult;
    }

    public function relatePermissionToRole($permissionEncodedId, $roleEncodedId, $relate) {
        return $this->authorizationDao->relatePermissionToRole($this->decode($permissionEncodedId), $this->decode($roleEncodedId), $relate);
    }

    public function updateRole(Request $request) {
        if ($request == null || !isset($request->encodedId) || $request->encodedId == null) {
            return null;
        }
        $role = $this->authorizationDao->getRoleById($this->decode($request->encodedId));
        if ($role == null || $role->id == null || $role->id < 1) {
            return null;
        }

        $role->name = $request->roleName;
        $result = $this->authorizationDao->updateRole($role);
        if ($result < 1) {
            return $role;
        }
        $role = $this->authorizationDao->getRoleById($role->id);
        $role->encoded_id = $this->encode($role->id);
        return $role;
    }

    public function encode($id) {
        return $this->hashids->encode($id);
    }

    public function decode($encodedId) {
        $decoded = $this->hashids->decode($encodedId);
        if (!isset($decoded) || $decoded == null || $decoded[0] == null) {
            return "";
        }
        return $decoded[0];
    }

}
