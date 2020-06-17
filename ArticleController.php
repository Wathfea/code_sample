<?php


namespace App\Http\Controllers\Admin;

use App\Domain\Article\Services\ArticleService;
use App\Domain\Calculator\Services\CalculatorService;
use App\Domain\Menu\Services\MenuService;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\SlackError;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Class ArticleController
 *
 * @package App\Http\Controllers\Admin
 */
class ArticleController extends Controller {

	use SlackError;

	/**
	 * @var ArticleService
	 */
	public $articleService;
	/**
	 * @var CalculatorService
	 */
	public $calculatorService;
	/**
	 * @var MenuService
	 */
	public $menuService;

	/**
	 * ArticleController constructor.
	 *
	 * @param  ArticleService  $articleService
	 * @param  CalculatorService  $calculatorService
	 * @param  MenuService  $menuService
	 */
	public function __construct(
		ArticleService $articleService,
		CalculatorService $calculatorService,
		MenuService $menuService
	) {
		$this->middleware(['role:super-admin|content-manager']);

		$this->articleService = $articleService;
		$this->calculatorService = $calculatorService;
		$this->menuService = $menuService;
	}

	/**
	 * @return Factory|View
	 */
	public function create()
	{
		try {
			$calculators = $this->calculatorService->getAll();
			$articles = $this->articleService->getAll();

			return view('admin.articles.create', compact('calculators', 'articles'));
		} catch (Exception $exception) {
			$this->generateSlackError($exception, 'Article create');
			Log::error('Article create: '.$exception->getMessage());
		}
	}

	/**
	 * @param  Article  $article
	 *
	 * @return Factory|View
	 */
	public function edit(Article $article)
	{
		try {
			$article->selectedTableOfContents = $article->tocs()->pluck('title', 'slug');
			$article->calculator_id = ($article->calculator_id === null) ? '0' : $article->calculator_id;
			$article->menu_calculator_id = ($article->menu_calculator_id === null) ? '0' : $article->menu_calculator_id;

			$calculators = $this->calculatorService->getAll();
			$articles = $this->articleService->getAll();
			$connectedArticles = $this->articleService->getConnecteds($article);

			$articles = $articles->whereNotIn('id', $article->id);
			$articles = $articles->diff($connectedArticles);

			if ($article->featured_image) {
				$article->featured_image = Storage::url('public/articles/'.$article->featured_image);
			}

			return view('admin.articles.edit', compact('article', 'calculators', 'articles', 'connectedArticles'));
		} catch (Exception $exception) {
			$this->generateSlackError($exception, 'Article edit');
			Log::error('Article edit (ID: '.$article->id.'): '.$exception->getMessage());
		}
	}

	/**
	 * @return Application|Factory|View
	 */
	public function index()
	{
		try {
			$articles = $this->articleService->getAll();

			return view('admin.articles.index', compact('articles'));
		} catch (Exception $exception) {
			$this->generateSlackError($exception, 'Article index');
			Log::error('Article index: '.$exception->getMessage());
		}
	}
}
