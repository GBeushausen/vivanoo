import template from './webwirkung-faq-list.html.twig';
import './webwirkung-faq-list.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('webwirkung-faq-list', {
	template,
	
	inject: [
		'repositoryFactory'
	],
	
	data() {
		return {
			repository: null,
			faq: null
		};
	},
	
	metaInfo() {
		return {
			title: this.$createTitle()
		};
	},
	
	computed: {
		columns() {
			return [
				{
					property: 'question',
					dataIndex: 'question',
					label: this.$t('faq.general.questionField'),
					routerLink: 'webwirkung.faq.detail',
					inlineEdit: 'string',
					allowResize: true,
					primary: true
				},{
					property: 'variant',
					dataIndex: 'variant',
					label: this.$t('faq.general.variantField'),
					routerLink: 'webwirkung.faq.detail',
					inlineEdit: 'string',
					allowResize: true,
					primary: true
				}
			];
		}
	},
	
	created() {
		this.repository = this.repositoryFactory.create('ww_faq');
		this.getFaqs();
		
	},
	methods: {

		getFaqs() {
			this.repository
				.search(new Criteria(), Shopware.Context.api)
				.then((result) => {
					this.faq = result;
				});
		},
		
		changeLanguageSelection() {
			return this.getFaqs();
		}
	}
});
