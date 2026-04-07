import template from './webwirkung-glossary-detail.html.twig';

const { Component, Mixin, State } = Shopware;
const { StateDeprecated } = Shopware;

Component.register('webwirkung-glossary-detail', {
	template,
	
	inject: [
		'repositoryFactory'
	],

	mixins: [
		Mixin.getByName('notification')
	],

	computed: {
		identifier() {
			return this.placeholder(this.glossary, 'name');
		},

		glossaryIsLoading() {
			return this.isLoading || this.glossary == null;
		},

		glossaryRepository() {
			return this.repositoryFactory.create('ww_glossary');
		},

	},

	metaInfo() {
		return {
			title: this.$createTitle()
		};
	},

	data() {
		return {
			glossary: null,
			isLoading: false,
			processSuccess: false,
			repository: null,
			changedToDefaultLanguage: false,
			hasError: false
		};
	},
	created() {
		this.repository = this.repositoryFactory.create('ww_glossary');
		this.getGlossary();
	},

	methods: {
		
		getGlossary() {
			this.repository
				.get(this.$route.params.id, Shopware.Context.api)
				.then((entity) => {
					this.glossary = entity;
				});
		},
		
		saveOnLanguageChange() {
			return this.onClickSave();
		},
		
		changeLanguageSelection(entityCollection) {
			this.glossary.languageId = entityCollection;
			this.changedToDefaultLanguage = false;
			this.getGlossary();
		},

		onClickSave() {
			this.isLoading = true;
			if (!this.glossary.name) {
				this.hasError = true;
			}
			this.repository
				.save(this.glossary, Shopware.Context.api)
				.then(() => {
					this.getGlossary();
					this.isLoading = false;
					this.processSuccess = true;
				}).catch((exception) => {
				this.isLoading = false;
				this.createNotificationError({
					title: this.$t('webwirkung.glossary.detail.errorTitle'),
					message: exception
				});
			});
		},
		
		saveFinish() {
			this.processSuccess = false;
		}
	}
});
