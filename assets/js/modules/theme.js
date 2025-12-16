/**
 * Mosaic Design System - Theme Module
 *
 * Provides theme switching between light and dark modes.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    const THEME_ATTRIBUTE = 'data-mosaic-theme';
    const STORAGE_KEY = 'mosaic-theme';

    /**
     * Get the current theme
     * @returns {string} 'light' or 'dark'
     */
    Mosaic.getTheme = function() {
        return document.documentElement.getAttribute(THEME_ATTRIBUTE) || 'light';
    };

    /**
     * Set the theme
     * @param {string} theme - 'light' or 'dark'
     * @param {boolean} persist - Save to localStorage (default: true)
     */
    Mosaic.setTheme = function(theme, persist = true) {
        if (theme === 'light') {
            document.documentElement.removeAttribute(THEME_ATTRIBUTE);
        } else {
            document.documentElement.setAttribute(THEME_ATTRIBUTE, theme);
        }

        if (persist) {
            localStorage.setItem(STORAGE_KEY, theme);
        }

        // Dispatch event for listeners
        window.dispatchEvent(new CustomEvent('mosaic-theme-change', {
            detail: { theme }
        }));
    };

    /**
     * Toggle between light and dark themes
     * @returns {string} The new theme
     */
    Mosaic.toggleTheme = function() {
        const current = Mosaic.getTheme();
        const next = current === 'dark' ? 'light' : 'dark';
        Mosaic.setTheme(next);
        return next;
    };

    /**
     * Initialize theme from localStorage or system preference
     * @param {object} options - Configuration options
     * @param {boolean} options.respectSystem - Use system preference if no saved theme (default: true)
     * @param {string} options.default - Default theme if no preference found (default: 'light')
     */
    Mosaic.initTheme = function(options = {}) {
        const respectSystem = options.respectSystem !== false;
        const defaultTheme = options.default || 'light';

        // Check localStorage first
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            Mosaic.setTheme(saved, false);
            return;
        }

        // Check system preference
        if (respectSystem && window.matchMedia) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (prefersDark) {
                Mosaic.setTheme('dark', false);
                return;
            }
        }

        // Use default
        Mosaic.setTheme(defaultTheme, false);
    };

    /**
     * Create a theme toggle button
     * @param {object} options - Button options
     * @param {string} options.lightIcon - Icon for light mode (default: 'admin-appearance')
     * @param {string} options.darkIcon - Icon for dark mode (default: 'admin-appearance')
     * @param {string} options.lightLabel - Label for light mode (default: 'Light')
     * @param {string} options.darkLabel - Label for dark mode (default: 'Dark')
     * @returns {HTMLElement} The toggle button element
     */
    Mosaic.createThemeToggle = function(options = {}) {
        const lightIcon = options.lightIcon || 'buddicons-activity';
        const darkIcon = options.darkIcon || 'moon';
        const lightLabel = options.lightLabel || 'Light';
        const darkLabel = options.darkLabel || 'Dark';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'mosaic-btn mosaic-btn-secondary-outline mosaic-theme-toggle';

        function updateButton() {
            const isDark = Mosaic.getTheme() === 'dark';
            button.innerHTML = `
                <span class="dashicons dashicons-${isDark ? darkIcon : lightIcon}"></span>
                ${isDark ? darkLabel : lightLabel}
            `;
            button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        }

        updateButton();

        button.addEventListener('click', () => {
            Mosaic.toggleTheme();
            updateButton();
        });

        // Listen for external theme changes
        window.addEventListener('mosaic-theme-change', updateButton);

        return button;
    };

    window.Mosaic = Mosaic;

})(window);
