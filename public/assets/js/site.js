/* Junk Removal Team Dubai — frontend behaviour (vanilla JS, no libraries) */
(function () {
  'use strict';

  var lang = document.documentElement.lang === 'ar' ? 'ar' : 'en';
  var TEXT = {
    en: {
      sending: 'Sending…',
      failed: 'Something went wrong. Please call or WhatsApp us instead.',
      sent: 'Thank you. We have your details and will get back to you shortly.'
    },
    ar: {
      sending: 'جارٍ الإرسال…',
      failed: 'حدث خطأ. يرجى الاتصال بنا أو مراسلتنا عبر واتساب.',
      sent: 'شكرًا لك. لقد استلمنا بياناتك وسنعاود التواصل معك قريبًا.'
    }
  }[lang];

  function track(name) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: name });
  }

  /* ---------- Mobile navigation ---------- */
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
      var link = e.target.closest('a');
      if (!link || !window.matchMedia('(max-width: 1023px)').matches) return;

      // First tap on a parent item opens its submenu; a second tap follows the link
      var parent = link.parentNode;
      if (parent && parent.classList.contains('has-sub')) {
        var sub = parent.querySelector('.submenu');
        if (sub && sub.dataset.open !== 'true') {
          e.preventDefault();
          sub.dataset.open = 'true';
          return;
        }
      }
      setNav(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { setNav(false); toggle.focus(); }
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 1024) setNav(false);
    });
  }

  /* ---------- Header shadow on scroll ---------- */
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-stuck', window.scrollY > 4); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Campaign attribution ----------
     Remembers where the visitor first came from (or the latest ad click) for
     90 days, so a lead submitted on a later page still carries its source. */
  var ATTR_KEY = 'jrtd_attr';
  var ATTR_FIELDS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid'];

  function loadAttribution() {
    var saved = null;
    try {
      saved = JSON.parse(localStorage.getItem(ATTR_KEY) || 'null');
      if (saved && (!saved.t || Date.now() - saved.t > 90 * 864e5)) saved = null;
    } catch (e) { saved = null; }

    var params = new URLSearchParams(window.location.search);
    var fromUrl = {};
    var hasCampaign = false;
    ATTR_FIELDS.forEach(function (key) {
      var value = params.get(key);
      if (value) { fromUrl[key] = value.slice(0, 200); hasCampaign = true; }
    });

    if (saved && !hasCampaign) return saved;

    var record = fromUrl;
    record.landing_page = (window.location.pathname + window.location.search).slice(0, 500);
    record.referrer = (document.referrer || '').slice(0, 500);
    record.t = Date.now();
    try { localStorage.setItem(ATTR_KEY, JSON.stringify(record)); } catch (e) { /* private mode */ }
    return record;
  }

  var attribution = loadAttribution();

  /* ---------- Quote forms ---------- */
  document.querySelectorAll('[data-quote-form]').forEach(function (form) {
    function setHidden(name, value) {
      var input = form.querySelector('input[type="hidden"][name="' + name + '"]');
      if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        form.appendChild(input);
      }
      // defaultValue survives form.reset()
      input.defaultValue = value || '';
      input.value = value || '';
    }

    function fillHidden() {
      setHidden('form_started', String(Date.now()));
      ['landing_page', 'referrer'].concat(ATTR_FIELDS).forEach(function (key) {
        setHidden(key, attribution[key]);
      });
    }
    fillHidden();

    var success = form.querySelector('[data-form-success]');
    if (success) success.setAttribute('role', 'status');

    var status = document.createElement('div');
    status.className = 'form-status is-error';
    status.setAttribute('role', 'alert');
    status.hidden = true;
    if (success) { success.parentNode.insertBefore(status, success); } else { form.appendChild(status); }

    var button = form.querySelector('button[type="submit"]');
    var buttonHtml = button ? button.innerHTML : '';

    var startTracked = false;
    form.addEventListener('focusin', function () {
      if (startTracked) return;
      startTracked = true;
      track('quote_start');
    });

    function clearErrors() {
      form.querySelectorAll('.field-error').forEach(function (node) { node.remove(); });
      form.querySelectorAll('[aria-invalid]').forEach(function (field) {
        field.removeAttribute('aria-invalid');
        field.removeAttribute('aria-describedby');
      });
      status.hidden = true;
    }

    function showErrors(errors) {
      var first = null;
      Object.keys(errors || {}).forEach(function (name) {
        var field = form.elements[name];
        if (!field || !field.id) return;
        var message = document.createElement('span');
        message.className = 'field-error';
        message.id = field.id + '-error';
        message.textContent = errors[name];
        field.setAttribute('aria-invalid', 'true');
        field.setAttribute('aria-describedby', message.id);
        field.insertAdjacentElement('afterend', message);
        if (!first) first = field;
      });
      if (first) first.focus();
    }

    function setBusy(busy) {
      if (!button) return;
      button.disabled = busy;
      if (busy) {
        button.setAttribute('aria-busy', 'true');
        button.textContent = TEXT.sending;
      } else {
        button.removeAttribute('aria-busy');
        button.innerHTML = buttonHtml;
      }
    }

    form.addEventListener('submit', function (e) {
      // Without fetch the browser posts normally and submit.php redirects back
      if (!window.fetch || !window.FormData || !form.action) return;
      e.preventDefault();

      clearErrors();
      if (success) success.hidden = true;
      setBusy(true);

      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'fetch' },
        credentials: 'same-origin'
      })
        .then(function (response) {
          return response.json().catch(function () { return { ok: false, message: TEXT.failed }; });
        })
        .then(function (data) {
          if (data.ok) {
            form.reset();
            fillHidden();
            if (success) {
              success.textContent = data.message || TEXT.sent;
              success.hidden = false;
            }
            track('quote_submit');
          } else {
            status.textContent = data.message || TEXT.failed;
            status.hidden = false;
            showErrors(data.errors);
          }
        })
        .catch(function () {
          status.textContent = TEXT.failed;
          status.hidden = false;
        })
        .then(function () { setBusy(false); });
    });
  });

  /* Plain (no-JS) submissions come back with ?sent=1 */
  if (new URLSearchParams(window.location.search).get('sent') === '1') {
    var box = document.querySelector('[data-form-success]');
    if (box) { box.textContent = TEXT.sent; box.hidden = false; }
  }

  /* ---------- Conversion events (GA4 / GTM ready) ---------- */
  document.querySelectorAll('[data-track]').forEach(function (el) {
    if (el.closest('form')) return; // forms report their own events
    el.addEventListener('click', function () { track(el.getAttribute('data-track')); });
  });
})();
