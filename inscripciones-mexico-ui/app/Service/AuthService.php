<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AuthenticationSourceByUser;
use Illuminate\Support\Carbon;
use App\Database\UsersDao;
use App\Database\AuthenticationSourceByUserDao;
use App\Database\AuthorizationDao;
use Hashids\Hashids;
use Illuminate\Http\Request;

class AuthService {

    const USER_STATUS_ID_ACTIVE = 1;

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

    public function handleLoginSocialite($socialiteUser, $authentication_source_id) {
        $dateTimeNowUTC = Carbon::now(env("APP_TIMEZONE"));

        // Check if user exists
        $user = $this->usersDao->getUserByEmail($socialiteUser->email);

        if (!$this->userExists($user)) {
            // User does not exist, we will create it
            $user = new User([
                'id' => null,
                'email' => $socialiteUser->email,
                'password' => null,
                'name' => $socialiteUser->name,
                'avatar' => $socialiteUser->avatar,
                'user_status_id' => self::USER_STATUS_ID_ACTIVE,
                'status_comments' => null,
                'created_on' => $dateTimeNowUTC,
                'updated_on' => null,
                'updated_by' => null
            ]);
            $user = $this->usersDao->insertUser($user);
        }

        // Now the user should exist
        $user = $this->usersDao->getUserByEmail($socialiteUser->email);
        
        // Check if we already captured this authentication source
        $authenticationSourceDatabase = $this->authenticationSourceByUserDao->getAuthenticationSourceByUser(
            $authentication_source_id,
            $user->id);

        if (!$this->authenticationSourceByUserExists($authenticationSourceDatabase)) {
            // Insert new authentication source by user
            $authenticationSourceByUser = new AuthenticationSourceByUser([
                'authentication_source_id' => $authentication_source_id,
                'user_id' => $user->id,
                'name' => $user->name,
                'avatar' => $socialiteUser->avatar,
                'last_login_on' => $dateTimeNowUTC,
                'created_on' => $dateTimeNowUTC,
                'updated_on' => null,
                'updated_by' => null
            ]);
            $authenticationSourceByUser = $this->authenticationSourceByUserDao->insertAuthenticationSourceByUser($authenticationSourceByUser);
        } else {
            // Update new authentication source by user
            $authenticationSourceByUser = $authenticationSourceDatabase;
            $authenticationSourceByUser->name = $socialiteUser->name;
            $authenticationSourceByUser->avatar = $socialiteUser->avatar;
            $authenticationSourceByUser->last_login_on = $dateTimeNowUTC;
            $authenticationSourceByUser->updated_on = $dateTimeNowUTC;
            $authenticationSourceByUser->updated_by = $user->id;

            $authenticationSourceByUser = $this->authenticationSourceByUserDao->updateAuthenticationSourceByUser($authenticationSourceByUser);
        }

        $user = $this->usersDao->getUserById($user->id);
        $user->encoded_id = $this->encode($user->id);
        $user->roles = $this->getRolesByUser($user);
        $user->permissions = $this->getPermissionsByUser($user);
        return $user;
    }

    public function getRolesByUser(User $user) {
        $roles = $this->authorizationDao->getRolesByUser($user->id);
        $rolesString = ',';
        foreach ($roles as $role) {
            $rolesString = $rolesString . $role->name . ',';
        }
        return $rolesString;
    }

    public function getPermissionsByUser(User $user) {
        $permissions = $this->authorizationDao->getPermissionsByUser($user->id);
        $permissionsString = ',';
        foreach ($permissions as $permission) {
            $permissionsString = $permissionsString . $permission->name . ',';
        }
        return $permissionsString;
    }

    public function updateProfile(Request $request) {
        if ($request == null || !isset($request->encodedId) || $request->encodedId == null) {
            return null;
        }
        $user = $this->usersDao->getUserById($this->decode($request->encodedId));
        if ($user == null || $user->id == null || $user->id < 1) {
            return null;
        }

        $user->name = $request->name;
        $user->updated_on = Carbon::now(env("APP_TIMEZONE"));
        $user->updated_by = $request->session()->get('user')->id;

        $result = $this->usersDao->updateUser($user);
        if ($result < 1) {
            return null;
        }
        $user = $this->usersDao->getUserById($user->id);
        $user->encoded_id = $this->encode($user->id);
        return $user;
    }

    public function getUserByid($userId): User {
        $user = $this->usersDao->getUserById($userId);
        $user->encoded_id = $this->encode($user->id);
        return $user;
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

    public function getAuthenticationSourcesByUser($userId) {
        return $this->authenticationSourceByUserDao->getAuthenticationSourcesByUser($userId);
    }

    private function userExists(User $user) {
        return $user != null && $user->id != null && $user->id > 0;
    }

    private function authenticationSourceByUserExists(AuthenticationSourceByUser $authenticationSourceByUser) {
        return ($authenticationSourceByUser != null && $authenticationSourceByUser->authentication_source_id != null && $authenticationSourceByUser->user_id != null);
    }

}
