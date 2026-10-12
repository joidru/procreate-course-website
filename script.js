const menuButton = document.getElementById('menu-toggle');
const mobileNav = document.getElementById('mobile-nav');
function closeMenu() {
  mobileNav.hidden = true;
  menuButton.setAttribute('aria-expanded', 'false');
  menuButton.setAttribute('aria-label', 'Открыть меню');
}
menuButton.addEventListener('click', () => {
  const open = menuButton.getAttribute('aria-expanded') !== 'true';
  menuButton.setAttribute('aria-expanded', String(open));
  menuButton.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
  mobileNav.hidden = !open;
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
const desktop = matchMedia('(min-width: 768px)');
desktop.addEventListener('change', event => { if (event.matches) closeMenu(); });
const filters = document.querySelectorAll('[data-filter]');
const catalogItems = document.querySelectorAll('[data-kind]');
filters.forEach(button => button.addEventListener('click', () => {
  const kind = button.dataset.filter;
  filters.forEach(other => other.setAttribute('aria-pressed', String(other === button)));
  catalogItems.forEach(item => { item.hidden = kind !== 'all' && item.dataset.kind !== kind; });
}));
const checkoutForm = document.getElementById('checkout-form');
if (checkoutForm) {
  const products = {
    basics: { title: 'Основы рисования в Procreate', kind: 'Курс · 30 уроков', price: 1980, old: 4980, cover: 'cover-basics.jpg' },
    dreams: { title: 'Procreate Dreams. Практический курс', kind: 'Курс', price: 1980, old: 5900, cover: 'cover-dreams.jpg' },
    watercolor: { title: 'Акварель от А до Я', kind: 'Курс', price: 1980, old: 4980, cover: 'cover-watercolor.jpg' },
    practice: { title: 'Практический курс', kind: 'Курс', price: 980, old: 2980, cover: 'cover-practice.jpg' },
    flowers: { title: 'Цветочные стикеры', kind: 'Курс', price: 1880, old: 2980, cover: 'cover-flowers.png' },
    mistakes: { title: 'Ошибки в рисунках', kind: 'Видео-урок', price: 500, old: 1900, cover: 'cover-mistakes.jpg' },
    oilpastel: { title: 'Кисти масляной пастели', kind: 'Набор кистей', price: 699, old: 1480, cover: 'cover-oilpastel.jpg' },
    'watercolor-brushes': { title: 'Акварельные кисти', kind: 'Набор кистей', price: 699, old: 1480, cover: 'cover-watercolor-brushes.png' },
  };
  const product = products[new URLSearchParams(location.search).get('item')] || products.oilpastel;
  const rub = value => `${value.toLocaleString('ru-RU')} ₽`;
  document.getElementById('order-title').textContent = product.title;
  document.getElementById('order-kind').textContent = product.kind;
  document.getElementById('order-cover').src = `./public/${product.cover}`;
  document.getElementById('order-old').textContent = rub(product.old);
  document.getElementById('order-discount').textContent = `−${rub(product.old - product.price)}`;
  document.getElementById('order-price').textContent = rub(product.price);
  document.title = `Оформление заказа: ${product.title} — Евгения Гагарина`;

  const couponToggle = document.getElementById('coupon-toggle');
  const couponPanel = document.getElementById('coupon-panel');
  const couponInput = document.getElementById('coupon');
  const couponStatus = document.getElementById('coupon-status');
  couponToggle.addEventListener('click', () => {
    const open = couponPanel.hidden;
    couponPanel.hidden = !open;
    couponToggle.setAttribute('aria-expanded', String(open));
    if (open) couponInput.focus();
  });
  document.getElementById('coupon-apply').addEventListener('click', () => {
    const code = couponInput.value.trim();
    couponStatus.textContent = code ? `Купон «${code}» будет применён при оплате` : 'Введите код купона';
  });

  const fields = {
    name: { input: document.getElementById('name'), valid: value => value.trim().length > 0 },
    email: { input: document.getElementById('email'), valid: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()) },
  };
  const validate = key => {
    const { input, valid } = fields[key];
    const ok = valid(input.value);
    input.setAttribute('aria-invalid', String(!ok));
    document.getElementById(`${key}-error`).hidden = ok;
    return ok;
  };
  Object.keys(fields).forEach(key => fields[key].input.addEventListener('blur', () => {
    if (fields[key].input.value) validate(key);
  }));
  checkoutForm.addEventListener('submit', event => {
    event.preventDefault();
    const invalid = Object.keys(fields).filter(key => !validate(key));
    if (invalid.length) {
      fields[invalid[0]].input.focus();
      return;
    }
    const data = new FormData(checkoutForm);
    const body = [
      `Заказ: ${product.title} × 1 — ${rub(product.price)}`,
      `Имя: ${data.get('name')}`,
      `Email: ${data.get('email')}`,
      data.get('coupon') ? `Купон: ${data.get('coupon')}` : '',
      data.get('comment') ? `Комментарий: ${data.get('comment')}` : '',
    ].filter(Boolean).join('\n');
    location.href = `mailto:courses@evgagarina.com?subject=${encodeURIComponent(`Заказ: ${product.title}`)}&body=${encodeURIComponent(body)}`;
  });
}
const retryLink = document.getElementById('retry-link');
const retryItem = new URLSearchParams(location.search).get('item');
if (retryLink && retryItem) retryLink.href = `./checkout.html?item=${encodeURIComponent(retryItem)}`;
const loginButtons = document.querySelectorAll('.login-btn');
// WordPress adds `logged-in` to <body> for authorized users — they go straight to the account page.
if (loginButtons.length && !document.body.classList.contains('logged-in') && 'HTMLDialogElement' in window) {
  const eyeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>';
  const eyeOffIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.7 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-2.2 3.1"/><path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/><path d="m2 2 20 20"/></svg>';
  const dialog = document.createElement('dialog');
  dialog.className = 'auth-modal';
  dialog.setAttribute('aria-labelledby', 'auth-title');
  dialog.innerHTML = `
    <form class="auth-modal__form" id="auth-form" novalidate>
      <button type="button" class="auth-modal__close" aria-label="Закрыть" data-auth-close>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
      <div class="auth-modal__head">
        <h2 id="auth-title" class="auth-modal__title">Вход в кабинет</h2>
        <p class="auth-modal__lead">Ваши курсы, заказы и сертификаты</p>
      </div>
      <div class="auth-field">
        <label for="auth-login">Email или логин</label>
        <input id="auth-login" name="log" type="text" autocomplete="username" autocapitalize="none" spellcheck="false" required aria-describedby="auth-error">
      </div>
      <div class="auth-field">
        <div class="auth-field__row">
          <label for="auth-password">Пароль</label>
          <a href="./account.html#lost-password" class="auth-modal__link">Забыли пароль?</a>
        </div>
        <div class="auth-field__password">
          <input id="auth-password" name="pwd" type="password" autocomplete="current-password" required aria-describedby="auth-error">
          <button type="button" class="auth-field__toggle" aria-label="Показать пароль" aria-pressed="false" data-auth-toggle>${eyeIcon}</button>
        </div>
      </div>
      <label class="auth-modal__remember"><input type="checkbox" name="rememberme" value="forever" checked> Запомнить меня</label>
      <p class="auth-modal__error" id="auth-error" role="alert" hidden></p>
      <button type="submit" class="auth-modal__submit">Войти</button>
      <div class="auth-modal__divider"><span>или</span></div>
      <a href="./account.html" class="auth-modal__yandex"><span class="auth-modal__yandex-mark" aria-hidden="true">Я</span>Войти через Яндекс</a>
    </form>`;
  document.body.append(dialog);

  const form = dialog.querySelector('#auth-form');
  const loginInput = dialog.querySelector('#auth-login');
  const passwordInput = dialog.querySelector('#auth-password');
  const toggle = dialog.querySelector('[data-auth-toggle]');
  const error = dialog.querySelector('#auth-error');
  let opener = null;

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

  loginButtons.forEach(button => button.addEventListener('click', event => {
    event.preventDefault();
    opener = button;
    resetError();
    dialog.showModal();
    loginInput.focus();
  }));
  dialog.addEventListener('close', () => opener?.focus());
  dialog.addEventListener('click', event => {
    if (event.target === dialog || event.target.closest('[data-auth-close]')) dialog.close();
  });
  toggle.addEventListener('click', () => {
    const visible = passwordInput.type === 'password';
    passwordInput.type = visible ? 'text' : 'password';
    toggle.setAttribute('aria-pressed', String(visible));
    toggle.setAttribute('aria-label', visible ? 'Скрыть пароль' : 'Показать пароль');
    toggle.innerHTML = visible ? eyeOffIcon : eyeIcon;
  });
  [loginInput, passwordInput].forEach(field => field.addEventListener('input', resetError));
  form.addEventListener('submit', event => {
    event.preventDefault();
    if (!loginInput.value.trim()) return showError('Введите email или логин', loginInput);
    if (!passwordInput.value) return showError('Введите пароль', passwordInput);
    location.href = './account.html';
  });
}
const questions = document.querySelectorAll('[data-faq]');
questions.forEach(button => button.addEventListener('click', () => {
  const open = button.getAttribute('aria-expanded') !== 'true';
  questions.forEach(question => {
    const expanded = question === button && open;
    question.setAttribute('aria-expanded', String(expanded));
    document.getElementById(question.getAttribute('aria-controls')).hidden = !expanded;
    question.querySelector('svg').style.transform = expanded ? 'rotate(45deg)' : '';
    question.querySelector('svg').classList.remove('rotate-45');
  });
}));
