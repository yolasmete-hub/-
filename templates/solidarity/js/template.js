/**
 * Solidarity template behaviours: mobile nav, submenu accordion,
 * sticky header shadow, marquee duplication, back-to-top.
 */
(function () {
    'use strict';

    // --- Mobile navigation toggle -------------------------------------
    var toggle = document.querySelector('.sol-nav-toggle');
    var nav = document.getElementById('sol-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        document.addEventListener('keyup', function (event) {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }

    // --- Submenu accordion (mobile) -----------------------------------
    if (nav) {
        var parents = nav.querySelectorAll('li');

        parents.forEach(function (li) {
            var submenu = li.querySelector(':scope > ul');

            if (!submenu) {
                return;
            }

            var link = li.querySelector(':scope > a, :scope > span');
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'sol-submenu-toggle';
            button.setAttribute('aria-expanded', 'false');
            button.innerHTML = '<span aria-hidden="true">&#9662;</span>';

            button.addEventListener('click', function (event) {
                event.preventDefault();
                var open = li.classList.toggle('is-open');
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            if (link && link.parentNode) {
                link.parentNode.insertBefore(button, link.nextSibling);
            } else {
                li.insertBefore(button, submenu);
            }
        });
    }

    // --- Sticky header shadow -----------------------------------------
    var header = document.getElementById('sol-header');

    if (header) {
        var onScroll = function () {
            header.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // --- Marquee: duplicate content for a seamless loop ---------------
    var ticker = document.querySelector('[data-sol-ticker] .sol-ticker__track');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (ticker && !reduceMotion) {
        var originals = Array.prototype.slice.call(ticker.children);

        if (originals.length) {
            var safety = 0;

            while (ticker.scrollWidth < window.innerWidth * 2 && safety < 10) {
                originals.forEach(function (node) {
                    var clone = node.cloneNode(true);
                    clone.setAttribute('aria-hidden', 'true');
                    ticker.appendChild(clone);
                });
                safety += 1;
            }
        }
    }

    // --- Back to top ---------------------------------------------------
    var backToTop = document.querySelector('[data-sol-back-to-top]');

    if (backToTop) {
        var onScrollTop = function () {
            backToTop.hidden = window.scrollY < 600;
        };
        window.addEventListener('scroll', onScrollTop, { passive: true });
        onScrollTop();

        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
        });
    }
})();
