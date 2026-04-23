import Plugin from 'src/plugin-system/plugin.class'

export default class ProductRequestFormModalPlugin extends Plugin {
	static options = {
		productName: '',
		productImage: '',
		customizedProduct: false
	};
	
	init() {
		const requestFormModalLink = document.getElementById('requestFormModalLink')
		requestFormModalLink.addEventListener('click', () => {
			const observer = new MutationObserver((mutationsList, observer) => {
				for(let mutation of mutationsList) {
					if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
						const form = document.querySelector('.modal-body form');
						if(form) {
							this.setProductInfo();
							observer.disconnect();
						}
					}
				}
			});
			
			observer.observe(document.body, { childList: true, subtree: true });
		});
	}
	setProductInfo() {
		const form = document.querySelector('.modal-body form')
		const formProductField = document.getElementById('form-request_form_product-product_information')
		formProductField.hidden = true;
		
		// add div before form for product information
		const productInformation = document.createElement('div')
		productInformation.classList.add('custom-form-product-information')
		
		if(this.options.customizedProduct) {
			// Get product configuration
			let customProductConfig = [];
			const dimensionSliders = document.querySelectorAll('.variant-configurator .variants-dimension-sliders input[type="number"]')
			dimensionSliders.forEach(input => {
				const label = input.previousElementSibling;
				customProductConfig.push(`${label.textContent}: ${input.value}`)
			});
			
			// add image, name and config to product field and to the div
			formProductField.value = this.options.productName + ' (' + customProductConfig.join(', ') + ')';
			productInformation.innerHTML = `<img src="${this.options.productImage}" alt="${this.options.productName}"><div class="custom-form-product-information-name"><span class="custom-form-product-information-name-span">${this.options.productName}</span><span class="custom-form-product-information-config">${customProductConfig.join(', ')}</span></div>`
		} else {
			// add image and name to product field and to the div
			formProductField.value = this.options.productName;
			productInformation.innerHTML = `<img src="${this.options.productImage}" alt="${this.options.productName}"><div class="custom-form-product-information-name">${this.options.productName}</div>`
		}
		form.parentNode.insertBefore(productInformation, form)
	}
}