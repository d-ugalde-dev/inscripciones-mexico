<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Service\SessionService;
use App\Models\User;

class SessionController extends Controller {

    private SessionService $sessionService;

    public function __construct(SessionService $sessionService) {
        $this->sessionService = $sessionService;
    }

    public function roles(Request $request) {
        if (!isset($request) || $request == null || $request->session() == null || $request->session()->get('user') == null || !$request->session()->get('user')->hasPermission('session.profile.roles-and-permissions.read')) {
            return response()->json(array('message' => "forbidden"), 403);
        }
        return view('components/roles-and-permissions', [
            "roles" => $this->sessionService->getRoles()
        ]);
    }

    public function role(Request $request) {
        if (!isset($request) || $request == null || $request->session() == null || $request->session()->get('user') == null || !$request->session()->get('user')->hasPermission('session.profile.roles-and-permissions.write')) {
            return response()->json(array('message' => "forbidden"), 403);
        }
        if (!isset($request->roleId) || $request->roleId == null || $request->roleId == '') {
            return response()->json(array('message' => "bad request"), 400);
        }
        return view('components/role', [
            "role" => $this->sessionService->getRoleByEncodedId($request->roleId),
            "all_permissions" => $this->sessionService->getAllPermissions($request->roleId),
            "permissions_by_role" => $this->sessionService->getPermissionsByEncodedRoleId($request->roleId)
        ]);
    }

}
