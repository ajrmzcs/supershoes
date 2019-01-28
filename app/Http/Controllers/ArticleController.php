<?php

namespace App\Http\Controllers;

use App\Article;
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

    public function getArticlesByStore($store)
    {

        $validator = Validator::make(['store' => $store], [
            'store' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'name' => 'required | max:100',
            'price' => 'required | numeric',
            'total_in_shelf' => 'required | integer',
            'total_in_vault' => 'required | integer',
            'store_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Find store id from store name, because
        // Article's api response does not include store_id.
        $store = Store::where('name', $request->store_name)->first();

        // Additional store id validation
        if (empty($store)) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
                'errors' => [
                    'store_name' => [
                        ['Invalid store name.']
                    ]
                ]
            ], 400);
        }

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'name' => 'required | max:100',
            'price' => 'required | numeric',
            'total_in_shelf' => 'required | integer',
            'total_in_vault' => 'required | integer',
            'store_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Find store id from store name, because
        // Article's api response does not include store_id.
        $store = Store::where('name', $request->store_name)->first();

        // Additional store id validation
        if (empty($store)) {
            return response()->json([
                'error_msg' => 'Bad Request',
                'error_code' => 400,
                'success' => false,
                'errors' => [
                    'store_name' => [
                        ['Invalid store name.']
                    ]
                ]
            ], 400);
        }

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
