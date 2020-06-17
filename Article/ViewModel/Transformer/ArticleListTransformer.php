<?php


namespace App\Domain\Article\ViewModel\Transformer;


use App\Domain\Article\ViewModel\Lists\ArticleList;
use App\Models\Article;

/**
 * Class ArticleListTransformer
 *
 * @package App\Domain\Article\ViewModel\Transformer
 */
class ArticleListTransformer {

	/**
	 * @param  Article  $article
	 *
	 * @return ArticleList
	 */
	public function transform(Article $article): ArticleList
	{
		return new ArticleList(
			$article->id,
			$article->title,
			$article->slug,
			$article->author,
			$article->status,
			$article->menucalculator ? $article->menucalculator->name : 'Blog',
			$article->updated_at,
			route('articles.edit', $article->id),
			$article->order ? $article->order : null,
			route('article', $article->slug)
		);
	}
}
