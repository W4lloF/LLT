import './stimulus_bootstrap.js';
import './styles/app.css';

document.addEventListener('DOMContentLoaded', () => {
    const open = document.querySelector(".menu-small-btn");
    const close = document.querySelector(".close");
    const menuul = document.querySelector(".menu-small-ul");
    const wrapper = document.querySelector(".wrapper-div");
    const left = document.querySelector(".leftarrow");
    const right = document.querySelector(".rightarrow");
    const cards = document.querySelectorAll(".card");

    if (open && menuul) {
        open.addEventListener('click', () => {
            menuul.classList.add("open");
        });
    }

    if (close && menuul) {
        close.addEventListener('click', () => {
            menuul.classList.remove("open");
        });
    }

    if (wrapper && left && right && cards.length > 0) {
        let i = 0;
        let y = getStep();

        function getStep() {
            return window.innerWidth >= 1200 ? 35 : 60;
        }

        function move() {
            wrapper.style.transform = `translateX(${y}vw)`;
        }

        right.addEventListener("click", () => {
            if (i < cards.length - 1) {
                i++;
                y -= getStep();
                move();
            }
        });

        left.addEventListener("click", () => {
            if (i > 0) {
                i--;
                y += getStep();
                move();
            }
        });

        move();
    }
});
