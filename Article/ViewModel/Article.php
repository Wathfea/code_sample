<?php


namespace App\Domain\Article\ViewModel;

/**
 * Class Article
 *
 * @package App\Domain\Article\ViewModel
 */
class Article {

	/**
	 * @var string
	 */
	public $title;
	/**
	 * @var string
	 */
	public $slug;
	/**
	 * @var string
	 */
	public $author;
	/**
	 * @var string
	 */
	public $featured_image;
	/**
	 * @var string
	 */
	public $prolog;
	/**
	 * @var string
	 */
	public $content;
	/**
	 * @var Object
	 */
	public $toc;
	/**
	 * @var string
	 */
	public $updated_at;
	/**
	 * @var Object
	 */
	public $connectedArticles;
	/**
	 * @var string|null
	 */
	public $calculatorUrl;
	/**
	 * @var string
	 */
	public $featured_image_title;
	/**
	 * @var string
	 */
	public $featured_image_desc;
	/**
	 * @var bool
	 */
	public $showCalculator;
	/**
	 * @var string
	 */
	public $seoTitle;
	/**
	 * @var string
	 */
	public $seoDescription;

	/**
	 * Article constructor.
	 *
	 * @param  string  $title
	 * @param  string  $slug
	 * @param  string  $author
	 * @param  string  $featured_image
	 * @param  string|null  $featured_image_title
	 * @param  string|null  $featured_image_desc
	 * @param  string  $prolog
	 * @param  string  $content
	 * @param  Object  $toc
	 * @param  string  $updated_at
	 * @param  Object  $connectedArticles
	 * @param  string|null  $calculatorUrl
	 * @param  bool  $showCalculator
	 * @param  string|null  $seoTitle
	 * @param  string|null  $seoDescription
	 */
	public function __construct(
		string $title,
		string $slug,
		string $author,
		string $featured_image,
		?string $featured_image_title,
		?string $featured_image_desc,
		string $prolog,
		string $content,
		object $toc,
		string $updated_at,
		object $connectedArticles,
		?string $calculatorUrl,
		bool $showCalculator,
		?string $seoTitle,
		?string $seoDescription
	) {
		$this->title = $title;
		$this->slug = $slug;
		$this->author = $author;
		$this->featured_image = $featured_image;
		$this->prolog = $prolog;
		$this->content = $content;
		$this->toc = $toc;
		$this->updated_at = $updated_at;
		$this->connectedArticles = $connectedArticles;
		$this->calculatorUrl = $calculatorUrl;
		$this->featured_image_title = $featured_image_title;
		$this->featured_image_desc = $featured_image_desc;
		$this->showCalculator = $showCalculator;
		$this->seoTitle = $seoTitle;
		$this->seoDescription = $seoDescription;
	}
}
