import Plugin from 'src/plugin-system/plugin.class';

export default class MultiStepForm extends Plugin {
	static options = {
		/**
		 * Specifies the text that is prompted to the user
		 * @type string
		 */
		currentTab: 0,
		tabLength: 0,
		lastTab: 0,
		validate: false,
	};
	init() {
		const submitButton = document.querySelector('#confirmFormSubmit')
		const submitButton2 = document.querySelector('.thank-you button')
		document.querySelector("#nextBtn").addEventListener("click",  this.nextStep.bind(this))
		document.querySelector("#prevBtn").addEventListener("click",  this.prevStep.bind(this))

		this.options.tabLength = document.getElementsByClassName("tab").length;
		
		const tabLength = localStorage.getItem('tabLength');

		this.options.currentTab = 0;
		let lastTab = localStorage.getItem('lastTab');
		// this.showTab(0);
		if(lastTab) {
			if(lastTab < 0) lastTab = 0;
			// If number of tabs changes set step back to 0
			if (Number(tabLength) !== this.options.tabLength) {
				this.options.currentTab = 0
				this.showTab(0);
			} else {
				this.options.currentTab = Number(lastTab)
				this.showTab(lastTab);
			}
		} else {
			this.showTab(0);
		}
		
		if(submitButton) {
			submitButton.addEventListener("click", this.removeLastStep)
		}
		if(submitButton2) {
			submitButton2.addEventListener("click", this.removeLastStep)
		}
		
		document.querySelector('#confirmFormSubmit').addEventListener('click', function() {
			document.querySelector('.checkout-confirm-tos-checkbox').classList.add('is-validated');
		})
	}
	
	showTab(number) {
		localStorage.setItem('tabLength', this.options.tabLength)
		const tab = document.getElementsByClassName("tab");
		tab[number].style.display = "block";

		if (number === 0) {
			document.getElementById("prevBtn").style.display = "none";
		} else {
			document.getElementById("prevBtn").style.display = "inline";
		}
		if (Number(number) === (tab.length - 1)) {
			document.getElementById("nextBtn").style.display = "none";
		} else {
			document.getElementById("nextBtn").style.display = "inline";
		}
		// Create span for step navigation element based on number of steps
		const stepNavigation = document.getElementById('stepNavigation')
		if (stepNavigation.childNodes.length === 0) {
			const arr = [].slice.call(tab);
			arr.forEach((tab, index) => {
				const tabTitle = tab.getAttribute('data-tab-title');
				this.handleSteps(index + 1, tabTitle);
			});
		}
		
		// Show checkout button
		if (Number(number) === 3) {
			document.querySelector('.checkout-aside-action').classList.add('show')
		} else {
			document.querySelector('.checkout-aside-action').classList.remove('show')
		}
		
		this.fixStepIndicator(number)
	}
	
	handleSteps(index, tabTitle) {
		const span = document.createElement("span");
		span.setAttribute("class", "step");
		
		// Create a separate span for the index
		const indexSpan = document.createElement("span");
		// Set the class for the index span
		indexSpan.setAttribute("class", "step-number");
		indexSpan.textContent = `${index}`;
		span.appendChild(indexSpan);
		
		const indexSpanTitle = document.createElement("span");
		indexSpanTitle.setAttribute("class", "step-title");
		indexSpanTitle.textContent = tabTitle;
		// Set the text content with tabTitle
		span.appendChild(indexSpanTitle);
		
		// Append the main span to the stepNavigation
		document.getElementById('stepNavigation').appendChild(span);
	}
	
	fixStepIndicator(n) {
		let x = document.getElementsByClassName("step");
		x[n].classList.add("active");
	}
	
	nextPrevStep(num) {
		const tab = document.getElementsByClassName("tab");
		tab[this.options.currentTab].style.display = "none";
		this.options.currentTab = this.options.currentTab + num;
		
		if (this.options.currentTab >= tab.length) {
			document.getElementById("regForm").submit();
			return false;
		}
		localStorage.setItem('lastTab', this.options.currentTab)
		this.showTab(this.options.currentTab);
	}
	
	nextStep() {
		const tab = document.getElementsByClassName("tab");
		const inputField = tab[this.options.currentTab].getElementsByTagName('input')
		window.scrollTo(0, 0);
		
		if(inputField.length > 0) {
			if (inputField[0].type === 'date') {
				if (inputField[0].value === "") {
					alert("Bitte geben Sie ihr Geburtsdatum an.");
				} else {
					this.nextPrevStep(+1)
				}
			} else {
				this.nextPrevStep(+1)
			}
		} else {
			this.nextPrevStep(+1)
		}
	}
	
	checkCheckboxes(checkboxes) {
		const lastCheckbox = checkboxes[checkboxes.length - 1];
		checkboxes.forEach(checkbox => {
			checkbox.addEventListener('change', () => {
				if (lastCheckbox.checked && checkbox !== lastCheckbox) {
					lastCheckbox.checked = false;
				} else if (!lastCheckbox.checked) {
					// Ensure at least one checkbox is checked
					let isChecked = false;
					checkboxes.forEach(cb => {
						if (cb.checked) {
							isChecked = true;
						}
					});
					if (!isChecked) {
						lastCheckbox.checked = true;
					}
				}
			});
		});
		
		lastCheckbox.addEventListener('change', () => {
			if (lastCheckbox.checked) {
				checkboxes.forEach(checkbox => {
					checkbox.checked = false;
				});
				lastCheckbox.checked = true;
			}
		});
	}
	
	
	prevStep() {
		// Fix navigation
		let xAct = document.getElementsByClassName("step");
		xAct[this.options.currentTab].classList.remove('active')
		this.nextPrevStep(-1)
	}
	
	removeLastStep() {
		localStorage.removeItem("lastTab");
	}
}