<?php

namespace App\Database;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AuthenticationSource;
use Illuminate\Support\Facades\Log;
use App\Database\AuthenticationSourceDao;
use DateTime;

class UsersDao {

    public function insertUser(User $user): User {
        DB::insert(
            "insert into users (email, password, name, avatar, user_status_id, status_comments, 
            created_on, updated_on, updated_by) 
            values (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $user->email,
                $user->password,
                $user->name,
                $user->avatar,
                $user->user_status_id,
                $user->status_comments,
                $user->created_on,
                $user->updated_on,
                $user->updated_by
            ]);
        return $this->getUserByEmail($user->email);
    }

    public function getUserById($id): User {
        $resultSet = DB::select(
            'select * from users where id = ?',
            [
                $id,
            ]);
        return $this->castResultSetToModel($resultSet);
    }

    public function getUserByEmail($email): User {
        $resultSet = DB::select(
            'select * from users where email = ?',
            [
                $email,
            ]);
        return $this->castResultSetToModel($resultSet);
    }

    public function updateUser(User $user) {
        return DB::insert(
            'update users 
            set name = ?, 
            avatar = ?, 
            updated_on = ?, 
            updated_by = ?
            where id = ?',
            [
                $user->name,
                $user->avatar,
                $user->updated_on,
                $user->updated_by,
                $user->id
            ]);
    }

    private function castResultSetToModel($resultSet): User {
        $model = new User([]);
        if ($resultSet == null || $resultSet[0] == null || $resultSet[0]->id == null) {
            return $model;
        }
        $record = $resultSet[0];
        $model = new User([
            'id' => $record->id,
            'email' => $record->email,
            'password' => null,
            'name' => $record->name,
            'avatar' => $record->avatar,
            'user_status_id' => $record->user_status_id,
            'status_comments' => $record->status_comments,
            'created_on' => (new DateTime($record->created_on))->format(DateTime::ATOM),
            'updated_on' => (new DateTime($record->updated_on))->format(DateTime::ATOM),
            'updated_by' => $record->updated_by
        ]);
        return $model;
    }

}
