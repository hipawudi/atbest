<?php

namespace App\Http\Controllers\Organization;

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
        //dd(session('member'));
        return Inertia::render('Organization/Articles', [
            'classifies' => Classify::whereBelongsTo(session('organization'))->get(),
            'articleCategories' => Config::item('article_categories'),
            'articles' => Article::whereBelongsTo(session('organization'))->orderBy('sequence','asc')->paginate($request->per_page)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = Article::make([
            'organization_id' => session('organization')->id,
            'published' => false,
            'public' => false
        ]);
        return Inertia::render('Organization/Article', [
            'classifies' => Classify::whereBelongsTo(session('organization'))->get(),
            'articleCategories' => Config::item('article_categories'),
            'article' => $article
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
        $data = $request->all();
        $data['organization_id'] = session('organization')->id;
        $data['user_id'] = auth()->user()->id;
        $data['author'] = auth()->user()->name;
        $data['sequence'] = Article::where('organization', session('organization')->id)->all()->count() + 1;
        $article = Article::create($data);

        if ($request->file('thumbnail_upload')) {
            $file = $request->file('thumbnail_upload');
            $fileName = $article->id . '_' . $file->getClientOriginalName();
            $file->move(public_path('thumbnail/articles'), $fileName);
            $article->thumbnail = '/thumbnail/articles/' . $fileName;
            $article->save();
        }

        return redirect()->route('manage.articles.index');
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
        return Inertia::render('Organization/Article', [
            'classifies' => Classify::whereBelongsTo(session('organization'))->get(),
            'articleCategories' => Config::item('article_categories'),
            'article' => $article
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
        $data = $request->all();

        if ($request->file('thumbnail_upload')) {
            $file = $request->file('thumbnail_upload');
            $fileName = $article->id . '_' . $file->getClientOriginalName();
            $file->move(public_path('thumbnail/articles'), $fileName);
            $data['thumbnail'] = '/thumbnail/articles/' . $fileName;
        }
        $article->update($data);

        return redirect()->route('manage.articles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if (session('organization')->id == $article->organization_id) {
            $article->delete();
        }
        return redirect()->back();
    }
    public function deleteImage(Article $article)
    {
        unlink(public_path($article->thumbnail));
        $article->thumbnail = null;
        $article->save();
        return redirect()->back();
    }
    public function sequence(Request $request)
    {
        foreach ($request->all() as $row) {
            Article::where('id', $row['id'])->update(['sequence' => $row['sequence']]);
        }
        return redirect()->back();
    }
}
