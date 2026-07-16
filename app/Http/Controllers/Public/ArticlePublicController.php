<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticlePublicController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::query()
            ->where('is_published', true)
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->string('kategori')))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Article::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.articles.index', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        $related = Article::query()
            ->where('is_published', true)
            ->whereKeyNot($article->getKey())
            ->when($article->category, fn ($q) => $q->where('category', $article->category))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.articles.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
