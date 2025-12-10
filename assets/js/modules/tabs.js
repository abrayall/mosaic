/**
 * Mosaic Design System - Tabs Module
 *
 * Handles tab navigation with overflow menu support for responsive layouts.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Tab component class
     */
    class MosaicTabs {
        constructor(container, options = {}) {
            this.container = typeof container === 'string'
                ? document.querySelector(container)
                : container;

            if (!this.container) {
                console.warn('Mosaic Tabs: Container not found');
                return;
            }

            this.options = {
                tabSelector: '.mosaic-tab',
                contentSelector: '.mosaic-tab-content',
                activeClass: 'mosaic-tab-active',
                contentActiveClass: 'mosaic-tab-content-active',
                overflowEnabled: true,
                mobileSelectEnabled: true,
                hashNavigation: false,
                onChange: null,
                ...options
            };

            this.tabs = [];
            this.contents = [];
            this.currentTab = null;
            this.overflowMenu = null;
            this.mobileSelect = null;

            this.init();
        }

        init() {
            // Only select tabs/content that belong to THIS container, not nested ones
            this.tabs = Array.from(this.container.querySelectorAll(this.options.tabSelector))
                .filter(tab => tab.closest('[data-mosaic-tabs]') === this.container);
            this.contents = Array.from(this.container.querySelectorAll(this.options.contentSelector))
                .filter(content => content.closest('[data-mosaic-tabs]') === this.container);

            // Set up click handlers
            this.tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.activate(tab.dataset.tab);
                });
            });

            // Set up overflow menu if enabled
            if (this.options.overflowEnabled) {
                this.setupOverflow();
            }

            // Set up mobile select if enabled
            if (this.options.mobileSelectEnabled) {
                this.setupMobileSelect();
            }

            // Set up hash navigation if enabled
            if (this.options.hashNavigation) {
                this.setupHashNavigation();
            }

            // Activate initial tab (check hash first if enabled)
            let initialTab = null;
            if (this.options.hashNavigation && window.location.hash) {
                const hashTab = window.location.hash.substring(1);
                initialTab = this.tabs.find(t => t.dataset.tab === hashTab);
            }
            if (!initialTab) {
                initialTab = this.tabs.find(t => t.classList.contains(this.options.activeClass));
            }
            if (initialTab) {
                this.activate(initialTab.dataset.tab);
            } else if (this.tabs.length > 0) {
                this.activate(this.tabs[0].dataset.tab);
            }

            // Handle resize for overflow
            if (this.options.overflowEnabled) {
                window.addEventListener('resize', this.debounce(() => this.checkOverflow(), 150));
                this.checkOverflow();
            }
        }

        activate(tabId) {
            if (this.currentTab === tabId) return;

            const previousTab = this.currentTab;
            this.currentTab = tabId;

            // Update tab states
            this.tabs.forEach(tab => {
                if (tab.dataset.tab === tabId) {
                    tab.classList.add(this.options.activeClass);
                } else {
                    tab.classList.remove(this.options.activeClass);
                }
            });

            // Update content visibility
            this.contents.forEach(content => {
                if (content.dataset.tab === tabId) {
                    content.classList.add(this.options.contentActiveClass);
                } else {
                    content.classList.remove(this.options.contentActiveClass);
                }
            });

            // Update mobile select
            if (this.mobileSelect) {
                this.mobileSelect.value = tabId;
            }

            // Update overflow menu items
            if (this.overflowMenu) {
                const items = this.overflowMenu.querySelectorAll('.mosaic-tabs-overflow-item');
                items.forEach(item => {
                    if (item.dataset.tab === tabId) {
                        item.classList.add(this.options.activeClass);
                    } else {
                        item.classList.remove(this.options.activeClass);
                    }
                });
            }

            // Update URL hash if enabled
            if (this.options.hashNavigation) {
                history.replaceState(null, null, '#' + tabId);
            }

            // Callback
            if (typeof this.options.onChange === 'function') {
                this.options.onChange(tabId, previousTab);
            }

            // Dispatch custom event
            this.container.dispatchEvent(new CustomEvent('mosaic:tab:change', {
                detail: { current: tabId, previous: previousTab }
            }));
        }

        setupHashNavigation() {
            window.addEventListener('hashchange', () => {
                if (window.location.hash) {
                    const hashTab = window.location.hash.substring(1);
                    const tab = this.tabs.find(t => t.dataset.tab === hashTab);
                    if (tab) {
                        this.activate(hashTab);
                    }
                }
            });
        }

        setupOverflow() {
            const wrapper = this.container.querySelector('.mosaic-tabs-wrapper');
            if (!wrapper) return;

            // Check if overflow container already exists (from PHP)
            let overflowContainer = wrapper.querySelector('.mosaic-tabs-overflow');

            if (!overflowContainer) {
                // Create overflow container if it doesn't exist
                overflowContainer = document.createElement('div');
                overflowContainer.className = 'mosaic-tabs-overflow';
                overflowContainer.style.display = 'none';
                wrapper.appendChild(overflowContainer);
            }

            // Create button and menu inside the container
            let overflowBtn = overflowContainer.querySelector('.mosaic-tabs-overflow-btn');
            if (!overflowBtn) {
                overflowBtn = document.createElement('button');
                overflowBtn.className = 'mosaic-tabs-overflow-btn';
                overflowBtn.innerHTML = 'More <span class="dashicons dashicons-arrow-down-alt2"></span>';
                overflowContainer.appendChild(overflowBtn);
            }

            this.overflowMenu = overflowContainer.querySelector('.mosaic-tabs-overflow-menu');
            if (!this.overflowMenu) {
                this.overflowMenu = document.createElement('div');
                this.overflowMenu.className = 'mosaic-tabs-overflow-menu';
                overflowContainer.appendChild(this.overflowMenu);
            }

            // Toggle overflow menu
            overflowBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = this.overflowMenu.classList.contains('mosaic-show');
                // Close all other overflow menus first
                document.querySelectorAll('.mosaic-tabs-overflow-menu.mosaic-show').forEach(menu => {
                    menu.classList.remove('mosaic-show');
                });
                if (!isOpen) {
                    this.overflowMenu.classList.add('mosaic-show');
                }
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!this.overflowContainer.contains(e.target)) {
                    this.overflowMenu.classList.remove('mosaic-show');
                }
            });

            this.overflowContainer = overflowContainer;
        }

        checkOverflow() {
            if (!this.overflowContainer) return;

            const wrapper = this.container.querySelector('.mosaic-tabs-wrapper');
            const tabList = this.container.querySelector('.mosaic-tabs');
            if (!wrapper || !tabList) return;

            // Reset visibility first
            this.overflowContainer.style.display = 'none';
            this.tabs.forEach(tab => {
                tab.style.display = '';
            });

            // Calculate total width of all tabs
            const wrapperWidth = wrapper.offsetWidth;
            let totalWidth = 0;
            this.tabs.forEach(tab => {
                totalWidth += tab.offsetWidth + 4; // Include gap
            });

            // If everything fits, no overflow needed
            if (totalWidth <= wrapperWidth) {
                this.overflowMenu.innerHTML = '';
                return;
            }

            // Need overflow - calculate with button space reserved
            const buttonWidth = 100; // Approximate width for "More" button
            const availableWidth = wrapperWidth - buttonWidth;
            const overflowTabs = [];
            totalWidth = 0;

            this.tabs.forEach(tab => {
                totalWidth += tab.offsetWidth + 4;
                if (totalWidth > availableWidth) {
                    overflowTabs.push(tab);
                    tab.style.display = 'none';
                }
            });

            // Update overflow menu
            this.overflowMenu.innerHTML = '';
            if (overflowTabs.length > 0) {
                this.overflowContainer.style.display = '';
                overflowTabs.forEach(tab => {
                    const item = document.createElement('button');
                    item.className = 'mosaic-tabs-overflow-item';
                    item.dataset.tab = tab.dataset.tab;
                    item.innerHTML = tab.innerHTML;
                    if (tab.classList.contains(this.options.activeClass)) {
                        item.classList.add(this.options.activeClass);
                    }
                    item.addEventListener('click', () => {
                        this.activate(tab.dataset.tab);
                        this.overflowMenu.classList.remove('mosaic-show');
                    });
                    this.overflowMenu.appendChild(item);
                });
            }
        }

        setupMobileSelect() {
            const wrapper = this.container.querySelector('.mosaic-tabs-wrapper');
            if (!wrapper) return;

            const selectContainer = document.createElement('div');
            selectContainer.className = 'mosaic-tabs-mobile-select';

            this.mobileSelect = document.createElement('select');
            this.mobileSelect.className = 'mosaic-select';

            this.tabs.forEach(tab => {
                const option = document.createElement('option');
                option.value = tab.dataset.tab;
                option.textContent = tab.textContent.trim();
                if (tab.classList.contains(this.options.activeClass)) {
                    option.selected = true;
                }
                this.mobileSelect.appendChild(option);
            });

            this.mobileSelect.addEventListener('change', (e) => {
                this.activate(e.target.value);
            });

            selectContainer.appendChild(this.mobileSelect);
            wrapper.parentNode.insertBefore(selectContainer, wrapper);
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        destroy() {
            // Remove event listeners and clean up
            this.tabs.forEach(tab => {
                tab.replaceWith(tab.cloneNode(true));
            });
            if (this.overflowContainer) {
                this.overflowContainer.remove();
            }
            if (this.mobileSelect) {
                this.mobileSelect.parentNode.remove();
            }
        }
    }

    /**
     * Initialize tabs
     */
    Mosaic.tabs = function(container, options) {
        return new MosaicTabs(container, options);
    };

    /**
     * Auto-initialize tabs with data attribute
     */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-mosaic-tabs]').forEach(container => {
            const options = {};
            if (container.dataset.mosaicTabsHash !== undefined) {
                options.hashNavigation = true;
            }
            new MosaicTabs(container, options);
        });
    });

    window.Mosaic = Mosaic;

})(window);
