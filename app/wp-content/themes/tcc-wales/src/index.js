 import '../sass/app.scss';

import * as Popper from "@popperjs/core"
// import * as bootstrap from "bootstrap";

// window.addEventListener('DOMContentLoaded', function() {
//     menuToggleInit()
// });
//
// const toastEl = document.getElementById('msg-send-success')
// const toastList = new bootstrap.Toast(toastEl)
//
//
//
// class MenuToggle {
//
//     constructor(){
//         this.body = document.body
//         this.navBar = document.getElementById('#navbarCollapse')
//         this.collapseElementList = document.querySelectorAll('.collapse')
//         this._bindEventListeners();
//     }
// }
//
// MenuToggle.prototype._bindEventListeners = function() {
//     this._onBtnClick = this._handleBtnToggle.bind(this)
//
//
//     this.toggleBtn = document.getElementById('navbar-toggle-icon')
//     this.toggleBtn.addEventListener('click', this._onBtnClick);
//
// }
//
// MenuToggle.prototype._handleBtnToggle = function( event ) {
//     this.toggleBtn.parentElement.classList.toggle("is-active")
//     this.collapseList = [...this.collapseElementList].map( collapseEl => new bootstrap.Collapse(collapseEl) )
// }
//
// function menuToggleInit(){
//     window.menuToggle = new MenuToggle()
// }