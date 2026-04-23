import Plugin from 'src/plugin-system/plugin.class';
import ViewportDetection from 'src/helper/viewport-detection.helper'

export default class HistoryPlugin extends Plugin {
	init() {
		
		// Create a MutationObserver to watch for changes in the class attribute of the thumbnail list items
		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.attributeName === 'class') {
					this.centerActiveThumbnail();
				}
			});
		});

		// Start observing the thumbnail container for changes in the class attribute of its child elements
		const thumbnailsContainer = document.querySelector('.year-nav');
		if (thumbnailsContainer) {
			const config = { attributes: true, childList: false, subtree: true, attributeFilter: ['class'] };
			observer.observe(thumbnailsContainer, config);
			
			// Initial call to ensure the active thumbnail is centered when the page loads
			window.addEventListener('load', this.centerActiveThumbnail);
		}
	}
	
	centerActiveThumbnail() {
		const thumbnailsContainer = document.querySelector('.year-nav');
		const activeThumbnail = document.querySelector('.year-nav .tns-nav-active');
		
		if (thumbnailsContainer && activeThumbnail) {
			if (['XS', 'SM', 'MD'].includes(ViewportDetection.getCurrentViewport())) {
				// Mobile: adjust horizontally
				const containerWidth = thumbnailsContainer.offsetWidth;
				const activeThumbnailOffsetLeft = activeThumbnail.offsetLeft;
				const activeThumbnailWidth = activeThumbnail.offsetWidth;
				
				const scrollTo = activeThumbnailOffsetLeft + (activeThumbnailWidth / 2) - (containerWidth / 2);
				thumbnailsContainer.scrollLeft = scrollTo;
			} else {
				// Desktop: adjust vertically as before
				const containerHeight = thumbnailsContainer.offsetHeight;
				const activeThumbnailOffsetTop = activeThumbnail.offsetTop;
				const activeThumbnailHeight = activeThumbnail.offsetHeight;
				
				const scrollTo = activeThumbnailOffsetTop + (activeThumbnailHeight / 2) - (containerHeight / 2);
				thumbnailsContainer.scrollTop = scrollTo;
			}
		}
	}
}
