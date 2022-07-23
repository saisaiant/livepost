<?php

namespace App\Http\Controllers;

use App\Events\Models\User\UserCreated;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group User Management
 *
 * APIs to manage the user resource.
 *
 * 
 **/
class UserController extends Controller
{
    /**
     * Display a listing of users.
     * 
     * Gets a list of users.
     * 
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     * 
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        event(new UserCreated(User::factory()->make()));
        $pageSize = $request->page_size ?? 20;
        $users = User::query()->paginate($pageSize);
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email of the user. Example: johndoe@gmail.com
     * 
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return UserResource
     */
    public function store(Request $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name',
            'email'
        ]));
        
        return new UserResource($created);
    }

    /**
     * Display the specified resource.
     * 
     * @urlParam id int required User ID.
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: johndoe@gmail.com
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return UserResource | JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $user = $repository->update($user, $request->only([
            'name',
            'email'
        ]));       
        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @response 200  {
     *  "data" : "success"
     * }
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $deleted = $repository->forceDelete($user);
        return new JsonResponse([
            'data' => 'success',
        ]);
    }
}
