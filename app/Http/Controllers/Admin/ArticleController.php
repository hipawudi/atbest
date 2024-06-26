<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Classify;
use App\Models\Article;
use App\Models\Organization;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Articles',[
            // 'classifies'=>Classify::whereBelongsTo(session('organization'))->get(),
            'organizations'=>Organization::all(),
            'articleCategories'=>Config::item('article_categories'),
            'articles'=>Article::paginate($request->per_page)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Article',[
            'organizations'=>Organization::all(),
            'articleCategories'=>Config::item('article_categories'),
            'article'=>new Article()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->all();
        $data['user_id']=auth()->user()->id;
        $data['author']=auth()->user()->name;
        $article=Article::create($data);

        if($request->file('thumbnail_upload')){
            $file=$request->file('thumbnail_upload');
            $fileName=$article->id.'_'.$file->getClientOriginalName();
            $file->move(public_path('articles'), $fileName);
            $article->thumbnail='/articles/'.$fileName;
            $article->save();
        }
        

        return redirect()->route('admin.articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return Inertia::render('Admin/Article',[
            'organizations'=>Organization::all(),
            'articleCategories'=>Config::item('article_categories'),
            'article'=>$article
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $data=$request->all();
        if($request->file('thumbnail_upload')){
            $file=$request->file('thumbnail_upload');
            $fileName=$article->id.'_'.$file->getClientOriginalName();
            $file->move(public_path('articles'), $fileName);
            $data['thumbnail']='/articles/'.$fileName;
        }
        $article->update($data);

        return redirect()->route('admin.articles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->back();
    }
    public function deleteImage(Article $article){
        unlink(public_path($article->thumbnail));
        $article->thumbnail=null;
        $article->save();
        return redirect()->back();
    }

}
