import Plugin from 'src/plugin-system/plugin.class';

export default class GlossaryPlugin extends Plugin {
	init() {
		this.filterGlossary();
	}
	
	filterGlossary() {
		const filters = document.querySelectorAll('[data-filter-by]');
		const terms = document.querySelectorAll('[data-filter-letter]');
		
		filters.forEach(filter => {
			const filterValue = filter.getAttribute('data-filter-by');
			const matchingTerms = Array.from(terms).filter(term => term.getAttribute('data-filter-letter') === filterValue);
			if (matchingTerms.length === 0) {
				filter.classList.add('disabled'); // Add a class to visually indicate the disabled state
				filter.removeEventListener('click', this.handleFilterClick); // Remove event listener
			}
		});
		
		this.handleFilterClick = this.handleFilterClick.bind(this);
		filters.forEach(filter => {
			filter.addEventListener('click', this.handleFilterClick);
		});
	}
	
	handleFilterClick(event) {
		event.preventDefault();
		const filterEl = event.target;
		const filterValue = filterEl.getAttribute('data-filter-by');
		// Remove active class from all other filters
		const filters = document.querySelectorAll('[data-filter-by]');
		filters.forEach(filter => {
			if (filter !== filterEl) {
				filter.classList.remove('active');
			}
		});
		filterEl.classList.add('active');
		
		const terms = document.querySelectorAll('[data-filter-letter]');
		
		if (filterValue === 'all') {
			terms.forEach(term => {
				term.classList.remove('d-none');
			});
			return;
		}
		
		terms.forEach(term => {
			const termLetter = term.getAttribute('data-filter-letter');
			if (termLetter === filterValue) {
				term.classList.remove('d-none');
			} else {
				term.classList.add('d-none');
			}
		});
	}
}