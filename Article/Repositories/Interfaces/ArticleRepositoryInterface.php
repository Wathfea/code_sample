<?php


namespace App\Domain\Article\Repositories\Interfaces;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Interface ArticleRepositoryInterface
 *
 * @package App\Domain\Article\Repositories\Interfaces
 */
interface ArticleRepositoryInterface {

	/**
	 * @param  Request  $request
	 * @param  string|null  $fileName
	 *
	 * @return Article
	 */
	public function createNew(Request $request, ?string $fileName): Article;

	/**
	 * @param  int  $id
	 *
	 * @return bool
	 */
	public function deleteArticle(int $id): bool;

	/**
	 * @return Collection
	 */
	public function getAll(): Collection;

	/**
	 * @param  int  $id
	 *
	 * @return Article
	 */
	public function getById(int $id): Article;

	/**
	 * @param  Article  $article
	 *
	 * @return Collection
	 */
	public function getConnecteds(Article $article): Collection;

	/**
	 * @param  Article  $article
	 *
	 * @param  string  $status
	 *
	 * @return bool
	 */
	public function setArticleStatus(Article $article, string $status): bool;

	/**
	 * @param  Request  $request
	 * @param  Article  $article
	 * @param  string|null  $fileName
	 *
	 * @return Article
	 */
	public function update(Request $request, Article $article, ?string $fileName): Article;
}
