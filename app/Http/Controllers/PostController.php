<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Get(
     *     tags={"/posts"},
     *     summary="Display a listing of the resource",
     *     description="get all post on database and paginate then",
     *     path="/posts",
     *     @OA\Parameter(
     *          name="only",
     *          in="query",
     *          description="filter results using field1;field2;field3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="search results using key:value",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="operators",
     *          in="query",
     *          description="set fileds operators using field1:operator1",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="order results using field:direction",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="with",
     *          in="query",
     *          description="get relations using relation1;relation2;relation3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="define page",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="limit per page",
     *          @OA\Schema(type="int"),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200", description="List of posts"
     *     )
     * )
     *
     * @return PostCollection
     */
    public function index()
    {
        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new PostCollection(Post::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"/posts"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new post on database",
     *     path="/posts",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="published_at", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *       )
     *     ),
     *     @OA\Response(
     *         response="201", description="New post created"
     *     )
     * )
     *
     * @param  PostStoreRequest $request
     * @return PostResource
     */
    public function store(PostStoreRequest $request)
    {
        return new PostResource(Post::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"/posts"},
     *     summary="Display the specified resource.",
     *     description="show post",
     *     path="/posts/{post}",
     *     @OA\Parameter(
     *         description="Post id",
     *         in="path",
     *         name="post",
     *         required=true,
     *        @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="only",
     *          in="query",
     *          description="filter results using field1;field2;field3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="with",
     *          in="query",
     *          description="get relations using relation1;relation2;relation3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200", description="Show post"
     *     )
     * )
     *
     * @param  Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * @OA\Put(
     *     tags={"/posts"},
     *     summary="Update the specified resource in storage",
     *     description="update a post on database",
     *     path="/posts/{post}",
     *     @OA\Parameter(
     *         description="Post id",
     *         in="path",
     *         name="post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="published_at", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201", description="Post updated"
     *     )
     * )
     *
     * @param  PostUpdateRequest $request
     * @param  Post $post
     *
     * @return PostResource
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    /**
     * @OA\Delete(
     *     tags={"/posts"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a post on database",
     *     path="/posts/{post}",
     *     @OA\Parameter(
     *         description="Post id",
     *         in="path",
     *         name="post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Post deleted"
     *     )
     * )
     *
     * @param  Post $post
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
