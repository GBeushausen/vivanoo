import template from './webwirkung-glossary-list.html.twig';
import './webwirkung-glossary-list.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('webwirkung-glossary-list', {
	template,
	
	inject: [
		'repositoryFactory'
	],
	
	data() {
		return {
			repository: null,
			glossary: null
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
					property: 'name',
					dataIndex: 'name',
					label: this.$t('webwirkung.glossary.general.nameField'),
					routerLink: 'webwirkung.glossary.detail',
					inlineEdit: 'string',
					allowResize: true,
					primary: true,
					sortable: true
				}, {
					property: 'active',
					dataIndex: 'active',
					label: this.$t('webwirkung.glossary.general.activeField'),
					routerLink: 'webwirkung.glossary.detail',
					inlineEdit: 'boolean',
					allowResize: true,
				}
			];
		}
	},
	
	created() {
		this.repository = this.repositoryFactory.create('ww_glossary');
		
		this.getGlossaries();
		
	},
	
	methods: {
		
		getGlossaries() {
			this.repository
				.search(new Criteria(), Shopware.Context.api)
				.then((result) => {
					this.glossary = result;
				});
		},
		
		changeLanguageSelection() {
			return this.getGlossaries();
		}
	}
});
