const menuButton = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');
menuButton?.addEventListener('click', () => menu?.classList.toggle('open'));

const dialog = document.querySelector('[data-whatsapp-dialog]');
document.querySelectorAll('[data-whatsapp-open]').forEach((button) => {
    button.addEventListener('click', () => dialog?.showModal());
});
document.querySelector('[data-whatsapp-close]')?.addEventListener('click', () => dialog?.close());
dialog?.addEventListener('click', (event) => {
    if (event.target === dialog) dialog.close();
});
