<?php namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use Auth;
use App\Http\Requests;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ArticlesController extends Controller {

	/**
	 * Create a new controller instance
     */
	function __construct(){
		$this->middleware('auth', ['except' => [ 'index', 'show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$articles = Article::latest('published_at')->where('published_at', '<=', Carbon::now())->get();
		$articles = Article::latest('published_at')->published()->get();

		return view('articles.index', compact('articles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$tags = Tag::lists('name', 'id');

		return view('articles.create', compact('tags'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateArticleRequest $request)
	{
		$this->createArticle($request);

		flash()->success('Your article has been created!', 'Good job');

        return redirect('articles');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Article $article
	 * @return Response
	 */
	public function show(Article $article)
	{

		//$article = Article::findOrFail($id);
		//dd($article->created_at->addDays(8)->diffForHumans());
                 
		return view('articles.show', compact('article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Article $article
	 * @return Response
	 */
	public function edit(Article $article)
	{
		$tags = Tag::lists('name', 'id');
		return view('articles.edit', compact('article', 'tags'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Article $article
	 * @return Response
	 */
	public function update(Article $article, CreateArticleRequest $request)
	{
		$article->update($request->all());

		$this->syncTags($article, $request->input('tag_list'));

		return redirect('articles');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return 'Ban da truy cap destroy';
	}

	/**
	 * Dong bo tag list in database
	 * @param Article $article
	 * @param array $tags
	 *
	 * Sync: vua them (attach), vua xoa tag (detach)-> chi giu lai nhung lua chon cuoi cung khi edit
	 */
	public function syncTags(Article $article, array $tags)
	{
		$article->tags()->sync($tags);
	}

	/**
	 * Save a new article.
	 *
	 * @param ArticleRequest $request
	 * @return mixed
     */
	private function createArticle( ArticleRequest $request)
	{
		/**
		 * Tao moi 1 article va tich hop voi User
		 */
		$article = Auth::user()->articles()->create($request->all());

		/**
		 * Gan tag vao article
		 */
		$this->syncTags($article, $request->input('tag_list'));

		return $articlel;
	}

}
