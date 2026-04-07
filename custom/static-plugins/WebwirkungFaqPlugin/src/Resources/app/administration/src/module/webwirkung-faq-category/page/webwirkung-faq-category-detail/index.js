import template from './webwirkung-faq-category-detail.html.twig';
const { Component, Mixin, State } = Shopware;
const { StateDeprecated } = Shopware;

Component.register('webwirkung-faq-category-detail', {
	template,
	
	inject: [
		'repositoryFactory'
	],

	mixins: [
		Mixin.getByName('notification')
	],
	
	data() {
		return {
			faqCategory: null,
			isLoading: false,
			processSuccess: false,
			repositoryCategory: null,
			changedToDefaultLanguage: false,
			hasError: false,
		};
	},
	
	metaInfo() {
		return {
			title: this.$createTitle()
		};
	},
	
	computed: {
		identifier() {
			return this.placeholder(this.faqCategory, 'name');
		},

		faqCategoryIsLoading() {
			return this.isLoading || this.faqCategory == null;
		},

		faqCategoryRepository() {
			return this.repositoryFactory.create('ww_faq_category');
		}

	},
	
	created() {
		this.repositoryCategory = this.repositoryFactory.create('ww_faq_category');
		this.getFaqCategory();
	},

	methods: {

		getFaqCategory() {
			this.repositoryCategory
				.get(this.$route.params.id, Shopware.Context.api)
				.then((entity) => {
					this.faqCategory = entity;
				});
		},
		saveOnLanguageChange() {
			return this.onClickSave();
		},
		
		changeLanguageSelection(entityCollection) {
			this.faqCategory.languageId = entityCollection;
			this.changedToDefaultLanguage = false;
			this.getFaqCategory();
		},

		onClickSave() {
			this.isLoading = true;
			
			if (!this.faqCategory.name) {
				this.hasError = true;
			}

			this.repositoryCategory
				.save(this.faqCategory, Shopware.Context.api)
				.then(() => {
					this.getFaqCategory();
					this.isLoading = false;
					this.processSuccess = true;
				}).catch((exception) => {
				this.isLoading = false;
				this.createNotificationError({
					title: this.$t('faq-category.detail.errorTitle'),
					message: exception
				});
			});
		},

		saveFinish() {
			this.processSuccess = false;
		},
	}
});
