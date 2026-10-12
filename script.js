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
