// CSRF token for all fetch requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function bhFetch(url, options = {}) {
    return fetch(url, {
        ...options,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            ...(options.headers || {}),
        },
    });
}

window.bhFetch = bhFetch;

document.addEventListener('DOMContentLoaded', () => {
    initNavbarScroll();
    initImagePreview();
});

function initNavbarScroll() {
    const navbar = document.querySelector('.bh-navbar');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 30) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
    });
}

function initImagePreview() {
    document.querySelectorAll('[data-image-input]').forEach((input) => {
        const previewId = input.dataset.imageInput;
        const preview = document.getElementById(previewId);
        if (!preview) return;

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `<img src="${e.target.result}" alt="preview">`;
            };
            reader.readAsDataURL(file);
        });
    });
}