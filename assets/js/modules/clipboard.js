/**
 * Mosaic Design System - Clipboard Module
 *
 * Provides clipboard copy functionality with visual feedback.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Default options
     */
    const defaults = {
        successDuration: 1500,
        successClass: 'mosaic-copy-success',
        errorClass: 'mosaic-copy-error',
        successIcon: 'dashicons-yes',
        errorIcon: 'dashicons-no',
        originalIconAttr: 'data-original-icon'
    };

    /**
     * Copy text to clipboard
     */
    async function copyToClipboard(text) {
        // Try modern Clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            try {
                await navigator.clipboard.writeText(text);
                return true;
            } catch (err) {
                console.warn('Clipboard API failed, falling back to execCommand');
            }
        }

        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        textarea.style.top = '-9999px';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        try {
            const successful = document.execCommand('copy');
            textarea.remove();
            return successful;
        } catch (err) {
            textarea.remove();
            return false;
        }
    }

    /**
     * Show visual feedback on element
     */
    function showFeedback(element, success, options = {}) {
        const opts = { ...defaults, ...options };
        const icon = element.querySelector('.dashicons');

        if (success) {
            element.classList.add(opts.successClass);
            element.classList.remove(opts.errorClass);

            if (icon) {
                // Store original icon class
                const originalClass = Array.from(icon.classList).find(c => c.startsWith('dashicons-') && c !== 'dashicons');
                if (originalClass) {
                    icon.setAttribute(opts.originalIconAttr, originalClass);
                    icon.classList.remove(originalClass);
                    icon.classList.add(opts.successIcon);
                    icon.style.color = 'var(--mosaic-success)';
                }
            }
        } else {
            element.classList.add(opts.errorClass);
            element.classList.remove(opts.successClass);

            if (icon) {
                const originalClass = Array.from(icon.classList).find(c => c.startsWith('dashicons-') && c !== 'dashicons');
                if (originalClass) {
                    icon.setAttribute(opts.originalIconAttr, originalClass);
                    icon.classList.remove(originalClass);
                    icon.classList.add(opts.errorIcon);
                    icon.style.color = 'var(--mosaic-critical)';
                }
            }
        }

        // Reset after duration
        setTimeout(() => {
            element.classList.remove(opts.successClass, opts.errorClass);

            if (icon) {
                const originalClass = icon.getAttribute(opts.originalIconAttr);
                if (originalClass) {
                    icon.classList.remove(opts.successIcon, opts.errorIcon);
                    icon.classList.add(originalClass);
                    icon.style.color = '';
                    icon.removeAttribute(opts.originalIconAttr);
                }
            }
        }, opts.successDuration);
    }

    /**
     * Copy text and show feedback
     */
    Mosaic.copy = async function(text, feedbackElement = null, options = {}) {
        const success = await copyToClipboard(text);

        if (feedbackElement) {
            showFeedback(feedbackElement, success, options);
        }

        return success;
    };

    /**
     * Copy from element's text content
     */
    Mosaic.copyFromElement = async function(sourceElement, feedbackElement = null, options = {}) {
        const source = typeof sourceElement === 'string'
            ? document.querySelector(sourceElement)
            : sourceElement;

        if (!source) {
            console.warn('Mosaic Clipboard: Source element not found');
            return false;
        }

        const text = source.value !== undefined ? source.value : source.textContent;
        return Mosaic.copy(text.trim(), feedbackElement || source, options);
    };

    /**
     * Initialize copy buttons with data attributes
     */
    function initCopyButtons() {
        document.querySelectorAll('[data-mosaic-copy]').forEach(button => {
            if (button.dataset.mosaicCopyInitialized) return;
            button.dataset.mosaicCopyInitialized = 'true';

            button.addEventListener('click', async (e) => {
                e.preventDefault();

                const text = button.dataset.mosaicCopy;
                const targetSelector = button.dataset.mosaicCopyTarget;

                if (targetSelector) {
                    await Mosaic.copyFromElement(targetSelector, button);
                } else if (text) {
                    await Mosaic.copy(text, button);
                }
            });
        });
    }

    // Auto-initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCopyButtons);

    // Re-initialize on dynamic content
    Mosaic.initCopyButtons = initCopyButtons;

    window.Mosaic = Mosaic;

})(window);
