import Plugin from 'src/plugin-system/plugin.class'
import DomAccess from 'src/helper/dom-access.helper'
export default class UploadFieldPlugin extends Plugin {
  init() {
      this.fileInput = DomAccess.querySelector(this.el, '#upload-file');
      this.deleteBtn = DomAccess.querySelector(this.el, '#delete-file');
      if (this.deleteBtn && this.fileInput) {
        this._registerEvents();
      }
  }
  
  _registerEvents() {
    this.deleteBtn.addEventListener('click', this._onDeleteFile.bind(this));
  }
  
  _onDeleteFile(event) {
    event.preventDefault();
    this.fileInput.value = '';
  }
  
}