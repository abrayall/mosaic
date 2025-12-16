/**
 * Mosaic Design System - Tag Editor Module
 *
 * Tag input widget with typeahead support.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Initialize a tag editor
     * @param {string|HTMLElement} selector - Container element or selector
     * @param {object} options - Configuration options
     * @param {string[]} options.tags - Initial tags
     * @param {string[]} options.suggestions - Autocomplete suggestions (typeahead)
     * @param {string} options.placeholder - Input placeholder text
     * @param {number} options.maxTags - Maximum number of tags allowed
     * @param {number} options.maxLength - Maximum length per tag
     * @param {boolean} options.allowDuplicates - Allow duplicate tags (default: false)
     * @param {boolean} options.allowFreeText - Allow tags not in suggestions (default: true)
     * @param {boolean} options.trimValue - Trim whitespace from tags (default: true)
     * @param {string[]} options.confirmKeys - Keys that confirm a tag (default: ['Enter', ','])
     * @param {string} options.variant - Tag style variant (default: 'primary')
     * @param {boolean} options.readOnly - Read-only mode, no editing (default: false)
     * @param {boolean} options.showAllOnFocus - Show all suggestions on focus (default: false)
     * @param {number} options.minCharsForSuggestions - Min chars before showing suggestions (default: 1)
     * @param {function} options.onAdd - Callback when tag is added
     * @param {function} options.onRemove - Callback when tag is removed
     * @param {function} options.onChange - Callback when tags change
     * @param {function} options.onBeforeAdd - Callback before adding (return false to prevent)
     * @param {function} options.validate - Custom validation function
     * @param {function} options.filterSuggestions - Custom filter function for suggestions
     * @returns {object} Tag editor controller
     */
    Mosaic.tagEditor = function(selector, options = {}) {
        const container = typeof selector === 'string'
            ? document.querySelector(selector)
            : selector;

        if (!container) return null;

        // Default options
        const config = {
            tags: [],
            suggestions: [],
            placeholder: 'Add a tag...',
            maxTags: Infinity,
            maxLength: Infinity,
            allowDuplicates: false,
            allowFreeText: true,
            trimValue: true,
            confirmKeys: ['Enter', ','],
            variant: 'primary',
            readOnly: false,
            showAllOnFocus: false,
            minCharsForSuggestions: 1,
            onAdd: null,
            onRemove: null,
            onChange: null,
            onBeforeAdd: null,
            validate: null,
            filterSuggestions: null,
            ...options
        };

        // State
        let tags = [...config.tags];
        let highlightedIndex = -1;
        let filteredSuggestions = [];

        // Create DOM structure
        const wrapper = document.createElement('div');
        wrapper.className = 'mosaic-tag-editor-wrapper';

        const editor = document.createElement('div');
        editor.className = config.readOnly ? 'mosaic-tag-display' : 'mosaic-tag-editor';
        editor.setAttribute('role', 'listbox');
        editor.setAttribute('aria-label', config.readOnly ? 'Tags' : 'Tag editor');

        let input = null;
        let suggestionsEl = null;

        if (!config.readOnly) {
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'mosaic-tag-editor-input';
            input.placeholder = config.placeholder;
            input.setAttribute('aria-autocomplete', 'list');

            suggestionsEl = document.createElement('div');
            suggestionsEl.className = 'mosaic-tag-suggestions';
            suggestionsEl.setAttribute('role', 'listbox');
        }

        // Hidden input for form submission
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = container.dataset.name || 'tags';

        if (input) editor.appendChild(input);
        wrapper.appendChild(editor);
        if (suggestionsEl) wrapper.appendChild(suggestionsEl);
        wrapper.appendChild(hiddenInput);
        container.appendChild(wrapper);

        // Helper functions
        function updateHiddenInput() {
            hiddenInput.value = tags.join(',');
        }

        function createTagElement(tag) {
            const pill = document.createElement('span');
            pill.className = `mosaic-tag-pill mosaic-tag-pill-${config.variant}`;
            pill.setAttribute('role', 'option');
            pill.dataset.tag = tag;

            const text = document.createElement('span');
            text.className = 'mosaic-tag-pill-text';
            text.textContent = tag;

            pill.appendChild(text);

            // Only add remove button if not read-only
            if (!config.readOnly) {
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'mosaic-tag-pill-remove';
                removeBtn.innerHTML = '&times;';
                removeBtn.setAttribute('aria-label', `Remove ${tag}`);
                removeBtn.tabIndex = -1;

                removeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    removeTag(tag);
                });

                pill.appendChild(removeBtn);
            }

            return pill;
        }

        function renderTags() {
            // Remove existing tags
            editor.querySelectorAll('.mosaic-tag-pill').forEach(el => el.remove());

            // Add tags (before input if editable, or just append if read-only)
            tags.forEach(tag => {
                const tagEl = createTagElement(tag);
                if (input) {
                    editor.insertBefore(tagEl, input);
                } else {
                    editor.appendChild(tagEl);
                }
            });

            // Update max state (only for editable mode)
            if (!config.readOnly) {
                if (tags.length >= config.maxTags) {
                    editor.classList.add('mosaic-tag-editor-max-reached');
                } else {
                    editor.classList.remove('mosaic-tag-editor-max-reached');
                }
            }

            updateHiddenInput();
        }

        function addTag(value) {
            const tag = config.trimValue ? value.trim() : value;

            // Validation
            if (!tag) return false;

            // Read-only mode doesn't allow adding
            if (config.readOnly) return false;

            if (tag.length > config.maxLength) {
                editor.classList.add('mosaic-tag-editor-error');
                setTimeout(() => editor.classList.remove('mosaic-tag-editor-error'), 1000);
                return false;
            }

            if (tags.length >= config.maxTags) {
                return false;
            }

            if (!config.allowDuplicates && tags.includes(tag)) {
                // Flash existing tag
                const existingTag = editor.querySelector(`[data-tag="${CSS.escape(tag)}"]`);
                if (existingTag) {
                    existingTag.style.animation = 'none';
                    existingTag.offsetHeight; // Trigger reflow
                    existingTag.style.animation = 'mosaic-tag-appear 0.15s ease-out';
                }
                return false;
            }

            // Check if tag is allowed (must be in suggestions if allowFreeText is false)
            if (!config.allowFreeText && config.suggestions.length > 0) {
                const lowerTag = tag.toLowerCase();
                const isInSuggestions = config.suggestions.some(s => s.toLowerCase() === lowerTag);
                if (!isInSuggestions) {
                    editor.classList.add('mosaic-tag-editor-error');
                    setTimeout(() => editor.classList.remove('mosaic-tag-editor-error'), 1000);
                    return false;
                }
            }

            if (config.validate && !config.validate(tag)) {
                editor.classList.add('mosaic-tag-editor-error');
                setTimeout(() => editor.classList.remove('mosaic-tag-editor-error'), 1000);
                return false;
            }

            if (config.onBeforeAdd && config.onBeforeAdd(tag) === false) {
                return false;
            }

            tags.push(tag);
            renderTags();
            if (input) input.value = '';
            closeSuggestions();

            if (config.onAdd) config.onAdd(tag);
            if (config.onChange) config.onChange(tags);

            return true;
        }

        function removeTag(tag) {
            const index = tags.indexOf(tag);
            if (index === -1) return false;

            tags.splice(index, 1);
            renderTags();

            if (config.onRemove) config.onRemove(tag);
            if (config.onChange) config.onChange(tags);

            input.focus();
            return true;
        }

        function removeLastTag() {
            if (tags.length > 0) {
                removeTag(tags[tags.length - 1]);
            }
        }

        function showSuggestions(query, showAll = false) {
            if (!suggestionsEl || !config.suggestions.length) return;

            const q = (query || '').toLowerCase();

            // Filter suggestions
            if (config.filterSuggestions) {
                filteredSuggestions = config.filterSuggestions(config.suggestions, q, tags);
            } else {
                filteredSuggestions = config.suggestions.filter(s => {
                    // Match query (or show all if showAll is true)
                    const matchesQuery = showAll || !q || s.toLowerCase().includes(q);
                    // Exclude already selected tags unless duplicates allowed
                    const notAlreadySelected = config.allowDuplicates || !tags.includes(s);
                    return matchesQuery && notAlreadySelected;
                });
            }

            if (filteredSuggestions.length === 0) {
                closeSuggestions();
                return;
            }

            suggestionsEl.innerHTML = '';
            highlightedIndex = -1;

            filteredSuggestions.forEach((suggestion, index) => {
                const item = document.createElement('div');
                item.className = 'mosaic-tag-suggestion-item';
                item.textContent = suggestion;
                item.dataset.index = index;

                item.addEventListener('click', () => {
                    addTag(suggestion);
                    if (input) input.focus();
                });

                item.addEventListener('mouseenter', () => {
                    setHighlighted(index);
                });

                suggestionsEl.appendChild(item);
            });

            suggestionsEl.classList.add('mosaic-open');
        }

        function showAllSuggestions() {
            showSuggestions('', true);
        }

        function closeSuggestions() {
            if (suggestionsEl) {
                suggestionsEl.classList.remove('mosaic-open');
            }
            highlightedIndex = -1;
            filteredSuggestions = [];
        }

        function setHighlighted(index) {
            if (!suggestionsEl) return;
            const items = suggestionsEl.querySelectorAll('.mosaic-tag-suggestion-item');
            items.forEach(item => item.classList.remove('mosaic-highlighted'));

            if (index >= 0 && index < items.length) {
                highlightedIndex = index;
                items[index].classList.add('mosaic-highlighted');
                items[index].scrollIntoView({ block: 'nearest' });
            }
        }

        function selectHighlighted() {
            if (highlightedIndex >= 0 && highlightedIndex < filteredSuggestions.length) {
                addTag(filteredSuggestions[highlightedIndex]);
                return true;
            }
            return false;
        }

        // Event handlers (only for editable mode)
        if (!config.readOnly && input) {
            editor.addEventListener('click', (e) => {
                if (e.target === editor) {
                    input.focus();
                }
            });

            input.addEventListener('focus', () => {
                // Show all suggestions on focus if configured
                if (config.showAllOnFocus && config.suggestions.length > 0) {
                    showAllSuggestions();
                }
            });

            input.addEventListener('keydown', (e) => {
                const value = input.value;

                if (config.confirmKeys.includes(e.key)) {
                    e.preventDefault();

                    if (highlightedIndex >= 0) {
                        selectHighlighted();
                    } else if (value) {
                        // Remove comma if it was used as confirm key
                        const tagValue = e.key === ',' ? value : value;
                        addTag(tagValue);
                    }
                } else if (e.key === 'Backspace' && !value) {
                    removeLastTag();
                } else if (e.key === 'Escape') {
                    closeSuggestions();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (suggestionsEl && suggestionsEl.classList.contains('mosaic-open')) {
                        setHighlighted(Math.min(highlightedIndex + 1, filteredSuggestions.length - 1));
                    } else if (config.showAllOnFocus || value) {
                        showSuggestions(value, config.showAllOnFocus && !value);
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (suggestionsEl && suggestionsEl.classList.contains('mosaic-open')) {
                        setHighlighted(Math.max(highlightedIndex - 1, 0));
                    }
                }
            });

            input.addEventListener('input', () => {
                const value = input.value;

                // Check for comma in value (in case it was pasted)
                if (value.includes(',')) {
                    const parts = value.split(',');
                    parts.forEach((part, i) => {
                        if (i < parts.length - 1) {
                            addTag(part);
                        } else {
                            input.value = part;
                        }
                    });
                    return;
                }

                if (value.length >= config.minCharsForSuggestions) {
                    showSuggestions(value);
                } else if (config.showAllOnFocus) {
                    showAllSuggestions();
                } else {
                    closeSuggestions();
                }
            });

            input.addEventListener('blur', () => {
                // Delay to allow click on suggestion
                setTimeout(() => {
                    closeSuggestions();
                }, 150);
            });

            input.addEventListener('paste', (e) => {
                const pasteData = e.clipboardData.getData('text');
                if (pasteData.includes(',')) {
                    e.preventDefault();
                    const parts = pasteData.split(',');
                    parts.forEach(part => addTag(part));
                }
            });
        }

        // Initialize with existing tags
        renderTags();

        // Public API
        const api = {
            /**
             * Add a tag
             * @param {string} tag - Tag to add
             * @returns {boolean} Success
             */
            add(tag) {
                return addTag(tag);
            },

            /**
             * Remove a tag
             * @param {string} tag - Tag to remove
             * @returns {boolean} Success
             */
            remove(tag) {
                return removeTag(tag);
            },

            /**
             * Remove all tags
             */
            removeAll() {
                tags = [];
                renderTags();
                if (config.onChange) config.onChange(tags);
            },

            /**
             * Get all tags
             * @returns {string[]} Current tags
             */
            getTags() {
                return [...tags];
            },

            /**
             * Set tags (replaces all)
             * @param {string[]} newTags - New tags array
             */
            setTags(newTags) {
                tags = [...newTags];
                renderTags();
                if (config.onChange) config.onChange(tags);
            },

            /**
             * Check if a tag exists
             * @param {string} tag - Tag to check
             * @returns {boolean}
             */
            hasTag(tag) {
                return tags.includes(tag);
            },

            /**
             * Update suggestions
             * @param {string[]} suggestions - New suggestions
             */
            setSuggestions(suggestions) {
                config.suggestions = suggestions;
            },

            /**
             * Focus the input
             */
            focus() {
                if (input) input.focus();
            },

            /**
             * Disable the editor
             */
            disable() {
                editor.classList.add('mosaic-disabled');
                if (input) input.disabled = true;
            },

            /**
             * Enable the editor
             */
            enable() {
                editor.classList.remove('mosaic-disabled');
                if (input) input.disabled = false;
            },

            /**
             * Set tag variant
             * @param {string} variant - New variant
             */
            setVariant(variant) {
                config.variant = variant;
                renderTags();
            },

            /**
             * Check if editor is read-only
             * @returns {boolean}
             */
            isReadOnly() {
                return config.readOnly;
            },

            /**
             * Show all available suggestions
             */
            showSuggestions() {
                showAllSuggestions();
            },

            /**
             * Get available suggestions
             * @returns {string[]}
             */
            getSuggestions() {
                return [...config.suggestions];
            },

            /**
             * Destroy the tag editor
             */
            destroy() {
                container.innerHTML = '';
            },

            /**
             * Get the container element
             */
            getElement() {
                return editor;
            }
        };

        // Store instance reference
        container.mosaicTagEditor = api;

        return api;
    };

    /**
     * Initialize all tag editors with data-mosaic-tags attribute
     */
    Mosaic.initTagEditors = function() {
        document.querySelectorAll('[data-mosaic-tags]').forEach(container => {
            if (!container.mosaicTagEditor) {
                const options = {};

                // Parse data attributes
                if (container.dataset.tags) {
                    options.tags = container.dataset.tags.split(',').map(t => t.trim()).filter(Boolean);
                }
                if (container.dataset.suggestions) {
                    options.suggestions = container.dataset.suggestions.split(',').map(t => t.trim());
                }
                if (container.dataset.placeholder) {
                    options.placeholder = container.dataset.placeholder;
                }
                if (container.dataset.maxTags) {
                    options.maxTags = parseInt(container.dataset.maxTags, 10);
                }
                if (container.dataset.maxLength) {
                    options.maxLength = parseInt(container.dataset.maxLength, 10);
                }
                if (container.dataset.variant) {
                    options.variant = container.dataset.variant;
                }
                if (container.dataset.allowDuplicates === 'true') {
                    options.allowDuplicates = true;
                }
                if (container.dataset.readOnly === 'true') {
                    options.readOnly = true;
                }
                if (container.dataset.showAllOnFocus === 'true') {
                    options.showAllOnFocus = true;
                }
                if (container.dataset.allowFreeText === 'false') {
                    options.allowFreeText = false;
                }

                Mosaic.tagEditor(container, options);
            }
        });
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', Mosaic.initTagEditors);
    } else {
        Mosaic.initTagEditors();
    }

    window.Mosaic = Mosaic;

})(window);
