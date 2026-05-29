import './bootstrap';

const applyTheme = (theme) => {
    document.documentElement.classList.toggle('light-mode', theme === 'light');
    localStorage.setItem('aviation-theme', theme);
};

const hideSidebar = () => {
    const sidebar = document.querySelector('[data-sidebar-panel]');
    const overlay = document.querySelector('[data-sidebar-overlay]');

    if (sidebar) {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
    }

    if (overlay) {
        overlay.classList.add('hidden');
    }
};

const toggleSidebar = () => {
    const sidebar = document.querySelector('[data-sidebar-panel]');
    const overlay = document.querySelector('[data-sidebar-overlay]');

    if (!sidebar || !overlay) {
        return;
    }

    const isHidden = sidebar.classList.contains('-translate-x-full');

    sidebar.classList.toggle('-translate-x-full', !isHidden);
    sidebar.classList.toggle('translate-x-0', isHidden);
    overlay.classList.toggle('hidden', !isHidden);
};

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(localStorage.getItem('aviation-theme') || 'dark');

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.classList.contains('light-mode') ? 'dark' : 'light';
            applyTheme(nextTheme);
        });
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', toggleSidebar);
    });

    document.querySelectorAll('[data-sidebar-overlay]').forEach((overlay) => {
        overlay.addEventListener('click', hideSidebar);
    });
});