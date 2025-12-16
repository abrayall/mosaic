/**
 * Mosaic Design System - Dropdown Module
 *
 * Provides dropdown menu functionality.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Initialize a dropdown
     * @param {string|HTMLElement} selector - Dropdown container or selector
     * @param {object} options - Dropdown options
     * @param {boolean} options.closeOnSelect - Close when item selected (default: true)
     * @param {boolean} options.closeOnOutside - Close when clicking outside (default: true)
     * @param {function} options.onSelect - Callback when item selected
     * @param {function} options.onOpen - Callback when dropdown opens
     * @param {function} options.onClose - Callback when dropdown closes
     * @returns {object} Dropdown controller
     */
    Mosaic.dropdown = function(selector, options = {}) {
        const container = typeof selector === 'string'
            ? document.querySelector(selector)
            : selector;

        if (!container) return null;

        const trigger = container.querySelector('.mosaic-dropdown-trigger');
        const menu = container.querySelector('.mosaic-dropdown-menu');

        if (!trigger || !menu) return null;

        const closeOnSelect = options.closeOnSelect !== false;
        const closeOnOutside = options.closeOnOutside !== false;

        function open() {
            // Close other dropdowns first
            document.querySelectorAll('.mosaic-dropdown.mosaic-open').forEach(d => {
                if (d !== container) d.classList.remove('mosaic-open');
            });

            container.classList.add('mosaic-open');
            trigger.setAttribute('aria-expanded', 'true');

            if (options.onOpen) {
                options.onOpen();
            }
        }

        function close() {
            container.classList.remove('mosaic-open');
            trigger.setAttribute('aria-expanded', 'false');

            if (options.onClose) {
                options.onClose();
            }
        }

        function toggle() {
            if (container.classList.contains('mosaic-open')) {
                close();
            } else {
                open();
            }
        }

        function isOpen() {
            return container.classList.contains('mosaic-open');
        }

        // Trigger click
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggle();
        });

        // Item clicks
        menu.querySelectorAll('.mosaic-dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (item.disabled || item.classList.contains('mosaic-disabled')) {
                    e.preventDefault();
                    return;
                }

                if (options.onSelect) {
                    options.onSelect(item.dataset.value || item.textContent.trim(), item);
                }

                if (closeOnSelect) {
                    close();
                }
            });
        });

        // Close on outside click
        if (closeOnOutside) {
            document.addEventListener('click', (e) => {
                if (!container.contains(e.target) && isOpen()) {
                    close();
                }
            });
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isOpen()) {
                close();
                trigger.focus();
            }
        });

        // Keyboard navigation
        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                open();
                const firstItem = menu.querySelector('.mosaic-dropdown-item:not(:disabled)');
                if (firstItem) firstItem.focus();
            }
        });

        menu.addEventListener('keydown', (e) => {
            const items = Array.from(menu.querySelectorAll('.mosaic-dropdown-item:not(:disabled)'));
            const current = document.activeElement;
            const index = items.indexOf(current);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const next = items[index + 1] || items[0];
                next.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = items[index - 1] || items[items.length - 1];
                prev.focus();
            }
        });

        return {
            open,
            close,
            toggle,
            isOpen
        };
    };

    /**
     * Initialize all dropdowns with data-mosaic-dropdown attribute
     */
    Mosaic.initDropdowns = function() {
        document.querySelectorAll('[data-mosaic-dropdown]').forEach(container => {
            if (!container.dataset.mosaicDropdownInit) {
                Mosaic.dropdown(container);
                container.dataset.mosaicDropdownInit = 'true';
            }
        });
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', Mosaic.initDropdowns);
    } else {
        Mosaic.initDropdowns();
    }

    window.Mosaic = Mosaic;

})(window);
