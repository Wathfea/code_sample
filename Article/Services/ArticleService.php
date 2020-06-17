<?php


namespace App\Domain\Article\Services;


use App\Domain\Article\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Domain\Article\ViewModel\Article as ArticleViewModel;
use App\Domain\Article\ViewModel\Transformer\ArticleListTransformer;
use App\Domain\Article\ViewModel\Transformer\ArticleTransformer;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


/**
 * Class ArticleService
 *
 * @package App\Domain\Article\Services
 */
class ArticleService {

	/**
	 * @var ArticleRepositoryInterface
	 */
	public $articleRepository;
	/**
	 * @var ArticleTransformer
	 */
	public $articleTransformer;
	/**
	 * @var ArticleListTransformer
	 */
	public $articleListTransformer;

	/**
	 * ArticleService constructor.
	 *
	 * @param  ArticleRepositoryInterface  $articleRepository
	 * @param  ArticleTransformer  $articleTransformer
	 * @param  ArticleListTransformer  $articleListTransformer
	 */
	public function __construct(
		ArticleRepositoryInterface $articleRepository,
		ArticleTransformer $articleTransformer,
		ArticleListTransformer $articleListTransformer
	) {
		$this->articleRepository = $articleRepository;
		$this->articleTransformer = $articleTransformer;
		$this->articleListTransformer = $articleListTransformer;
	}

	/**
	 * @param  Article  $article
	 *
	 * @return ArticleViewModel
	 */
	public function assembleArticle(Article $article): ArticleViewModel
	{
		return $this->articleTransformer->transform($article, $this->getConnecteds($article));
	}

	/**
	 * @param  Article  $article
	 *
	 * @return Collection
	 */
	public function getConnecteds(Article $article): Collection
	{
		return $this->articleRepository->getConnecteds($article)->map(function ($article) {
			return $this->articleListTransformer->transform($article);
		});
	}

	/**
	 * @param  Request  $request
	 * @param  string|null  $fileName
	 *
	 * @return Article
	 */
	public function createNewArticle(Request $request, ?string $fileName): Article
	{
		return $this->articleRepository->createNew($request, $fileName);
	}

	/**
	 * @return Collection
	 */
	public function getAll(): Collection
	{
		return $this->articleRepository->getAll()->map(function ($article) {
			return $this->articleListTransformer->transform($article);
		});
	}

	/**
	 * @param  int  $id
	 *
	 * @return Article
	 */
	public function getArticleById(int $id): Article
	{
		return $this->articleRepository->getById($id);
	}

	/**
	 * @param  int  $id
	 *
	 * @return bool
	 */
	public function removeArticle(int $id): bool
	{
		return $this->articleRepository->deleteArticle($id);
	}

	/**
	 * @param  Article  $article
	 * @param  string  $status
	 *
	 * @return bool
	 */
	public function setStatus(Article $article, string $status): bool
	{
		return $this->articleRepository->setArticleStatus($article, $status);
	}

	/**
	 * @param  Request  $request
	 * @param  Article  $article
	 * @param  string|null  $fileName
	 *
	 * @return Article
	 */
	public function updateArticle(Request $request, Article $article, ?string $fileName): Article
	{
		return $this->articleRepository->update($request, $article, $fileName);
	}
}
