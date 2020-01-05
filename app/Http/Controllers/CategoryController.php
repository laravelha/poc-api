<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Get(
     *     tags={"/categories"},
     *     summary="Display a listing of the resource",
     *     description="get all category on database and paginate then",
     *     path="/categories",
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
     *         response="200", description="List of categories"
     *     )
     * )
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new CategoryCollection(Category::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"/categories"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new category on database",
     *     path="/categories",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="published_at", type="string"),
     *       )
     *     ),
     *     @OA\Response(
     *         response="201", description="New category created"
     *     )
     * )
     *
     * @param  CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request)
    {
        return new CategoryResource(Category::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"/categories"},
     *     summary="Display the specified resource.",
     *     description="show category",
     *     path="/categories/{category}",
     *     @OA\Parameter(
     *         description="Category id",
     *         in="path",
     *         name="category",
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
     *         response="200", description="Show category"
     *     )
     * )
     *
     * @param  Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     tags={"/categories"},
     *     summary="Update the specified resource in storage",
     *     description="update a category on database",
     *     path="/categories/{category}",
     *     @OA\Parameter(
     *         description="Category id",
     *         in="path",
     *         name="category",
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
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="published_at", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201", description="Category updated"
     *     )
     * )
     *
     * @param  CategoryUpdateRequest $request
     * @param  Category $category
     *
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     tags={"/categories"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a category on database",
     *     path="/categories/{category}",
     *     @OA\Parameter(
     *         description="Category id",
     *         in="path",
     *         name="category",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Category deleted"
     *     )
     * )
     *
     * @param  Category $category
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}
