/* Junk Removal Team Dubai — frontend behaviour (vanilla JS, no libraries) */
(function () {
  'use strict';

  /* Mobile navigation */
  var toggle = document.querySelector('[data-nav-toggle]');
  var nav = document.getElementById('primary-nav');

  function setNav(open) {
    if (!toggle || !nav) return;
    toggle.setAttribute('aria-expanded', String(open));
    nav.dataset.open = String(open);
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      setNav(toggle.getAttribute('aria-expanded') !== 'true');
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a') && window.matchMedia('(max-width: 1023px)').matches) setNav(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { setNav(false); toggle.focus(); }
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1024) setNav(false);
    });
  }

  /* Header shadow on scroll */
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-stuck', window.scrollY > 4); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* Quote form — front-end only for now.
     The PHP backend (POST /en/get-a-quote/) will replace this handler. */
  var form = document.querySelector('[data-quote-form]');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      ['name', 'phone'].forEach(function (n) {
        var field = form.elements[n];
        if (field && !field.value.trim()) { ok = false; field.setAttribute('aria-invalid', 'true'); }
        else if (field) { field.removeAttribute('aria-invalid'); }
      });
      var box = form.querySelector('[data-form-success]');
      if (!ok) { if (box) { box.hidden = true; } return; }
      if (box) { box.hidden = false; }
      form.reset();
    });
  }

  /* Conversion event hooks — ready for GA4/GTM once tracking IDs exist */
  document.querySelectorAll('[data-track]').forEach(function (el) {
    el.addEventListener('click', function () {
      var name = el.getAttribute('data-track');
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({ event: name });
    });
  });
})();
