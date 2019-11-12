<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use Validator;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        return $this->sendResponse($articles, 'Articles retrieved successfully.');
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

        // return $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            $msg = $message->all();
            return $this->sendError($msg);
        }

        $article = Article::create($request->all());
        $article->categories()->attach($request->category_id);

        return $this->sendResponse($article, 'Article Created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return $this->sendError('Article not found.');
        }
        return $this->sendResponse($article, 'Article retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Article $article)
    {
        $article->update($request->all());
        if(isset($request->category_id)){
         $article->categories()->detach($article->category_id);
         $article->categories()->attach($request->category_id);
     }
     $article->save();
     return $this->sendResponse($article, 'Article updated successfully.');
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $Article->delete();
        return $this->sendResponse($article, 'Article deleted successfully.');
    }
}
