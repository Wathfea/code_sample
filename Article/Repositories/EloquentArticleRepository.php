<?php


namespace App\Domain\Article\Repositories;

use App\Domain\Article\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Models\Article;
use App\Models\Toc;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class EloquentArticleRepository
 *
 * @package App\Domain\Article\Repositories
 */
class EloquentArticleRepository implements ArticleRepositoryInterface {

	/**
	 * @var Article
	 */
	public $articleModel;
	/**
	 * @var Toc
	 */
	public $tocModel;

	/**
	 * EloquentArticleRepository constructor.
	 *
	 * @param  Article  $articleModel
	 * @param  Toc  $tocModel
	 */
	public function __construct(Article $articleModel, Toc $tocModel)
	{
		$this->articleModel = $articleModel;
		$this->tocModel = $tocModel;
	}

	/**
	 * @inheritDoc
	 */
	public function createNew(Request $request, ?string $fileName): Article
	{
		Cache::forget('articles.all');
		$articleData = (array) json_decode($request->get('article'));
		$articleData['featured_image'] = $fileName;
		$articleData['calculator_id'] = ($articleData['calculator_id'] == '0') ? null : $articleData['calculator_id'];
		$articleData['menu_calculator_id'] = ($articleData['menu_calculator_id'] == '0') ? null : $articleData['menu_calculator_id'];
		$articleData['order'] = ($articleData['order'] == '') ? null : $articleData['order'];


		/* @var Article $article */
		$article = $this->articleModel->newQuery()->create($articleData);

		if ( !empty((array) json_decode($request->get('tocs')))) {
			foreach ((array) json_decode($request->get('tocs')) as $tocTitle) {
				$article->tocs()->create([
					'slug'  => Str::slug($tocTitle),
					'title' => $tocTitle,
				]);
			}
		}

		if ( !empty((array) json_decode($request->get('connectedArticles')))) {
			foreach ((array) json_decode($request->get('connectedArticles')) as $connected) {
				$article->connectedarticles()->create([
					'connected_article_id' => $connected->id,
				]);
			}
		}

		return $this->articleModel;
	}

	/**
	 * @inheritDoc
	 */
	public function deleteArticle(int $id): bool
	{
		Cache::forget('articles.all');
		Cache::forget("article.{$id}");

		$article = $this->articleModel->newQuery()->find($id);
		//Delete logo
		Storage::disk('public')->delete('articles/'.$article->featured_image);

		return $article->delete();
	}

	/**
	 * @inheritDoc
	 */
	public function getAll(): Collection
	{
		return Cache::remember('articles.all', 60, function () {
			return $this->articleModel->newQuery()->orderBy('id', 'DESC')->get();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getById(int $id): Article
	{
		return Cache::remember("article.{$id}", 60, function () use ($id) {
			return $this->articleModel->newQuery()->find($id);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getConnecteds(Article $article): Collection
	{
		$connecteds = collect([]);
		foreach ($article->connectedarticles as $connected) {
			$connecteds->push($connected->connectedarticle);
		}

		return $connecteds;
	}

	/**
	 * @inheritDoc
	 */
	public function setArticleStatus(Article $article, string $status): bool
	{
		Cache::forget('articles.all');
		Cache::forget("article.{$article->id}");

		$validStatuses = ['DRAFT', 'PUBLISHED'];

		if (in_array($status, $validStatuses)) {
			$article->status = $status;
		}

		return $article->save();
	}

	/**
	 * @inheritDoc
	 */
	public function update(Request $request, Article $article, ?string $fileName): Article
	{
		Cache::forget('articles.all');
		Cache::forget("article.{$article->id}");

		$articleData = json_decode($request->get('article'), true);
		$articleData['featured_image'] = $fileName;
		$articleData['calculator_id'] = ($articleData['calculator_id'] == '0') ? null : $articleData['calculator_id'];
		$articleData['menu_calculator_id'] = ($articleData['menu_calculator_id'] == '0') ? null : $articleData['menu_calculator_id'];
		$articleData['order'] = ($articleData['order'] == '') ? null : $articleData['order'];

		$article->update($articleData);

		$article->tocs()->delete();
		if ( !empty((array) json_decode($request->get('tocs')))) {
			foreach ((array) json_decode($request->get('tocs')) as $tocTitle) {
				$article->tocs()->create([
					'slug'  => Str::slug($tocTitle),
					'title' => $tocTitle,
				]);
			}
		}

		$article->connectedarticles()->delete();
		if ( !empty((array) json_decode($request->get('connectedArticles')))) {
			foreach ((array) json_decode($request->get('connectedArticles')) as $connected) {
				$article->connectedarticles()->create([
					'connected_article_id' => $connected->id,
				]);
			}
		}

		return $article;
	}
}
