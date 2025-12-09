/**
 * Mosaic Design System
 *
 * A comprehensive UI framework for WordPress plugins.
 * This file imports all Mosaic JavaScript modules.
 *
 * @version 1.0.0
 *
 * Usage:
 * - Include this file after jQuery (optional but recommended)
 * - Access via the global Mosaic object
 *
 * Example:
 *   Mosaic.confirm('Are you sure?').then(result => { ... });
 *   Mosaic.tabs('#my-tabs');
 *   Mosaic.copy('Text to copy', buttonElement);
 */

// Note: In production, these modules should be concatenated.
// For development, include each module file separately:
//
// <script src="mosaic/assets/js/modules/dialog.js"></script>
// <script src="mosaic/assets/js/modules/tabs.js"></script>
// <script src="mosaic/assets/js/modules/clipboard.js"></script>
// <script src="mosaic/assets/js/modules/ajax.js"></script>
// <script src="mosaic/assets/js/modules/filters.js"></script>

(function(window) {
    'use strict';

    // Initialize Mosaic namespace if not exists
    window.Mosaic = window.Mosaic || {};

    /**
     * Version info
     */
    Mosaic.version = '1.0.0';

    /**
     * Initialize all Mosaic components on a container
     */
    Mosaic.init = function(container = document) {
        const root = typeof container === 'string'
            ? document.querySelector(container)
            : container;

        if (!root) return;

        // Auto-init tabs
        root.querySelectorAll('[data-mosaic-tabs]').forEach(el => {
            if (!el.dataset.mosaicInitialized) {
                Mosaic.tabs(el);
                el.dataset.mosaicInitialized = 'true';
            }
        });

        // Auto-init copy buttons
        if (typeof Mosaic.initCopyButtons === 'function') {
            Mosaic.initCopyButtons();
        }

        // Auto-init filters
        root.querySelectorAll('[data-mosaic-filter]').forEach(el => {
            if (!el.dataset.mosaicInitialized) {
                Mosaic.filter({
                    containerSelector: el.dataset.mosaicFilter,
                    itemSelector: el.dataset.mosaicFilterItem || '.mosaic-filter-item'
                });
                el.dataset.mosaicInitialized = 'true';
            }
        });
    };

    /**
     * Utility: Debounce function
     */
    Mosaic.debounce = function(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func.apply(this, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(this, args);
        };
    };

    /**
     * Utility: Throttle function
     */
    Mosaic.throttle = function(func, limit) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    };

    /**
     * Utility: Format number with commas
     */
    Mosaic.formatNumber = function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    /**
     * Utility: Format bytes to human readable
     */
    Mosaic.formatBytes = function(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    };

    /**
     * Utility: Format date relative to now
     */
    Mosaic.formatRelativeTime = function(date) {
        const now = new Date();
        const past = new Date(date);
        const diffMs = now - past;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);

        if (diffSec < 60) return 'just now';
        if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
        if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
        if (diffDay < 30) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;

        return past.toLocaleDateString();
    };

    /**
     * Utility: Escape HTML
     */
    Mosaic.escapeHtml = function(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    /**
     * Utility: Generate unique ID
     */
    Mosaic.uniqueId = function(prefix = 'mosaic') {
        return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Mosaic.init());
    } else {
        Mosaic.init();
    }

})(window);
