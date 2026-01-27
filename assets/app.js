import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

window.onload = () => {
    let open = document.querySelector(".menu-small-btn")
    let close = document.querySelector(".close")
    let menuul = document.querySelector(".menu-small-ul")

    open.addEventListener('click', () => {
        if(menuul.className = "menu-small-ul"){
            menuul.classList.add("open")
        }
    });
    close.addEventListener('click', () => {
        if(menuul.className = "menu-small-ul open"){
            menuul.classList.remove("open")
        }
    });
}
