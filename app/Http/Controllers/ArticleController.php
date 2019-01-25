<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\Article\ArticlesCollection;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
