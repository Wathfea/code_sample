<?php


namespace App\Domain\Article\ViewModel\Lists;


/**
 * Class ArticleList
 *
 * @package App\Domain\Article\ViewModel\Lists
 */
class ArticleList {

	/**
	 * @var int
	 */
	public $id;
	/**
	 * @var string
	 */
	public $updatedAt;
	/**
	 * @var string
	 */
	public $editRoute;
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
	public $status;
	/**
	 * @var string
	 */
	public $menu;
	/**
	 * @var int
	 */
	public $order;
	/**
	 * @var string
	 */
	public $articleRoute;

	/**
	 * ArticleList constructor.
	 *
	 * @param  int  $id
	 * @param  string  $title
	 * @param  string  $slug
	 * @param  string  $author
	 * @param  string  $status
	 * @param  string  $menu
	 * @param  string  $updatedAt
	 * @param  string  $editRoute
	 * @param  int|null  $order
	 * @param  string  $articleRoute
	 */
	public function __construct(
		int $id,
		string $title,
		string $slug,
		string $author,
		string $status,
		string $menu,
		string $updatedAt,
		string $editRoute,
		?int $order,
		string $articleRoute
	) {
		$this->id = $id;
		$this->title = $title;
		$this->slug = $slug;
		$this->author = $author;
		$this->status = $status;
		$this->menu = $menu;
		$this->updatedAt = $updatedAt;
		$this->editRoute = $editRoute;
		$this->order = $order;
		$this->articleRoute = $articleRoute;
	}

	/**
	 * @return string|void
	 */
	public function __toString()
	{
		return serialize(
			[
				'id'           => $this->getId(),
				'title'        => $this->getTitle(),
				'slug'         => $this->getSlug(),
				'author'       => $this->getAuthor(),
				'status'       => $this->getStatus(),
				'menu'         => $this->getMenu(),
				'updatedAt'    => $this->getUpdatedAt(),
				'editRoute'    => $this->getEditRoute(),
				'order'        => $this->getOrder(),
				'articleRoute' => $this->getArticleRoute(),
			]
		);
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param  int  $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param  string  $title
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return $this->slug;
	}

	/**
	 * @param  string  $slug
	 */
	public function setSlug(string $slug): void
	{
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function getAuthor(): string
	{
		return $this->author;
	}

	/**
	 * @param  string  $author
	 */
	public function setAuthor(string $author): void
	{
		$this->author = $author;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * @param  string  $status
	 */
	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getMenu(): string
	{
		return $this->menu;
	}

	/**
	 * @param  string  $menu
	 */
	public function setMenu(string $menu): void
	{
		$this->menu = $menu;
	}

	/**
	 * @return string
	 */
	public function getUpdatedAt(): string
	{
		return $this->updatedAt;
	}

	/**
	 * @param  string  $updatedAt
	 */
	public function setUpdatedAt(string $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	/**
	 * @return string
	 */
	public function getEditRoute(): string
	{
		return $this->editRoute;
	}

	/**
	 * @param  string  $editRoute
	 */
	public function setEditRoute(string $editRoute): void
	{
		$this->editRoute = $editRoute;
	}

	/**
	 * @return int
	 */
	public function getOrder(): int
	{
		return $this->order;
	}

	/**
	 * @param  int  $order
	 */
	public function setOrder(int $order): void
	{
		$this->order = $order;
	}

	/**
	 * @return string
	 */
	public function getArticleRoute(): string
	{
		return $this->articleRoute;
	}

	/**
	 * @param  string  $articleRoute
	 */
	public function setArticleRoute(string $articleRoute): void
	{
		$this->articleRoute = $articleRoute;
	}
}
