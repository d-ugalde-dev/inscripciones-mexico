<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\AuthenticationSourceByUser;
use Illuminate\Http\Request;
use App\Service\AuthService;
use App\Http\Resources\AuthenticationSourceResource;

class AuthenticationSourceController extends Controller
{

    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = array();
        if (!isset($request) || $request == null || $request->session() == null || $request->session()->get('user') == null) {
            $data = [ 'message' => "forbidden"];
            return Response::make($data, 403);
        }
        if (!isset($_GET['user_id']) || $_GET['user_id'] == null) {
            $data = [ 'message' => "user_id field is mandatory"];
            return Response::make($data, 400);
        }
        // /$authenticationSources = AuthenticationSourceByUser::where('user_id', '=', $request->user_id)->paginate(15);
        return $this->authService->getAuthenticationSourcesByUser($this->authService->decode($_GET['user_id']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new AuthenticationSourceByUser($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthenticationSourceByUser $authenticationSourceByUser)
    {
        return new AuthenticationSourceResource($authenticationSourceByUser);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthenticationSourceByUser $authenticationSourceByUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthenticationSourceByUser $authenticationSourceByUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthenticationSourceByUser $authenticationSourceByUser)
    {
        //
    }
}
