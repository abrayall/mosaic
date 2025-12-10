/**
 * Mosaic Design System - Media Selector Module
 *
 * Integrates with WordPress Media Library for image/file selection.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Media frame instances cache
     */
    const frames = new WeakMap();

    /**
     * Initialize media selectors
     */
    function initMediaSelectors() {
        document.querySelectorAll('.mosaic-media-selector').forEach(initSelector);
    }

    /**
     * Initialize a single media selector
     */
    function initSelector(container) {
        const selectBtn = container.querySelector('.mosaic-media-selector-select');
        const removeBtn = container.querySelector('.mosaic-media-selector-remove');
        const input = container.querySelector('.mosaic-media-selector-input');
        const preview = container.querySelector('.mosaic-media-selector-preview');
        const type = container.dataset.type || 'image';

        if (!selectBtn || !input) return;

        // Select button click
        selectBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Check if wp.media is available
            if (typeof wp === 'undefined' || !wp.media) {
                console.error('WordPress media library not available. Make sure wp_enqueue_media() is called.');
                return;
            }

            // Get or create frame
            let frame = frames.get(container);

            if (!frame) {
                const frameOptions = {
                    title: getFrameTitle(type),
                    button: {
                        text: 'Select'
                    },
                    multiple: false
                };

                // Set library filter based on type
                if (type === 'image') {
                    frameOptions.library = { type: 'image' };
                } else if (type === 'video') {
                    frameOptions.library = { type: 'video' };
                } else if (type === 'audio') {
                    frameOptions.library = { type: 'audio' };
                }

                frame = wp.media(frameOptions);
                frames.set(container, frame);

                // Handle selection
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    setAttachment(container, attachment, type);
                });
            }

            // Open with current selection if exists
            frame.open();

            // Pre-select current attachment
            const currentId = input.value;
            if (currentId) {
                const selection = frame.state().get('selection');
                const attachment = wp.media.attachment(currentId);
                attachment.fetch();
                selection.add(attachment);
            }
        });

        // Remove button click
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clearAttachment(container);
            });
        }
    }

    /**
     * Get frame title based on type
     */
    function getFrameTitle(type) {
        const titles = {
            image: 'Select Image',
            video: 'Select Video',
            audio: 'Select Audio',
            file: 'Select File'
        };
        return titles[type] || 'Select Media';
    }

    /**
     * Set attachment data
     */
    function setAttachment(container, attachment, type) {
        const input = container.querySelector('.mosaic-media-selector-input');
        const preview = container.querySelector('.mosaic-media-selector-preview');
        const removeBtn = container.querySelector('.mosaic-media-selector-remove');

        // Set input value
        input.value = attachment.id;

        // Update preview
        if (preview) {
            if (type === 'image') {
                // Use medium size if available, otherwise full
                const url = attachment.sizes && attachment.sizes.medium
                    ? attachment.sizes.medium.url
                    : attachment.url;
                preview.style.backgroundImage = `url(${url})`;
                preview.innerHTML = '';
            } else {
                // Show filename for non-images
                preview.style.backgroundImage = '';
                preview.innerHTML = `<span class="mosaic-media-selector-filename">${attachment.filename}</span>`;
            }
        }

        // Show remove button
        if (removeBtn) {
            removeBtn.style.display = '';
        }

        // Add has-image class
        container.classList.add('mosaic-media-selector-has-image');

        // Trigger change event
        input.dispatchEvent(new Event('change', { bubbles: true }));

        // Custom event
        container.dispatchEvent(new CustomEvent('mosaic:media:select', {
            bubbles: true,
            detail: { attachment }
        }));
    }

    /**
     * Clear attachment
     */
    function clearAttachment(container) {
        const input = container.querySelector('.mosaic-media-selector-input');
        const preview = container.querySelector('.mosaic-media-selector-preview');
        const removeBtn = container.querySelector('.mosaic-media-selector-remove');
        const type = container.dataset.type || 'image';

        // Clear input
        input.value = '';

        // Reset preview
        if (preview) {
            preview.style.backgroundImage = '';
            const iconClass = type === 'image' ? 'format-image' : 'media-default';
            preview.innerHTML = `<span class="dashicons dashicons-${iconClass}"></span>`;
        }

        // Hide remove button
        if (removeBtn) {
            removeBtn.style.display = 'none';
        }

        // Remove has-image class
        container.classList.remove('mosaic-media-selector-has-image');

        // Trigger change event
        input.dispatchEvent(new Event('change', { bubbles: true }));

        // Custom event
        container.dispatchEvent(new CustomEvent('mosaic:media:remove', {
            bubbles: true
        }));
    }

    /**
     * Public API
     */
    Mosaic.media = {
        /**
         * Initialize all media selectors
         */
        init: initMediaSelectors,

        /**
         * Initialize a specific container
         */
        initSelector: initSelector,

        /**
         * Programmatically set attachment
         */
        set: function(selector, attachmentId) {
            const container = typeof selector === 'string'
                ? document.querySelector(selector)
                : selector;

            if (!container) return;

            // Fetch attachment data and set
            if (typeof wp !== 'undefined' && wp.media) {
                const attachment = wp.media.attachment(attachmentId);
                attachment.fetch().then(function() {
                    setAttachment(container, attachment.toJSON(), container.dataset.type || 'image');
                });
            }
        },

        /**
         * Programmatically clear attachment
         */
        clear: function(selector) {
            const container = typeof selector === 'string'
                ? document.querySelector(selector)
                : selector;

            if (container) {
                clearAttachment(container);
            }
        },

        /**
         * Get current attachment ID
         */
        get: function(selector) {
            const container = typeof selector === 'string'
                ? document.querySelector(selector)
                : selector;

            if (!container) return null;

            const input = container.querySelector('.mosaic-media-selector-input');
            return input ? (input.value || null) : null;
        }
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMediaSelectors);
    } else {
        initMediaSelectors();
    }

    window.Mosaic = Mosaic;

})(window);
