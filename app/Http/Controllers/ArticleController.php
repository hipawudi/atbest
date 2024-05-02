<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Article;

class ArticleController extends Controller
{
    public function item(Request $request){
        //dd(Article::where('uuid',$request->t)->first());
        return Inertia::render('Article',[
            'article'=>Article::where('uuid',$request->t)->first()
        ]);
    }
}
