<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\SessionService;

class RolesAndPermissionsController extends Controller {

    private SessionService $sessionService;

    public function __construct(SessionService $sessionService) {
        $this->sessionService = $sessionService;
    }

    public function updateRole(Request $request) {
        $role = $this->sessionService->updateRole($request);
        if (!isset($role) || $role == null) {
            return abort(404);
        }
        return view('components/role', [
            "role" => $this->sessionService->getRoleByEncodedId($role->encoded_id),
            "all_permissions" => $this->sessionService->getAllPermissions($role->id),
            "permissions_by_role" => $this->sessionService->getPermissionsByEncodedRoleId($role->id)
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
