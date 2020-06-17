<?php


namespace App\Domain\Article\ViewModel\Transformer;


use App\Domain\Article\ViewModel\Article as ArticleViewModel;
use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use KubAT\PhpSimple\HtmlDomParser;


/**
 * Class ArticleTransformer
 *
 * @package App\Domain\Article\ViewModel\Transformer
 */
class ArticleTransformer {

	/**
	 * @param  Article  $article
	 *
	 * @param  Collection  $connectedArticles
	 *
	 * @return ArticleViewModel
	 */
	public function transform(Article $article, Collection $connectedArticles): ArticleViewModel
	{
		return new ArticleViewModel(
			$article->title,
			$article->slug,
			$article->author,
			Storage::url('public/articles/'.$article->featured_image),
			$article->featured_image_title === null ? '' : $article->featured_image_title,
			$article->featured_image_desc === null ? '' : $article->featured_image_desc,
			$this->generateToCIDs($article->prolog),
			$this->generateToCIDs($article->content),
			$article->tocs()->get()->pluck('title', 'slug'),
			$article->updated_at,
			$connectedArticles,
			$article->calculator === null ? null : route('calculator', $article->calculator->slug),
			$article->calculator === null ? false : true,
			$article->seo_title === null ? '' : $article->seo_title,
			$article->seo_description === null ? '' : $article->seo_description
		);
	}

	/**
	 * Generate a ToC IDs for H2s
	 *
	 * @param  string  $content
	 *
	 * @return string
	 */
	public function generateToCIDs(string $content): string
	{
		$content = HtmlDomParser::str_get_html($content);

		if ($content) {
			$headings = ['h1', 'h2', 'h3', 'h4'];
			foreach ($headings as $heading) {
				foreach ($content->find($heading) as $h) {
					if ( !$h->getAttribute('id')) {
						if ($h->has_child()) {
							$h->setAttribute('id', Str::slug($h->lastChild()->innertext()));
						} else {
							$h->setAttribute('id', Str::slug($h->innertext()));
						}
					}
				}
			}
		}

		return $content;
	}
}
