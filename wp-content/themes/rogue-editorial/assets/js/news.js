/* OceanWP Child — Viberi: UX enhancements (smooth scroll, reveal, header state) */
(function () {
	'use strict';

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// ---------- Smooth scroll for anchor links ----------
	document.addEventListener('click', function (e) {
		var a = e.target.closest('a[href^="#"]');
		if (!a) return;
		var id = a.getAttribute('href');
		if (!id || id === '#') return;
		var el = document.querySelector(id);
		if (el) {
			e.preventDefault();
			el.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
		}
	});

	// ---------- Header shadow once scrolled ----------
	var header = document.getElementById('site-header');
	if (header) {
		var onScroll = function () {
			header.classList.toggle('vk-header-scrolled', window.scrollY > 8);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	// ---------- Scroll reveal ----------
	var revealSelector = '.vk-section-head, .vk-card, .vk-cat, .vk-hero__content, .vk-hero__thumb, .vk-archive-hero__inner, .vk-single-widget';

	if (!reducedMotion && 'IntersectionObserver' in window) {
		var targets = document.querySelectorAll(revealSelector);
		targets.forEach(function (el) {
			el.classList.add('vk-reveal');
		});

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

		targets.forEach(function (el) {
			observer.observe(el);
		});
	}

	// ---------- Navbar search close button ----------
	var searchClose = document.querySelector('.vk-search-dropdown__close');
	var searchDropdown = document.getElementById('searchform-dropdown');
	var searchToggle = document.querySelector('.search-dropdown-toggle');
	if (searchClose && searchDropdown && searchToggle) {
		searchClose.addEventListener('click', function (e) {
			e.preventDefault();
			searchDropdown.classList.remove('show');
			searchToggle.setAttribute('aria-expanded', 'false');
		});
	}

	// ---------- Mobile nav toggle helper ----------
	var mobileToggle = document.querySelector('.vk-mobile-nav-toggle, #mobile-dropdown ul li a.menu-link, .mobile-menu-toggle');
	if (mobileToggle) {
		mobileToggle.setAttribute('aria-label', 'Uključi/isključi navigaciju');
	}
})();
