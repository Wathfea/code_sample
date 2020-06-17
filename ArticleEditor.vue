<template>
	<div class="row">
		<!-- BAL TARTALOM -->
		<div class="col-8">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Cikk címe</strong>
						<div class="input-group">
							<input @blur="setSlug(article.title)"
							       class="form-control"
							       required
							       type="text"
							       v-model="article.title">
						</div>
						<p class="text-danger" v-if="errors.get('title')">{{ errors.get('title') }}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Cikk menüben szereplő címe</strong>
						<div class="input-group">
							<input class="form-control" required
							       type="text"
							       v-model="article.short_title">
						</div>
						<p class="text-danger" v-if="errors.get('short_title')">{{
							errors.get('short_title') }}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* SLUG</strong>
						<div class="input-group">
							<input @blur="setSlug(saveSlug)" aria-describedby="article-slug-addon" class="form-control"
							       required
							       type="text"
							       v-model="saveSlug"
							>
							<div class="input-group-append">
								<a
										@click.prevent="setSlug(article.title)"
										class="btn btn-md btn-default btn-icon-only"
										href="#"
										id="article-slug-addon"
										title="Refresh slug"
								><i class="fas fa-sync-alt"></i></a>
							</div>
						</div>
						<p class="text-danger" v-if="errors.get('slug')">{{ errors.get('slug') }}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Szerző neve</strong>
						<input class="form-control" required type="text" v-model="article.author">
						<p class="text-danger" v-if="errors.get('author')">{{ errors.get('author')
							}}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Bevezető</strong>
						<editor
								:api-key="tinyMCEApiKey"
								:init="tinyMCEConfig"
								id="article-prolog"
								v-model="articleProlog"
						/>
						<p class="text-danger" v-if="errors.get('prolog')">{{ errors.get('prolog')
							}}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Tartalom</strong>
						<editor
								:api-key="tinyMCEApiKey"
								:init="tinyMCEConfig"
								id="article-content"
								v-model="articleContent"
						/>
						<p class="text-danger" v-if="errors.get('content')">{{ errors.get('content')
							}}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Sidebar kalkulátor</strong>

						<select class="form-control" required v-model="article.calculator_id">
							<option value="0">Nincs</option>
							<option :value="calculator.id" v-for="calculator in calculators">
								{{calculator.calculatorName}}
							</option>
						</select>
						<p class="text-danger" v-if="errors.get('calculator_id')">{{
							errors.get('calculator_id')
							}}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>* Menü kalkulátor</strong>
						<select class="form-control" required v-model="article.menu_calculator_id">
							<option value="0">Blog</option>
							<option :value="calculator.id" v-for="calculator in calculators">
								{{calculator.calculatorName}}
							</option>
						</select>
						<p class="text-danger" v-if="errors.get('menu_calculator_id')">{{
							errors.get('menu_calculator_id')
							}}</p>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<strong>Sorrend</strong>
						<input class="form-control" type="text" v-model="article.order">
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12">
					<a @click.prevent="saveArticle" class="btn btn-primary" href="#" v-if="isCreate">Mentés</a>
					<a @click.prevent="updateArticle" class="btn btn-primary" href="#" v-if="!isCreate">Módosítás</a>


					<a @click.prevent="setStatus('PUBLISHED')" class="btn btn-success" href="#"
					   v-if="!isCreate && article.status === 'DRAFT'">Publikálás</a>

					<a @click.prevent="setStatus('DRAFT')" class="btn btn-warning" href="#"
					   v-if="!isCreate && article.status === 'PUBLISHED'">Piszkozat</a>
				</div>
			</div>
		</div>
		<!-- /BAL TARTALOM -->

		<!-- JOBB TARTALOM -->
		<div class="col-4">
			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<strong>* Featured kép</strong>
						<input class="form-control" id="articleFeaturedImage" ref="featured_image" required
						       type="file" v-on:change="handleFileUpload">

						<p class="text-danger" v-if="errors.get('featured_image')">{{
							errors.get('featured_image')
							}}</p>

						<strong>Kép title</strong>
						<input class="form-control" type="text" v-model="article.featured_image_title">

						<strong>Kép description</strong>
						<input class="form-control" type="text" v-model="article.featured_image_desc">

					</div>
				</div>

				<div class="col-12" v-if="!isCreate && article.featured_image">
					<div class="form-group">
						<strong>Kép</strong>
						<img :src="article.featured_image" height="120px" width="120px">
					</div>
				</div>

				<div class="col-12">
					<div class="form-group">
						<strong>SEO</strong>
						<hr/>

						<strong>Meta title</strong>
						<input class="form-control" type="text" v-model="article.seo_title">

						<strong>Meta description</strong>
						<input class="form-control" type="text" v-model="article.seo_description">
					</div>
				</div>

				<div class="col-12">
					<div class="form-group">
						<strong>Státusz</strong>
						{{ article.status }}
					</div>
				</div>


				<div class="col-12">
					<div class="form-group">
						<strong>Tartalomjegyzék generálása</strong>
						<a @click.prevent="generateTableOfContents" class="btn btn-primary" href="#">Generál</a>
					</div>
				</div>

				<div class="col-12" v-if="selectedTableOfContents.length">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead class="thead-dark">
							<tr>
								<th>Tartalomjegyzék</th>
								<th class="actions">Műveletek</th>
							</tr>
							</thead>
							<draggable :tag="'tbody'" @end="drag=true"
							           @start="drag=true" group="tableofcontents"
							           v-model="selectedTableOfContents">
								<tr class="draggable"
								    v-for="(tableContent, index) in selectedTableOfContents">
									<td>
										<input class="form-control" type="text"
										       v-model="selectedTableOfContents[index]">
									</td>
									<td>
										<a @click.prevent="removeTableOfContent(index)"
										   class="btn btn-icon btn-danger float-right"
										   href="#"
										   title="Törlés">Törlés</a>
									</td>
								</tr>
							</draggable>
						</table>
					</div>
				</div>

				<div class="col-12">
					<div class="form-group">
						<strong>Kapcsolódó cikkek</strong>
						<table class="table table-striped table-hover">
							<thead class="thead-dark">
							<tr>
								<th style="width: 70%">Cikkek</th>
								<th class="actions">Műveletek</th>
							</tr>
							</thead>
							<tr>
								<td>
									<v-select :options="articles" :reduce="articles => articles.id" label="title"
									          v-model="selectedArticle"></v-select>
								</td>
								<td><a @click.prevent="addNewConnectedArticle" class="btn btn-primary"
								       href="#">Hozzáadás</a></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="col-12" v-if="connectedArticles.length">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead class="thead-dark">
							<tr>
								<th>Cikk</th>
								<th class="actions">Műveletek</th>
							</tr>
							</thead>
							<draggable :tag="'tbody'" @end="drag=true"
							           @start="drag=true" group="connectedArticles"
							           v-model="connectedArticles">
								<tr class="draggable"
								    v-for="(article, index) in connectedArticles">
									<td>
										{{ article.title }}
									</td>
									<td>
										<a @click.prevent="removeSelectedArticle(index)"
										   class="btn btn-icon btn-danger float-right"
										   href="#"
										   title="Törlés">Törlés</a>
									</td>
								</tr>
							</draggable>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- /JOBB TARTALOM -->
	</div>


