<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
// use App\Http\Requests\UpdatePostRequest;

use App\Exceptions\GeneralJsonException;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use App\Rules\IntegerArray;
use Illuminate\Http\Resources\Json\ResourceCollection;
// use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  Illuminate\Http\Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        // throw new GeneralJsonException('some error', 422);
        $pageSize = $request->page_size ?? 20;
        $posts = Post::query()->paginate($pageSize);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return PostResource
     */
    public function store(Request $request, PostRepository $repository)
    {
        $payload = $request->only([
            'title',
            'body',
            'user_ids'
        ]);
        $validator = Validator::make($payload, [
            'title' => 'string|required',
            'body' => ['string', 'required'],
            'user_ids' => [
                    'array',
                    'required',
                    new IntegerArray()
                ]
        ], [
            'body.required' => 'Please enter a value for body',
            'title.string' => 'Use a string'
        ], [
            'user_ids' => 'USER ID'
        ]);

        

        $validator->validate();

        $created = $repository->create($payload);
        //     'title',
        //     'body',
        //     'user_ids'
        // ]));

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return PostResource | JsonResponse
     */
    public function update(Request $request, Post $post, PostRepository $repository)
    {
        $post = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @param  PostRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->forceDelete($post);
        return new JsonResponse([
            'data' => 'success'
        ]);
    }
}
