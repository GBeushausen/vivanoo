import Plugin from 'src/plugin-system/plugin.class';

export default class GlossaryPlugin extends Plugin {
	static options = {
		apiKey: 'no api key',
		baseUrl: window.location.origin,
		salesChannelBaseURL: ''
	};
	data() {
		return {
			glossary: [],
			appearingGlossaryTerms: []
		}
	}
	init() {
		this.fetchGlossary();
	}
	
	async fetchGlossary() {
		const url = this.options.baseUrl + this.options.salesChannelBaseURL + '/glossary';
		
		const options = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				Accept: 'application/json',
				'sw-access-key': this.options.apiKey,
			},
		}
		
		try {
			const response = await fetch(url, options);
			const data = await response.json().then((data) => {this.handleGlossaryNames(data)});
		} catch (error) {
			console.error(error);
		}
	}
	async handleGlossaryNames(response) {
		const glossaryElements = response.elements;
		let glossaryNames = [];
		Object.values(glossaryElements).forEach(element => {
			glossaryNames.push(element.name);
		});
		this.glossary = glossaryNames;

		await this.checkForGlossaryTerm()
		this.renderGlossary();
	}
	
	checkForGlossaryTerm() {
		const textToCheck = document.querySelector('[data-glossary-text]').textContent
		let appearingWords = [];
		this.glossary.forEach(glossaryTerm => {
			if (textToCheck.includes(glossaryTerm)) {
				appearingWords.push(glossaryTerm);
			}
		});
		this.appearingGlossaryTerms = appearingWords;
	}
	
	renderGlossary() {
		const glossaryContainer = document.querySelector('[data-glossary-terms]');
		this.appearingGlossaryTerms.forEach(term => {
			const termElement = document.createElement('a');
			// Todo: Check if we use a link or a modal for this, depending on decision make the link dynamic for languages
			termElement.href = this.options.baseUrl + this.options.salesChannelBaseURL + '/glossar/#' + term.toLowerCase();
			termElement.classList.add('glossary__term');
			termElement.textContent = term;
			glossaryContainer.appendChild(termElement);
		});
		if(this.appearingGlossaryTerms.length <= 0) glossaryContainer.parentNode.style.display = 'none';
	}
	
}