(() => {
  const settings = window.evgTheme || {};
  const i18n = settings.i18n || {};

  /* Мобильное меню */
  const menuButton = document.getElementById('menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  if (menuButton && mobileNav) {
    const closeMenu = () => {
      mobileNav.hidden = true;
      menuButton.setAttribute('aria-expanded', 'false');
    };
    menuButton.addEventListener('click', () => {
      const open = mobileNav.hidden;
      mobileNav.hidden = !open;
      menuButton.setAttribute('aria-expanded', String(open));
    });
    mobileNav.addEventListener('click', event => {
      if (event.target.closest('a')) closeMenu();
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && !mobileNav.hidden) {
        closeMenu();
        menuButton.focus();
      }
    });
    window.matchMedia('(min-width: 768px)').addEventListener('change', event => {
      if (event.matches) closeMenu();
    });
  }

  /* Фильтр каталога на главной */
  const filters = document.querySelectorAll('[data-filter]');
  filters.forEach(button => button.addEventListener('click', () => {
    const value = button.dataset.filter;
    filters.forEach(item => item.setAttribute('aria-pressed', String(item === button)));
    document.querySelectorAll('[data-kind]').forEach(card => {
      card.hidden = value !== 'all' && !card.dataset.kind.split(' ').includes(value);
    });
  }));

  /* FAQ-аккордеон на странице товара */
  const questions = document.querySelectorAll('[data-faq]');
  questions.forEach(button => button.addEventListener('click', () => {
    const open = button.getAttribute('aria-expanded') !== 'true';
    questions.forEach(question => {
      const expanded = question === button && open;
      question.setAttribute('aria-expanded', String(expanded));
      const panel = document.getElementById(question.getAttribute('aria-controls'));
      if (panel) panel.hidden = !expanded;
    });
  }));

  /* Окно входа */
  const dialog = document.getElementById('auth-modal');
  if (!dialog || settings.loggedIn || !('HTMLDialogElement' in window)) return;

  const form = dialog.querySelector('#auth-form');
  const loginInput = dialog.querySelector('#auth-login');
  const passwordInput = dialog.querySelector('#auth-password');
  const toggle = dialog.querySelector('[data-auth-toggle]');
  const error = dialog.querySelector('#auth-error');
  const lostForm = dialog.querySelector('#auth-lost-form');
  const lostInput = dialog.querySelector('#auth-lost-login');
  const lostError = dialog.querySelector('#auth-lost-error');
  const lostFields = dialog.querySelector('[data-auth-lost-fields]');
  const lostSuccess = dialog.querySelector('#auth-lost-success');
  let opener = null;
  let nonce = settings.nonce || '';

  const setBusy = (targetForm, busy) => {
    const submit = targetForm.querySelector('[type="submit"]');
    targetForm.setAttribute('aria-busy', String(busy));
    if (submit) submit.disabled = busy;
  };

  const refreshNonce = async () => {
    const response = await fetch(`${settings.ajaxUrl}?action=evg_auth_nonce`, { credentials: 'same-origin' });
    const json = await response.json();
    if (json.success) nonce = json.data.nonce;
  };

  const request = async (action, fields, retried = false) => {
    const body = new URLSearchParams(fields);
    body.set('action', action);
    body.set('nonce', nonce);
    const response = await fetch(settings.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body,
    });
    const json = await response.json().catch(() => ({ success: false, data: { message: i18n.network } }));
    if (!json.success && json.data && json.data.code === 'nonce' && !retried) {
      nonce = json.data.nonce || nonce;
      if (!json.data.nonce) await refreshNonce();
      return request(action, fields, true);
    }
    return json;
  };

  const showError = (message, input) => {
    error.textContent = message;
    error.hidden = false;
    [loginInput, passwordInput].forEach(field => field.setAttribute('aria-invalid', String(field === input)));
    input.focus();
  };
  const resetError = () => {
    error.hidden = true;
    loginInput.removeAttribute('aria-invalid');
    passwordInput.removeAttribute('aria-invalid');
  };
  const resetLost = () => {
    lostError.hidden = true;
    lostInput.removeAttribute('aria-invalid');
    lostFields.hidden = false;
    lostSuccess.hidden = true;
  };
  const showView = view => {
    const lost = view === 'lost';
    form.hidden = lost;
    lostForm.hidden = !lost;
    dialog.setAttribute('aria-labelledby', lost ? 'auth-lost-title' : 'auth-title');
    if (lost) {
      resetLost();
      if (!lostInput.value) lostInput.value = loginInput.value.trim();
      lostInput.focus();
    } else {
      resetError();
      loginInput.focus();
    }
  };
  const openDialog = (trigger, view = 'login') => {
    opener = trigger || null;
    if (!dialog.open) dialog.showModal();
    showView(view);
  };

  document.addEventListener('click', event => {
    const trigger = event.target.closest('.login-btn, [data-auth-open]');
    if (!trigger) return;
    event.preventDefault();
    openDialog(trigger, trigger.dataset.authOpen === 'lost' ? 'lost' : 'login');
  });
  if (location.hash === '#login') openDialog(null);

  dialog.addEventListener('close', () => opener && opener.focus());
  dialog.addEventListener('click', event => {
    if (event.target === dialog || event.target.closest('[data-auth-close]')) return dialog.close();
    const viewSwitch = event.target.closest('[data-auth-view]');
    if (viewSwitch) showView(viewSwitch.dataset.authView);
  });

  toggle.addEventListener('click', () => {
    const visible = passwordInput.type === 'password';
    passwordInput.type = visible ? 'text' : 'password';
    toggle.setAttribute('aria-pressed', String(visible));
    toggle.setAttribute('aria-label', visible ? i18n.hidePassword : i18n.showPassword);
    toggle.querySelector('[data-icon="show"]').hidden = visible;
    toggle.querySelector('[data-icon="hide"]').hidden = !visible;
  });
  [loginInput, passwordInput].forEach(field => field.addEventListener('input', resetError));

  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (form.getAttribute('aria-busy') === 'true') return;
    if (!loginInput.value.trim()) return showError(i18n.enterLogin, loginInput);
    if (!passwordInput.value) return showError(i18n.enterPassword, passwordInput);

    setBusy(form, true);
    try {
      const fields = Object.fromEntries(new FormData(form));
      const json = await request('evg_login', fields);
      if (json.success) {
        location.assign(json.data.redirect || settings.accountUrl);
        return;
      }
      const field = json.data && json.data.field === 'log' ? loginInput : passwordInput;
      showError((json.data && json.data.message) || i18n.network, field);
      setBusy(form, false);
    } catch {
      showError(i18n.network, passwordInput);
      setBusy(form, false);
    }
  });

  lostInput.addEventListener('input', () => {
    lostError.hidden = true;
    lostInput.removeAttribute('aria-invalid');
  });
  lostForm.addEventListener('submit', async event => {
    event.preventDefault();
    if (lostForm.getAttribute('aria-busy') === 'true') return;
    const value = lostInput.value.trim();
    const fail = message => {
      lostError.textContent = message;
      lostError.hidden = false;
      lostInput.setAttribute('aria-invalid', 'true');
      lostInput.focus();
    };
    if (!value) return fail(i18n.enterLogin);

    setBusy(lostForm, true);
    try {
      const json = await request('evg_lost_password', { user_login: value });
      if (json.success) {
        lostFields.hidden = true;
        lostSuccess.textContent = json.data.message;
        lostSuccess.hidden = false;
        lostSuccess.focus();
      } else {
        fail((json.data && json.data.message) || i18n.network);
      }
    } catch {
      fail(i18n.network);
    }
    setBusy(lostForm, false);
  });
})();
