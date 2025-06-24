document.addEventListener('DOMContentLoaded', function () {
    const select = document.querySelector('.lang-selector select');
    if (!select) return;

    select.addEventListener('change', () => {
        select.classList.add('animating');

        setTimeout(() => {
            select.form.submit();
        }, 300);
    });

    select.addEventListener('transitionend', () => {
        select.classList.remove('animating');
    });
});
