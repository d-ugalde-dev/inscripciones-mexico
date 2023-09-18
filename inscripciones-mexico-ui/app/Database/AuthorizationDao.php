<?php

namespace App\Database;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class AuthorizationDao {

    public function getRoles() {
        $resultSet = DB::select('select * from roles order by name asc');
        $roles = array();
        foreach ($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                array_push($roles, $this->castResultSetToRoleModel($record));
            }
        }
        return $roles;
    }

    public function getRoleById($id): Role {
        $resultSet = DB::select(
            'select * from roles where id = ?',
            [
                $id
            ]);
        foreach ($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                return $this->castResultSetToRoleModel($record);
            }
        }
        return new Role([]);
    }

    public function getPermissions() {
        $resultSet = DB::select('select * from permissions order by name asc');
        $permissions = array();
        foreach ($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                array_push($permissions, $this->castResultSetToPermissionModel($record));
            }
        }
        return $permissions;
    }

    public function getRolesByUser($user_id) {
        $resultSet = DB::select(
            'select * from roles where id in (
                select role_id from roles_by_user where user_id = ?
            )',
            [
                $user_id
            ]);
        $roles = array();
        foreach ($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                array_push($roles, $this->castResultSetToRoleModel($record));
            }
        }
        return $roles;
    }

    public function getPermissionsByUser($user_id) {
        $resultSet = DB::select(
            'select * from permissions where id in ( 
                select permission_id from permissions_by_role where role_id in (select role_id from roles_by_user where user_id = ?) 
                union 
                select permission_id from permissions_by_user where user_id = ? 
            )
            ',
            [
                $user_id,
                $user_id
            ]);
        $permissions = array();
        foreach($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                array_push($permissions, $this->castResultSetToPermissionModel($record));
            }
        }
        return $permissions;
    }

    public function getPermissionsByRoleId($role_id) {
        $resultSet = DB::select(
            'select * from permissions where id in (
                select permission_id from permissions_by_role where role_id = ?
            ) order by name asc
            ',
            [
                $role_id
            ]);
        $permissions = array();
        foreach($resultSet as $record) {
            if ($record != null && $record->id > 0) {
                array_push($permissions, $this->castResultSetToPermissionModel($record));
            }
        }
        return $permissions;
    }

    public function relatePermissionToRole($permission_id, $role_id, $relate) {
        if ($relate == 'false' || $relate == false || $relate == 0) {
            return DB::insert(
                'delete from permissions_by_role where permission_id = ? and role_id = ?',
                [
                    $permission_id,
                    $role_id
                ]);
        } else {
            return DB::insert(
                'insert into permissions_by_role (permission_id, role_id) values (?, ?)',
                [
                    $permission_id,
                    $role_id
                ]);
        }
    }

    public function updateRole(Role $role) {
        return DB::insert(
            'update roles set name = ? where id = ?',
            [
                $role->name,
                $role->id
            ]);
    }

    private function castResultSetToRoleModel($record): Role {
        $model = new Role([]);
        if ($record == null || $record->id == null || $record->name == null) {
            return $model;
        }
        $model = new Role([
            'id' => $record->id,
            'name' => $record->name,
            'description' => $record->description
        ]);
        return $model;
    }

    private function castResultSetToPermissionModel($record): Permission {
        $model = new Permission([]);
        if ($record == null || $record->id == null || $record->name == null) {
            return $model;
        }
        $model = new Permission([
            'id' => $record->id,
            'name' => $record->name,
            'description' => $record->description
        ]);
        return $model;
    }

}
