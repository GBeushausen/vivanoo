import template from './webwirkung-faq-detail.html.twig';

const { Component, Mixin, State } = Shopware;
const { StateDeprecated } = Shopware;

Component.register('webwirkung-faq-detail', {
	template,
	
	inject: [
		'repositoryFactory'
	],

	mixins: [
		Mixin.getByName('notification')
	],

	computed: {
		identifier() {
			return this.placeholder(this.faq, 'question');
		},

		faqIsLoading() {
			return this.isLoading || this.faq == null;
		},

		faqRepository() {
			return this.repositoryFactory.create('ww_faq');
		},

	},

	metaInfo() {
		return {
			title: this.$createTitle()
		};
	},

	data() {
		return {
			bundle: null,
			faq: null,
			isLoading: false,
			processSuccess: false,
			changedToDefaultLanguage: false,
			repository: null,
			hasError: false
		};
	},
	created() {
		this.repository = this.repositoryFactory.create('ww_faq');
		this.getFaq();
	},

	methods: {
		
		getFaq() {
			this.repository
				.get(this.$route.params.id, Shopware.Context.api)
				.then((entity) => {
					this.faq = entity;
				});
		},
		
		saveOnLanguageChange() {
			return this.onClickSave();
		},
		
		changeLanguageSelection(entityCollection) {
			this.faq.languageId = entityCollection;
			this.changedToDefaultLanguage = false;
			this.getFaq();
		},

		onClickSave() {
			if (!this.faq.faqCategory || this.faq.faqCategory === 0) {
				this.createNotificationError({
					message: 'Bitte Kategorie ergänzen'
				});
				
				return;
			}
			if (!this.faq.question) {
				this.hasError = true;
			}
			
			this.isLoading = true;

			this.repository
				.save(this.faq, Shopware.Context.api)
				.then(() => {
					this.getFaq();
					this.isLoading = false;
					this.processSuccess = true;
				}).catch((exception) => {
				this.isLoading = false;
				this.createNotificationError({
					title: this.$t('faq.detail.errorTitle'),
					message: exception
				});
			});
		},
		
		saveFinish() {
			this.processSuccess = false;
		}
	}
});
