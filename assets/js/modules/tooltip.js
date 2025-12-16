/**
 * Mosaic Design System - Tooltip Module
 *
 * Provides programmatic tooltip creation and management.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Create a tooltip on an element
     * @param {string|HTMLElement} selector - Element or selector
     * @param {string} text - Tooltip text
     * @param {object} options - Tooltip options
     * @param {string} options.position - Position: 'top', 'bottom', 'left', 'right' (default: 'top')
     * @param {string} options.variant - Variant: 'dark', 'light', 'primary' (default: 'dark')
     * @param {boolean} options.multiline - Allow multiline text (default: false)
     * @returns {object} Tooltip controller with show(), hide(), destroy() methods
     */
    Mosaic.tooltip = function(selector, text, options = {}) {
        const element = typeof selector === 'string'
            ? document.querySelector(selector)
            : selector;

        if (!element) return null;

        const position = options.position || 'top';
        const variant = options.variant || 'dark';
        const multiline = options.multiline || false;

        // Create tooltip wrapper
        const wrapper = document.createElement('span');
        wrapper.className = 'mosaic-tooltip';

        if (position !== 'top') {
            wrapper.classList.add(`mosaic-tooltip-${position}`);
        }

        if (variant === 'light') {
            wrapper.classList.add('mosaic-tooltip-light');
        } else if (variant === 'primary') {
            wrapper.classList.add('mosaic-tooltip-primary');
        }

        if (multiline) {
            wrapper.classList.add('mosaic-tooltip-multiline');
        }

        // Create tooltip content
        const content = document.createElement('span');
        content.className = 'mosaic-tooltip-content';
        content.textContent = text;

        // Wrap element
        element.parentNode.insertBefore(wrapper, element);
        wrapper.appendChild(element);
        wrapper.appendChild(content);

        return {
            show: function() {
                content.classList.add('mosaic-show');
            },
            hide: function() {
                content.classList.remove('mosaic-show');
            },
            setText: function(newText) {
                content.textContent = newText;
            },
            destroy: function() {
                wrapper.parentNode.insertBefore(element, wrapper);
                wrapper.remove();
            }
        };
    };

    /**
     * Initialize all data-mosaic-tooltip elements
     * This is called automatically but can be called manually for dynamic content
     */
    Mosaic.initTooltips = function() {
        // Data attribute tooltips are handled by CSS
        // This function is for future JS enhancements like dynamic positioning
    };

    /**
     * Show a temporary tooltip near an element
     * @param {string|HTMLElement} selector - Element or selector
     * @param {string} text - Tooltip text
     * @param {object} options - Options
     * @param {number} options.duration - Duration in ms (default: 2000)
     * @param {string} options.position - Position (default: 'top')
     */
    Mosaic.showTooltip = function(selector, text, options = {}) {
        const element = typeof selector === 'string'
            ? document.querySelector(selector)
            : selector;

        if (!element) return;

        const duration = options.duration || 2000;
        const position = options.position || 'top';

        // Create floating tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'mosaic-tooltip-content mosaic-show';
        tooltip.textContent = text;
        tooltip.style.position = 'fixed';
        tooltip.style.zIndex = 'var(--mosaic-z-tooltip)';
        document.body.appendChild(tooltip);

        // Position tooltip
        const rect = element.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();

        let top, left;

        switch (position) {
            case 'bottom':
                top = rect.bottom + 8;
                left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'left':
                top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.left - tooltipRect.width - 8;
                break;
            case 'right':
                top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.right + 8;
                break;
            default: // top
                top = rect.top - tooltipRect.height - 8;
                left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
        }

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;

        // Auto-hide
        setTimeout(() => {
            tooltip.style.opacity = '0';
            setTimeout(() => tooltip.remove(), 150);
        }, duration);
    };

    window.Mosaic = Mosaic;

})(window);
