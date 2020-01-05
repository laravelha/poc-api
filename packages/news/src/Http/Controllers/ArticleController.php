<?php

namespace Laravelha\News\Http\Controllers;

use Laravelha\News\Models\Article;
use Laravelha\News\Http\Requests\ArticleStoreRequest;
use Laravelha\News\Http\Requests\ArticleUpdateRequest;
use Laravelha\News\Http\Resources\ArticleResource;
use Laravelha\News\Http\Resources\ArticleCollection;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ArticleController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Get(
     *     tags={"/news/articles"},
     *     summary="Display a listing of the resource",
     *     description="get all article on database and paginate then",
     *     path="/news/articles",
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
     *         response="200", description="List of articles"
     *     )
     * )
     *
     * @return ArticleCollection
     */
    public function index()
    {
        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new ArticleCollection(Article::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"/news/articles"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new article on database",
     *     path="/news/articles",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="published_at", type="string"),
     *       )
     *     ),
     *     @OA\Response(
     *         response="201", description="New article created"
     *     )
     * )
     *
     * @param  ArticleStoreRequest $request
     * @return ArticleResource
     */
    public function store(ArticleStoreRequest $request)
    {
        return new ArticleResource(Article::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"/news/articles"},
     *     summary="Display the specified resource.",
     *     description="show article",
     *     path="/news/articles/{article}",
     *     @OA\Parameter(
     *         description="Article id",
     *         in="path",
     *         name="article",
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
     *         response="200", description="Show article"
     *     )
     * )
     *
     * @param  Article $article
     * @return ArticleResource
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * @OA\Put(
     *     tags={"/news/articles"},
     *     summary="Update the specified resource in storage",
     *     description="update a article on database",
     *     path="/news/articles/{article}",
     *     @OA\Parameter(
     *         description="Article id",
     *         in="path",
     *         name="article",
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
     *         )
     *     ),
     *     @OA\Response(
     *         response="201", description="Article updated"
     *     )
     * )
     *
     * @param  ArticleUpdateRequest $request
     * @param  Article $article
     *
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $article->update($request->validated());

        return new ArticleResource($article);
    }

    /**
     * @OA\Delete(
     *     tags={"/news/articles"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a article on database",
     *     path="/news/articles/{article}",
     *     @OA\Parameter(
     *         description="Article id",
     *         in="path",
     *         name="article",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Article deleted"
     *     )
     * )
     *
     * @param  Article $article
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
}
