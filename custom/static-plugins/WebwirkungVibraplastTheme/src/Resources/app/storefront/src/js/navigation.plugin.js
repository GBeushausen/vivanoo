import Plugin from 'src/plugin-system/plugin.class'
import DomAccess from 'src/helper/dom-access.helper'
import ViewportDetection from 'src/helper/viewport-detection.helper'

export default class NavigationPlugin extends Plugin {
	init() {
		this.mainNavigation = this.el
		this.navigationLi = DomAccess.querySelectorAll(this.el, '.main-navigation-link')
		
		this.subscribeViewportEvents()
		if(this.pluginActive()) this.initializePlugin()
	}
	subscribeViewportEvents() {
		document.$emitter.subscribe('Viewport/hasChanged', this.update, {scope: this})
	}
	update() {
		if(this.pluginActive()) {
			if(this.initialized) return
			this.initializePlugin()
		}else {
			if(!this.initialized) return
			
			this.destroy()
		}
	}
	
	initializePlugin() {
		this.setSubnavPos()
		this.initialized = true
	}
	destroy() {
		this.initialized = false
	}
	pluginActive() {
		return !['XS', 'SM', 'MD'].includes(ViewportDetection.getCurrentViewport());
	}
	setSubnavPos() {
		this.navigationLi.forEach((elnav, index, array) => {
			setTimeout(() => {
				if(elnav.dataset.flyoutMenuTrigger) {
					const subNavigation = DomAccess.querySelector(this.el, '[data-flyout-menu-id="'+ elnav.dataset.flyoutMenuTrigger + '"]')
					// if subnavigation has no div with class "nav-item-image" inside it add offset
					if(subNavigation.querySelector('.nav-item-image') === null || subNavigation.querySelector('.nav-item-image--icon')) {
						const offsetLeft = elnav.offsetLeft
						subNavigation.classList.add('subnav-no-image')
						subNavigation.style.left = offsetLeft + 'px';
					}
					
				}
			}, "1000")
		});
	}
}
