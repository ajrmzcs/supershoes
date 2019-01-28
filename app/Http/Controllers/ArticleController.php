<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\Article\ArticlesCollection;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return ArticlesCollection
     */
    public function index()
    {
        return new ArticlesCollection(Article::with('store')->get());
    }

    /**
     * Display a list of articles by store id
     * @param int $store
     * @return ArticlesCollection|\Illuminate\Http\JsonResponse
     */
    public function getArticlesByStore($store)
    {

        $validator = Validator::make(['store' => $store], [
            'store' => 'required | integer | exists:stores,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $articlesByStore = Article::where('store_id', $store)->get();

        if (! $articlesByStore->isEmpty()) {
            return new ArticlesCollection($articlesByStore);
        } else {
            return response()->json([
                'error_msg' => 'Record not Found',
                'error_code' => 404,
                'success' => false,
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleRequest $request)
    {
        // Find store id from store name, because
        // Article's api response does not include store_id.
        $store = Store::where('name', $request->store_name)->first();

        try {
            Article::create([
                'description' => $request->description,
                'name' => $request->name,
                'price' => $request->price,
                'total_in_shelf' => $request->total_in_shelf,
                'total_in_vault' => $request->total_in_vault,
                'store_id' => $store->id
            ]);

            return response()->json([
                'msg' => 'Article created.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        try {
            return response()->json([
                'article' => new ArticleResource($article),
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param ArticleRequest $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ArticleRequest $request, Article $article)
    {
        // Find store id from store name, because
        // Article's api response does not include store_id.
        $store = Store::where('name', $request->store_name)->first();

        try {

            $article->description = $request->description;
            $article->name = $request->name;
            $article->price = $request->price;
            $article->total_in_shelf = $request->total_in_shelf;
            $article->total_in_vault = $request->total_in_vault;
            $article->store_id = $store->id;

            $article->save();

            return response()->json([
                'msg' => 'Article updated.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return response()->json([
                'msg' => 'Article deleted.',
                'success' => true
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'error_msg' => $e->getMessage(),
                'error_code' => 500,
                'success' => false,
            ], 500);
        }
    }
}
