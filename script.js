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
