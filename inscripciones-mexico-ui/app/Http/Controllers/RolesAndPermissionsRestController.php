<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\SessionService;

class RolesAndPermissionsRestController extends Controller {

    private SessionService $sessionService;

    public function __construct(SessionService $sessionService) {
        $this->sessionService = $sessionService;
    }
    
    public function relatePermissionToRole(Request $request) {
        
        if (!isset($request) || $request == null || $request->session() == null || $request->session()->get('user') == null || !$request->session()->get('user')->hasPermission('session.profile.roles-and-permissions.write')) {
            return response()->json(array('message' => "forbidden"), 403);
        }

        $requestJSONObject = json_decode($request->request->get('body'));

        $permissionEncodedId = $requestJSONObject->permission_encoded_id;
        $roleEncodedId = $requestJSONObject->role_encoded_id;
        $relate = $requestJSONObject->relate;

        $result = $this->sessionService->relatePermissionToRole($permissionEncodedId, $roleEncodedId, $relate);

        if ($result < 1) {
            return response()->json(
                array('message' => "error"),
                500);
        }

        return response()->json([
            'permission_encoded_id' => $permissionEncodedId,
            'role_encoded_id' => $roleEncodedId,
            'relate' => $relate
        ]);
        
    }

}
