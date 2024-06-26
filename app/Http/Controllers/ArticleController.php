<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Article;

class ArticleController extends Controller
{
    public function item(Request $request){
        //dd(Article::where('uuid',$request->t)->first());
        if(empty($request->t)){
            return to_route('/');
        }else if(session('member')){
            $article=Article::where('uuid',$request->t)->where('published',true)->first();
        }else{
            $article=Article::where('uuid',$request->t)->where('published',true)->where('public',true)->first();
        }
        return Inertia::render('Article',[
            'article'=>$article
        ]);
    }
}