</template>

<script>
	import Errors from "../../Utils/Errors";
	import Slug from "../../Utils/Slug";
	import draggable from "vuedraggable";

	export default {
		components: {
			draggable
		},
		props: {
			dataArticle: {
				type: Object
			},
			dataIsCreate: {
				type: Boolean,
				required: true
			},
			dataRedirectRoute: {
				type: String,
				required: true
			},
			dataCalculators: {
				type: Array
			},
			dataArticles: {
				type: Object
			},
			dataConnectedArticles: {
				type: Array
			},
			dataMenus: {
				type: Array
			},
		},
		data() {
			return {
				tinyMCEApiKey: this.$tinyMCEApiKey,
				tinyMCEConfig: this.$tinyMCEConfig,
				selectConfig: {
					keyName: 'id',
					valueName: 'title'
				},
				article: this.dataArticle ? this.dataArticle : {
					title: '',
					short_title: '',
					slug: '',
					author: 'Okosan.hu',
					content: '',
					prolog: '',
					status: 'DRAFT',
					calculator_id: 0,
					menu_calculator_id: 0,
					order: 1,
				},
				saveSlug: this.dataArticle ? this.dataArticle.slug : '',
				isCreate: this.dataIsCreate,
				redirectRoute: this.dataRedirectRoute,
				calculators: this.dataCalculators,
				menus: this.dataMenus,
				featuredImage: '',
				selectedTableOfContents: this.dataArticle ? _.toArray(this.dataArticle.selectedTableOfContents) : [],


				articleProlog: this.dataArticle ? this.dataArticle.prolog : '',
				articleContent: this.dataArticle ? this.dataArticle.content : '',

				articles: _.toArray(this.dataArticles),
				selectedArticle: '',
				connectedArticles: this.dataConnectedArticles ? _.toArray(this.dataConnectedArticles) : [],

				slug: new Slug(),
				errors: new Errors(),
			}
		},
		methods: {
			generateTableOfContents: function () {
				let pattern = /<(h[2])>((?:(?!<h\d+\b).)+?)<\/\1>/giu;
				let headingsContent = tinyMCE.get('article-content').getContent().match(pattern);

				this.selectedTableOfContents = [];
				let vm = this;

				_.forEach(headingsContent, function (value) {
					let tContent = value.replace(/<[^>]*>?/gm, '');
					if (tContent) vm.selectedTableOfContents.push(tContent);
				});
			},
			setSlug: function (title) {
				this.saveSlug = this.slug.refreshSlug(title);
			},
			handleFileUpload: function () {
				this.featuredImage = this.$refs.featured_image.files[0];
			},
			saveArticle: function () {
				if (this.selectedTableOfContents.length === 0) {
					this.generateTableOfContents();
				}

				let settings = {headers: {'content-type': 'multipart/form-data'}};

				let formData = new FormData();

				formData.append('featured_image', this.featuredImage);
				this.article.slug = this.saveSlug;
				this.article.prolog = this.articleProlog;
				this.article.content = this.articleContent;
				formData.append('article', JSON.stringify(this.article));
				formData.append('tocs', JSON.stringify(this.selectedTableOfContents));
				formData.append('connectedArticles', JSON.stringify(this.connectedArticles));

				axios.post('/api/articles', formData, settings).then(response => {
					this.$toastr.success('A cikk adatai mentve');
					if (this.isCreate) {
						window.location.replace(this.redirectRoute);
					}
				})
					.catch(e => {
						this.errors.record(e.response.data);
						this.$toastr.error('Cikk mentés hiba', 'Error');
					});
			},
			updateArticle: function () {
				this.generateTableOfContents();
				let settings = {headers: {'content-type': 'multipart/form-data'}};
				let formData = new FormData();

				formData.append('featured_image', this.featuredImage);
				this.article.slug = this.saveSlug;
				this.article.prolog = this.articleProlog;
				this.article.content = this.articleContent;
				formData.append('article', JSON.stringify(this.article));
				formData.append('tocs', JSON.stringify(this.selectedTableOfContents));
				formData.append('connectedArticles', JSON.stringify(this.connectedArticles));

				formData.append('_method', 'PATCH');

				axios.post('/api/articles/' + this.article.id, formData, settings)
					.then(response => {
						this.$toastr.success('A cikk adatai módosítva');
						//window.location.replace(this.redirectRoute);
					})
					.catch(e => {
						this.errors.record(e.response.data);
						this.$toastr.error('Cikk módosítási hiba', 'Error');
					});
			},
			setStatus: function (status) {
				axios.post('/api/articles/' + this.article.id + '/set/' + status)
					.then(response => {
						this.$toastr.success('Sikeres módosítás');
						location.reload();
					})
					.catch(e => {
						this.errors.record(e.response.data);
						this.$toastr.error('Módosítási hiba', 'Error');
					});
			},
			removeTableOfContent: function (index) {
				this.selectedTableOfContents.splice(index, 1);
			},
			addNewConnectedArticle: function () {
				if (this.selectedArticle !== '') {
					let article = this.articles.find(element => element['id'] === this.selectedArticle);
					let articleIndex = this.articles.indexOf(article);

					this.connectedArticles.push(article);
					this.articles.splice(articleIndex, 1);
					this.selectedArticle = '';
				} else {
					this.$toastr.error('Előbb válasszon egy cikket', 'Error');
				}
			},
			removeSelectedArticle: function (index) {
				this.articles.push(this.connectedArticles[index]);
				this.connectedArticles.splice(index, 1);
			},
		}
	}
</script>

<style scoped>

</style>
