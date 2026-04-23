import Plugin from 'src/plugin-system/plugin.class';

export default class CategoryFilterPlugin extends Plugin {
	init() {
		this.categoryButtons = new Map();
		this.categoryIntros = new Map();
		this.products = new Map();
		
		this._cacheElements();
		this._registerEvents();
	}
	
	_cacheElements() {
		document.querySelectorAll('.category-filter button').forEach((button) => {
			const categoryId = button.getAttribute('data-category-filter-category');
			this.categoryButtons.set(categoryId, button);
		});
		
		document.querySelectorAll('.category-intro').forEach((intro) => {
			const categoryId = intro.getAttribute('data-category-filter-intro');
			this.categoryIntros.set(categoryId, intro);
		});
		
		document.querySelectorAll('.cms-listing-col').forEach((product) => {
			if(product.getAttribute('data-category-filter-categories')) {
				const productCategoryIds = product.getAttribute('data-category-filter-categories').split(',');
				productCategoryIds.forEach((id) => {
					if (!this.products.has(id)) {
						this.products.set(id, []);
					}
					this.products.get(id).push(product);
				});
			}
		});
	}
	
	_registerEvents() {
		this.el.addEventListener('click', this._onCategoryFilterClick.bind(this));
	}
	
	_onCategoryFilterClick(event) {
		const button = event.target.closest('button');
		if (!button) return;
		
		event.preventDefault();
		
		// Add the active class to the clicked button
		this.categoryButtons.forEach((button) => button.classList.remove('active'));
		button.classList.add('active');
		
		// Get the active category id
		const activeCategoryId = button.getAttribute('data-category-filter-category');
		
		if (activeCategoryId === '') {
			this.products.forEach((products) => {
				products.forEach((product) => {
					product.style.display = '';
				});
			});
			this.categoryIntros.forEach((intro) => {
				intro.style.display = 'none';
			});
			return;
		}
		
		// Filter products
		this.products.forEach((products, id) => {
			const display = id === activeCategoryId ? '' : 'none';
			products.forEach((product) => {
				product.style.display = display;
			});
		});
		
		// Display category intros
		this.categoryIntros.forEach((intro, id) => {
			intro.style.display = id === activeCategoryId ? '' : 'none';
		});
	}
}
