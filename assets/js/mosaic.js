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
                const options = {};
                if (el.dataset.mosaicTabsHash !== undefined) {
                    options.hashNavigation = true;
                }
                Mosaic.tabs(el, options);
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

        // Auto-init dismissible alerts
        root.querySelectorAll('.mosaic-info-card-close').forEach(btn => {
            if (!btn.dataset.mosaicInitialized) {
                btn.addEventListener('click', function() {
                    const card = this.closest('.mosaic-info-card');
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-10px)';
                        card.style.transition = 'all 0.2s ease-out';
                        setTimeout(() => card.remove(), 200);
                    }
                });
                btn.dataset.mosaicInitialized = 'true';
            }
        });

        // Auto-init collapsible cards
        root.querySelectorAll('.mosaic-card-collapsible').forEach(card => {
            if (!card.dataset.mosaicInitialized) {
                const header = card.querySelector('.mosaic-card-header-collapsible');
                const collapseBtn = card.querySelector('.mosaic-card-collapse-btn');
                const content = card.querySelector('.mosaic-card-collapsible-content');

                const toggleCollapse = () => {
                    const isCollapsed = card.classList.contains('mosaic-card-collapsed');
                    if (isCollapsed) {
                        // Expanding - clear all inline styles first
                        content.style.visibility = '';
                        content.style.opacity = '';
                        content.style.overflow = '';
                        card.classList.remove('mosaic-card-collapsed');
                        // Measure and animate
                        const height = content.scrollHeight;
                        content.style.maxHeight = '0';
                        requestAnimationFrame(() => {
                            content.style.maxHeight = height + 'px';
                            setTimeout(() => {
                                content.style.maxHeight = '';
                            }, 200);
                        });
                    } else {
                        // Collapsing
                        content.style.maxHeight = content.scrollHeight + 'px';
                        requestAnimationFrame(() => {
                            content.style.maxHeight = '0';
                            card.classList.add('mosaic-card-collapsed');
                        });
                    }
                };

                if (header) {
                    header.addEventListener('click', (e) => {
                        if (!e.target.closest('.mosaic-card-close-btn') && !e.target.closest('button:not(.mosaic-card-collapse-btn)')) {
                            toggleCollapse();
                        }
                    });
                }

                if (collapseBtn) {
                    collapseBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        toggleCollapse();
                    });
                }

                // CSS handles initial collapsed state - no JS needed

                card.dataset.mosaicInitialized = 'true';
            }
        });

        // Auto-init dismissible cards
        root.querySelectorAll('.mosaic-card-close-btn').forEach(btn => {
            if (!btn.dataset.mosaicInitialized) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const card = this.closest('.mosaic-card');
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-10px)';
                        card.style.transition = 'all 0.2s ease-out';
                        setTimeout(() => card.remove(), 200);
                    }
                });
                btn.dataset.mosaicInitialized = 'true';
            }
        });

        // Auto-init dismissible status cards
        root.querySelectorAll('.mosaic-status-card-close').forEach(btn => {
            if (!btn.dataset.mosaicInitialized) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const card = this.closest('.mosaic-status-card');
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-10px)';
                        card.style.transition = 'all 0.2s ease-out';
                        setTimeout(() => card.remove(), 200);
                    }
                });
                btn.dataset.mosaicInitialized = 'true';
            }
        });

        // Auto-init accordions
        root.querySelectorAll('.mosaic-accordion').forEach(accordion => {
            if (!accordion.dataset.mosaicInitialized) {
                const allowMultiple = accordion.dataset.multiple === 'true';

                accordion.querySelectorAll('.mosaic-accordion-header').forEach(header => {
                    header.addEventListener('click', () => {
                        const item = header.closest('.mosaic-accordion-item');
                        const content = item.querySelector('.mosaic-accordion-content');
                        const isOpen = item.classList.contains('mosaic-accordion-item-open');

                        // Close other items if not allowing multiple
                        if (!allowMultiple && !isOpen) {
                            accordion.querySelectorAll('.mosaic-accordion-item-open').forEach(openItem => {
                                const openContent = openItem.querySelector('.mosaic-accordion-content');
                                openContent.style.maxHeight = '0';
                                openItem.classList.remove('mosaic-accordion-item-open');
                            });
                        }

                        // Toggle current item
                        if (isOpen) {
                            content.style.maxHeight = '0';
                            item.classList.remove('mosaic-accordion-item-open');
                        } else {
                            content.style.maxHeight = content.scrollHeight + 'px';
                            item.classList.add('mosaic-accordion-item-open');
                        }
                    });
                });

                // Initialize open items
                accordion.querySelectorAll('.mosaic-accordion-item-open').forEach(item => {
                    const content = item.querySelector('.mosaic-accordion-content');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });

                accordion.dataset.mosaicInitialized = 'true';
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

    /**
     * Loading state utilities
     */
    Mosaic.loading = function(element, show = true, options = {}) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        const { size, dark } = options;

        if (show) {
            el.classList.add('mosaic-loading');
            if (size === 'sm') el.classList.add('mosaic-loading-sm');
            if (size === 'lg') el.classList.add('mosaic-loading-lg');
            if (dark) el.classList.add('mosaic-loading-dark');
        } else {
            el.classList.remove('mosaic-loading', 'mosaic-loading-sm', 'mosaic-loading-lg', 'mosaic-loading-dark');
        }

        return el;
    };

    Mosaic.startLoading = function(element, options = {}) {
        return Mosaic.loading(element, true, options);
    };

    Mosaic.stopLoading = function(element) {
        return Mosaic.loading(element, false);
    };

    /**
     * Page-level loading (covers entire .mosaic container)
     */
    Mosaic.startPage = function(options = {}) {
        const page = document.querySelector('.mosaic');
        return Mosaic.loading(page, true, options);
    };

    Mosaic.stopPage = function() {
        const page = document.querySelector('.mosaic');
        return Mosaic.loading(page, false);
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Mosaic.init());
    } else {
        Mosaic.init();
    }

})(window);
