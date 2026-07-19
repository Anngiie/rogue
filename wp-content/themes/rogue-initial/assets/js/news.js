/* OceanWP Child — Viberi: custom cursor + UX enhancements */
(function () {
	'use strict';

	// ---------- Smooth scroll for anchor links ----------
	document.addEventListener('click', function (e) {
		var a = e.target.closest('a[href^="#"]');
		if (!a) return;
		var id = a.getAttribute('href');
		if (!id || id === '#') return;
		var el = document.querySelector(id);
		if (el) {
			e.preventDefault();
			el.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	});

	// ---------- Custom cursor ----------
	var cursor = document.querySelector('.vk-cursor');
	var follower = document.querySelector('.vk-cursor-follower');

	// Disable on touch / small screens / reduced motion.
	var isTouch = window.matchMedia('(hover: none)').matches ||
		          window.matchMedia('(pointer: coarse)').matches ||
		          window.innerWidth < 768;
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (cursor && follower && !isTouch && !reducedMotion) {
		document.body.classList.add('vk-cursor-enabled');

		var mouseX = 0, mouseY = 0;
		var cursorX = 0, cursorY = 0;
		var followerX = 0, followerY = 0;
		var rafId = null;
		var isActive = false;

		function onMove(e) {
			mouseX = e.clientX;
			mouseY = e.clientY;
			if (!isActive) {
				isActive = true;
				animate();
			}
		}

		function animate() {
			cursorX += (mouseX - cursorX) * 0.2;
			cursorY += (mouseY - cursorY) * 0.2;
			followerX += (mouseX - followerX) * 0.1;
			followerY += (mouseY - followerY) * 0.1;

			cursor.style.left = cursorX + 'px';
			cursor.style.top = cursorY + 'px';
			follower.style.left = followerX + 'px';
			follower.style.top = followerY + 'px';

			rafId = requestAnimationFrame(animate);
		}

		document.addEventListener('mousemove', onMove, { passive: true });

		// Hover expansion.
		var hoverables = 'a, button, .vk-card, .vk-cat, .work-card, input, textarea, select, .vk-btn';
		document.addEventListener('mouseover', function (e) {
			if (e.target.closest(hoverables)) {
				cursor.classList.add('hover');
				follower.classList.add('hover');
			}
		});
		document.addEventListener('mouseout', function (e) {
			if (e.target.closest(hoverables)) {
				cursor.classList.remove('hover');
				follower.classList.remove('hover');
			}
		});

		// Hide when leaving window.
		document.addEventListener('mouseleave', function () {
			cursor.style.opacity = '0';
			follower.style.opacity = '0';
		});
		document.addEventListener('mouseenter', function () {
			cursor.style.opacity = '1';
			follower.style.opacity = '1';
		});
	} else if (cursor && follower) {
		cursor.style.display = 'none';
		follower.style.display = 'none';
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
