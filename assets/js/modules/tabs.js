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
            this.tabs = Array.from(this.container.querySelectorAll(this.options.tabSelector));
            this.contents = Array.from(this.container.querySelectorAll(this.options.contentSelector));

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

            // Activate initial tab
            const activeTab = this.tabs.find(t => t.classList.contains(this.options.activeClass));
            if (activeTab) {
                this.currentTab = activeTab.dataset.tab;
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

            // Callback
            if (typeof this.options.onChange === 'function') {
                this.options.onChange(tabId, previousTab);
            }

            // Dispatch custom event
            this.container.dispatchEvent(new CustomEvent('mosaic:tab:change', {
                detail: { current: tabId, previous: previousTab }
            }));
        }

        setupOverflow() {
            const wrapper = this.container.querySelector('.mosaic-tabs-wrapper');
            if (!wrapper) return;

            // Create overflow container
            const overflowContainer = document.createElement('div');
            overflowContainer.className = 'mosaic-tabs-overflow';
            overflowContainer.style.display = 'none';

            const overflowBtn = document.createElement('button');
            overflowBtn.className = 'mosaic-tabs-overflow-btn';
            overflowBtn.innerHTML = 'More <span class="dashicons dashicons-arrow-down-alt2"></span>';

            this.overflowMenu = document.createElement('div');
            this.overflowMenu.className = 'mosaic-tabs-overflow-menu';

            overflowContainer.appendChild(overflowBtn);
            overflowContainer.appendChild(this.overflowMenu);
            wrapper.appendChild(overflowContainer);

            // Toggle overflow menu
            overflowBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.overflowMenu.classList.toggle('mosaic-show');
            });

            // Close on outside click
            document.addEventListener('click', () => {
                this.overflowMenu.classList.remove('mosaic-show');
            });

            this.overflowContainer = overflowContainer;
        }

        checkOverflow() {
            if (!this.overflowContainer) return;

            const wrapper = this.container.querySelector('.mosaic-tabs-wrapper');
            const tabList = this.container.querySelector('.mosaic-tabs');
            if (!wrapper || !tabList) return;

            const wrapperWidth = wrapper.offsetWidth - 120; // Account for overflow button
            let totalWidth = 0;
            const overflowTabs = [];

            // Reset visibility
            this.tabs.forEach(tab => {
                tab.style.display = '';
            });

            // Check which tabs overflow
            this.tabs.forEach(tab => {
                totalWidth += tab.offsetWidth + 4; // Include gap
                if (totalWidth > wrapperWidth) {
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
            } else {
                this.overflowContainer.style.display = 'none';
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
            new MosaicTabs(container);
        });
    });

    window.Mosaic = Mosaic;

})(window);
