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
		// Mega-flyout layout always spans the full container — no per-trigger offset.
	}
}
