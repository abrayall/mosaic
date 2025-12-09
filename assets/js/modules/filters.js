/**
 * Mosaic Design System - Filters Module
 *
 * Table and card filtering functionality.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Filter component class
     */
    class MosaicFilter {
        constructor(options = {}) {
            this.options = {
                containerSelector: null,
                itemSelector: '.mosaic-filter-item',
                filterAttr: 'data-filter',
                activeFilterClass: 'mosaic-filter-active',
                hiddenClass: 'mosaic-hidden',
                animateItems: true,
                onFilter: null,
                ...options
            };

            this.container = null;
            this.items = [];
            this.currentFilter = 'all';
            this.filterButtons = [];

            if (this.options.containerSelector) {
                this.init();
            }
        }

        init() {
            this.container = typeof this.options.containerSelector === 'string'
                ? document.querySelector(this.options.containerSelector)
                : this.options.containerSelector;

            if (!this.container) {
                console.warn('Mosaic Filter: Container not found');
                return;
            }

            this.items = Array.from(this.container.querySelectorAll(this.options.itemSelector));
            this.filterButtons = Array.from(document.querySelectorAll(`[data-filter-target="${this.options.containerSelector}"]`));

            // Set up filter button handlers
            this.filterButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.filter(btn.dataset.filter || 'all');
                    this.updateButtons(btn);
                });
            });
        }

        filter(filterValue) {
            this.currentFilter = filterValue;
            let visibleCount = 0;

            this.items.forEach(item => {
                const itemFilters = (item.getAttribute(this.options.filterAttr) || '').split(',').map(f => f.trim());
                const shouldShow = filterValue === 'all' || itemFilters.includes(filterValue);

                if (shouldShow) {
                    item.classList.remove(this.options.hiddenClass);
                    if (this.options.animateItems) {
                        item.style.animation = 'mosaic-filter-fade-in 0.3s ease forwards';
                    }
                    visibleCount++;
                } else {
                    item.classList.add(this.options.hiddenClass);
                }
            });

            // Callback
            if (typeof this.options.onFilter === 'function') {
                this.options.onFilter(filterValue, visibleCount, this.items.length);
            }

            // Dispatch event
            this.container.dispatchEvent(new CustomEvent('mosaic:filter', {
                detail: {
                    filter: filterValue,
                    visibleCount,
                    totalCount: this.items.length
                }
            }));

            return visibleCount;
        }

        updateButtons(activeButton) {
            this.filterButtons.forEach(btn => {
                btn.classList.remove(this.options.activeFilterClass);
            });
            if (activeButton) {
                activeButton.classList.add(this.options.activeFilterClass);
            }
        }

        reset() {
            this.filter('all');
            this.updateButtons(this.filterButtons.find(btn => btn.dataset.filter === 'all'));
        }

        getVisibleItems() {
            return this.items.filter(item => !item.classList.contains(this.options.hiddenClass));
        }

        getHiddenItems() {
            return this.items.filter(item => item.classList.contains(this.options.hiddenClass));
        }
    }

    /**
     * Search filter for tables/lists
     */
    class MosaicSearch {
        constructor(options = {}) {
            this.options = {
                inputSelector: null,
                containerSelector: null,
                itemSelector: '.mosaic-search-item',
                searchAttr: 'data-search',
                hiddenClass: 'mosaic-hidden',
                minChars: 1,
                debounce: 200,
                highlightMatches: false,
                highlightClass: 'mosaic-search-highlight',
                noResultsMessage: 'No results found',
                onSearch: null,
                ...options
            };

            this.input = null;
            this.container = null;
            this.items = [];
            this.debounceTimer = null;

            if (this.options.inputSelector && this.options.containerSelector) {
                this.init();
            }
        }

        init() {
            this.input = typeof this.options.inputSelector === 'string'
                ? document.querySelector(this.options.inputSelector)
                : this.options.inputSelector;

            this.container = typeof this.options.containerSelector === 'string'
                ? document.querySelector(this.options.containerSelector)
                : this.options.containerSelector;

            if (!this.input || !this.container) {
                console.warn('Mosaic Search: Input or container not found');
                return;
            }

            this.items = Array.from(this.container.querySelectorAll(this.options.itemSelector));

            this.input.addEventListener('input', () => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    this.search(this.input.value);
                }, this.options.debounce);
            });

            // Clear on Escape
            this.input.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.clear();
                }
            });
        }

        search(query) {
            const searchTerm = query.toLowerCase().trim();
            let visibleCount = 0;

            if (searchTerm.length < this.options.minChars) {
                // Show all items if query is too short
                this.items.forEach(item => {
                    item.classList.remove(this.options.hiddenClass);
                    if (this.options.highlightMatches) {
                        this.removeHighlights(item);
                    }
                });
                visibleCount = this.items.length;
            } else {
                this.items.forEach(item => {
                    const searchText = (item.getAttribute(this.options.searchAttr) || item.textContent).toLowerCase();
                    const matches = searchText.includes(searchTerm);

                    if (matches) {
                        item.classList.remove(this.options.hiddenClass);
                        if (this.options.highlightMatches) {
                            this.highlightMatches(item, searchTerm);
                        }
                        visibleCount++;
                    } else {
                        item.classList.add(this.options.hiddenClass);
                        if (this.options.highlightMatches) {
                            this.removeHighlights(item);
                        }
                    }
                });
            }

            // Callback
            if (typeof this.options.onSearch === 'function') {
                this.options.onSearch(searchTerm, visibleCount, this.items.length);
            }

            // Dispatch event
            this.container.dispatchEvent(new CustomEvent('mosaic:search', {
                detail: {
                    query: searchTerm,
                    visibleCount,
                    totalCount: this.items.length
                }
            }));

            return visibleCount;
        }

        highlightMatches(item, term) {
            // Simple highlight - for production use a more robust solution
            this.removeHighlights(item);
            const walker = document.createTreeWalker(item, NodeFilter.SHOW_TEXT, null, false);
            const textNodes = [];

            while (walker.nextNode()) {
                textNodes.push(walker.currentNode);
            }

            textNodes.forEach(node => {
                const text = node.textContent;
                const regex = new RegExp(`(${term})`, 'gi');
                if (regex.test(text)) {
                    const span = document.createElement('span');
                    span.innerHTML = text.replace(regex, `<mark class="${this.options.highlightClass}">$1</mark>`);
                    node.parentNode.replaceChild(span, node);
                }
            });
        }

        removeHighlights(item) {
            const highlights = item.querySelectorAll(`.${this.options.highlightClass}`);
            highlights.forEach(mark => {
                const parent = mark.parentNode;
                parent.replaceChild(document.createTextNode(mark.textContent), mark);
                parent.normalize();
            });
        }

        clear() {
            this.input.value = '';
            this.search('');
        }

        getValue() {
            return this.input.value;
        }
    }

    /**
     * Create filter instance
     */
    Mosaic.filter = function(options) {
        return new MosaicFilter(options);
    };

    /**
     * Create search instance
     */
    Mosaic.search = function(options) {
        return new MosaicSearch(options);
    };

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes mosaic-filter-fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .mosaic-search-highlight {
            background-color: var(--mosaic-warning-light, #fcf0d2);
            padding: 0 2px;
            border-radius: 2px;
        }
    `;
    document.head.appendChild(style);

    window.Mosaic = Mosaic;

})(window);
