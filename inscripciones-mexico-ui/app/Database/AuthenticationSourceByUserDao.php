<?php

namespace App\Database;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AuthenticationSourceByUser;
use Illuminate\Support\Facades\Log;

class AuthenticationSourceByUserDao {

    public function insertAuthenticationSourceByUser(AuthenticationSourceByUser $authenticationSourceByUser): AuthenticationSourceByUser {
        DB::insert(
            'insert into authentication_source_by_user (authentication_source_id, user_id, name, avatar, last_login_on, created_on, updated_on, updated_by) 
            values (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $authenticationSourceByUser->authentication_source_id,
                $authenticationSourceByUser->user_id,
                $authenticationSourceByUser->name,
                $authenticationSourceByUser->avatar,
                $authenticationSourceByUser->last_login_on,
                $authenticationSourceByUser->created_on,
                $authenticationSourceByUser->updated_on,
                $authenticationSourceByUser->updated_by
            ]);
        return $this->getAuthenticationSourceByUser($authenticationSourceByUser->authentication_source_id, $authenticationSourceByUser->user_id);
    }
    public function updateAuthenticationSourceByUser(AuthenticationSourceByUser $authenticationSourceByUser): AuthenticationSourceByUser {
        DB::insert(
            'update authentication_source_by_user 
            set name = ?, 
            avatar = ?, 
            last_login_on = ?, 
            updated_on = ?, 
            updated_by = ?
            where authentication_source_id = ? and user_id = ?',
            [
                $authenticationSourceByUser->name,
                $authenticationSourceByUser->avatar,
                $authenticationSourceByUser->last_login_on,
                $authenticationSourceByUser->updated_on,
                $authenticationSourceByUser->updated_by,
                $authenticationSourceByUser->authentication_source_id,
                $authenticationSourceByUser->user_id
            ]);
        return $this->getAuthenticationSourceByUser($authenticationSourceByUser->authentication_source_id, $authenticationSourceByUser->user_id);
    }

    public function getAuthenticationSourceByUser($authentication_source_id, $user_id) {
        $resultSet = DB::select(
            'select * from authentication_source_by_user where authentication_source_id = ? and user_id = ?',
            [
                $authentication_source_id,
                $user_id
            ]);
        if (isset($resultSet) && $resultSet != null && isset($resultSet[0]) && $resultSet[0] != null) {
            return $this->castResultSetToModel($resultSet[0]);
        }
        return new AuthenticationSourceByUser([
            'authentication_source_id' => null,
            'user_id' => null,
            'name' => null,
            'avatar' => null,
            'last_login_on' => null,
            'created_on' => null,
            'updated_on' => null,
            'updated_by' => null
        ]);
    }

    public function getAuthenticationSourcesByUser($user_id) {
        $resultSet = DB::select(
            'select * from authentication_source_by_user where user_id = ?',
            [
                $user_id
            ]);
        $authenticationSources = array();
        foreach($resultSet as $record) {
            array_push($authenticationSources, $this->castResultSetToModel($record));
        }
        return $authenticationSources;
    }

    private function castResultSetToModel($resultSet): AuthenticationSourceByUser {
        $model = new AuthenticationSourceByUser([]);
        if ($resultSet == null || $resultSet->authentication_source_id == null || $resultSet->user_id == null) {
            return $model;
        }
        $model = new AuthenticationSourceByUser([
            'authentication_source_id' => $resultSet->authentication_source_id,
            'user_id' => $resultSet->user_id,
            'name' => $resultSet->name,
            'avatar' => $resultSet->avatar,
            'last_login_on' => $resultSet->last_login_on,
            'created_on' => $resultSet->created_on,
            'updated_on' => $resultSet->updated_on,
            'updated_by' => $resultSet->updated_by
        ]);
        return $model;
    }

}
