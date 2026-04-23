import Plugin from 'src/plugin-system/plugin.class'

export default class TableOfContentsPlugin extends Plugin {
	static options = {
		/**
		 * Specifies the text that is prompted to the user
		 * @type string
		 */
		titleType: 'h4',
	};
	init() {
		const container = document.querySelector('.container-main');
		// Select all title elements based on the titleType specified in options.
		this.titles = container.querySelectorAll(this.options.titleType);
		this.tocNav = document.querySelector('.table-of-contents');
		this.tocList = document.querySelector('.table-of-contents-list');
		
		this.createTableOfContents()
		this.addScrollListener();
	}
	
	createTableOfContents() {
		this.tocList.innerHTML = '';
		// Store a reference to each list item and its corresponding title.
		this.tocMapping = [];
		
		Array.from(this.titles).forEach((title) => {
			const titleId = title.textContent.toLowerCase().replace(/\s+/g, '-');
			title.id = titleId;
			
			const listItem = document.createElement('li');
			const link = document.createElement('a');
			link.href = `#${titleId}`;
			link.textContent = title.textContent;
			
			listItem.appendChild(link);
			this.tocList.appendChild(listItem);
			
			// Store the mapping.
			this.tocMapping.push({ id: titleId, listItem });
		});
	}
	
	addScrollListener() {
		const toc = this.tocNav;
		const parentSection = toc.closest('.cms-section-sidebar-sidebar-content'); // Adjust this if your table of contents is nested deeper.
		window.addEventListener('scroll', () => {
			// ==============================
			// Handle sticky behavior of the table of contents.
			// ==============================
			const tocRect = toc.getBoundingClientRect();
			const parentRect = parentSection.getBoundingClientRect();
			
			// Check if the table of contents should become sticky.
			if (tocRect.top <= 0 && parentRect.bottom >= tocRect.height) {
				toc.classList.add('sticky');
			} else {
				toc.classList.remove('sticky');
			}
			
			if(parentRect.top <= 0 && parentRect.bottom >= tocRect.height) {
				toc.classList.add('sticky');
			} else {
				toc.classList.remove('sticky');
			}
			
			// ==============================
			// Handle Title Active class behavior.
			// ==============================
			let closestTitle = null;
			let closestDistance = Infinity;
			
			for (const mapping of this.tocMapping) {
				const element = document.getElementById(mapping.id);
				const bounding = element.getBoundingClientRect();
				
				// Determine how close the title is to being at the top of the viewport.
				// A negative value means the item is above the viewport.
				const distance = Math.abs(bounding.top);
				
				// Find the title that is closest to the viewport's top but not above it.
				if (bounding.top < window.innerHeight && distance < closestDistance) {
					closestTitle = mapping;
					closestDistance = distance;
				}
			}
			
			// Remove the active class from all list items.
			this.tocMapping.forEach(mapping => mapping.listItem.classList.remove('active'));
			
			// Add the active class to the closest title's list item, if any.
			if (closestTitle) {
				closestTitle.listItem.classList.add('active');
			}
		});
	}
	
}