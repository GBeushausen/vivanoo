import template from './webwirkung-faq-category-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('webwirkung-faq-category-list', {
	template,
	
	inject: [
		'repositoryFactory'
	],
	
	data() {
		return {
			repositoryCategory: null,
			faqCategory: null
		};
	},
	
	metaInfo() {
		return {
			title: this.$createTitle()
		};
	},
	
	computed: {
		columns() {
			return [{
				property: 'name',
				dataIndex: 'name',
				label: this.$tc('faq-category.general.nameField'),
				routerLink: 'webwirkung.faq.category.detail',
				inlineEdit: 'string',
				allowResize: true,
				primary: true
			}];
		}
	},
	
	created() {
		this.repositoryCategory = this.repositoryFactory.create('ww_faq_category');
		this.getFaqCategories()
	},
	
	methods:{
		getFaqCategories() {
			this.repositoryCategory
				.search(new Criteria(), Shopware.Context.api)
				.then((result) => {
					this.faqCategory = result;
				});
		},
		
		changeLanguageSelection() {
			return this.getFaqCategories();
		}
	}
});
