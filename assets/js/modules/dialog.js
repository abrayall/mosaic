/**
 * Mosaic Design System - Dialog Module
 *
 * Provides alert, confirm, and prompt dialogs with consistent styling.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Dialog configuration defaults
     */
    const defaults = {
        overlayClass: 'mosaic-modal-overlay',
        modalClass: 'mosaic-modal mosaic-dialog',
        headerClass: 'mosaic-modal-header',
        bodyClass: 'mosaic-modal-body',
        footerClass: 'mosaic-modal-footer',
        closeOnOverlay: true,
        closeOnEscape: true,
        animation: true
    };

    /**
     * Create dialog HTML structure
     */
    function createDialogHTML(options) {
        const iconClass = options.type ? `mosaic-modal-icon mosaic-modal-icon-${options.type}` : '';
        const iconHTML = options.icon ? `
            <div class="${iconClass}">
                <span class="dashicons dashicons-${options.icon}"></span>
            </div>
        ` : '';

        const closeHTML = options.closable ? `
            <button type="button" class="mosaic-modal-close" data-action="close">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        ` : '';

        const inputHTML = options.input ? `
            <input type="text" class="mosaic-input mosaic-dialog-input" value="${options.inputValue || ''}" placeholder="${options.inputPlaceholder || ''}">
        ` : '';

        const buttonsHTML = options.buttons.map(btn => `
            <button type="button" class="mosaic-btn ${btn.class || 'mosaic-btn-secondary'}" data-action="${btn.action}">
                ${btn.label}
            </button>
        `).join('');

        // Only show header if there's a title, icon, or close button
        const showHeader = options.title || options.icon || options.closable;
        const titleClass = options.type ? `mosaic-modal-title mosaic-modal-title-${options.type}` : 'mosaic-modal-title';
        const headerHTML = showHeader ? `
            <div class="${defaults.headerClass}">
                ${iconHTML}
                <h2 class="${titleClass}">${options.title || ''}</h2>
                ${closeHTML}
            </div>
        ` : '';

        return `
            <div class="${defaults.overlayClass}">
                <div class="${defaults.modalClass}">
                    ${headerHTML}
                    <div class="${defaults.bodyClass}">
                        <p>${options.message}</p>
                        ${inputHTML}
                    </div>
                    <div class="${defaults.footerClass}">
                        ${buttonsHTML}
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Show a dialog
     */
    function showDialog(options) {
        return new Promise((resolve) => {
            const html = createDialogHTML(options);
            const container = document.createElement('div');
            container.innerHTML = html;
            const overlay = container.firstElementChild;
            document.body.appendChild(overlay);

            const modal = overlay.querySelector('.mosaic-modal');
            const input = overlay.querySelector('.mosaic-dialog-input');
            const buttons = overlay.querySelectorAll('[data-action]');

            // Focus input or first button
            setTimeout(() => {
                if (input) {
                    input.focus();
                    input.select();
                } else if (buttons.length > 0) {
                    buttons[buttons.length - 1].focus();
                }
            }, 100);

            function close(result) {
                overlay.style.animation = 'mosaic-fade-out 0.15s ease-out forwards';
                modal.style.animation = 'mosaic-slide-out 0.15s ease-out forwards';
                setTimeout(() => {
                    overlay.remove();
                    resolve(result);
                }, 150);
            }

            // Button handlers
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const action = btn.dataset.action;
                    if (action === 'confirm') {
                        close(input ? input.value : true);
                    } else if (action === 'cancel' || action === 'close') {
                        close(input ? null : false);
                    } else {
                        close(action);
                    }
                });
            });

            // Overlay click
            if (defaults.closeOnOverlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) {
                        close(input ? null : false);
                    }
                });
            }

            // Escape key
            if (defaults.closeOnEscape) {
                const escHandler = (e) => {
                    if (e.key === 'Escape') {
                        document.removeEventListener('keydown', escHandler);
                        close(input ? null : false);
                    }
                };
                document.addEventListener('keydown', escHandler);
            }

            // Enter key for input
            if (input) {
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        close(input.value);
                    }
                });
            }
        });
    }

    /**
     * Show an alert dialog
     * @param {string} message - The message to display
     * @param {string|object} options - Title string or options object { title, icon, closable }
     */
    Mosaic.alert = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Notice',
            message,
            type: opts.icon ? 'info' : null,
            icon: opts.icon,
            closable: opts.closable !== false,
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a success alert
     */
    Mosaic.success = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Success',
            message,
            type: 'success',
            icon: opts.icon,
            closable: opts.closable !== false,
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a warning alert
     */
    Mosaic.warning = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Warning',
            message,
            type: 'warning',
            icon: opts.icon,
            closable: opts.closable !== false,
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show an error alert
     */
    Mosaic.error = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Error',
            message,
            type: 'error',
            icon: opts.icon,
            closable: opts.closable !== false,
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a confirm dialog
     */
    Mosaic.confirm = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Confirm',
            message,
            type: opts.icon ? 'info' : null,
            icon: opts.icon,
            closable: true,
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' },
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
            ]
        });
    };

    /**
     * Show a destructive confirm dialog
     */
    Mosaic.confirmDanger = function(message, options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Confirm',
            message,
            type: 'error',
            icon: opts.icon,
            closable: true,
            buttons: [
                { label: 'Confirm', action: 'confirm', class: 'mosaic-btn-danger' },
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
            ]
        });
    };

    /**
     * Show a prompt dialog
     */
    Mosaic.prompt = function(message, defaultValue = '', options = {}) {
        const opts = typeof options === 'string' ? { title: options } : options;
        return showDialog({
            title: opts.title || 'Input',
            message,
            type: opts.icon ? 'info' : null,
            icon: opts.icon,
            closable: true,
            input: true,
            inputValue: defaultValue,
            inputPlaceholder: opts.placeholder || '',
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' },
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary-outline' }
            ]
        });
    };

    /**
     * Show a custom dialog
     */
    Mosaic.dialog = function(options) {
        return showDialog({
            title: options.title || '',
            message: options.message || '',
            type: options.type,
            icon: options.icon,
            input: options.input || false,
            inputValue: options.inputValue || '',
            inputPlaceholder: options.inputPlaceholder || '',
            closable: options.closable || false,
            buttons: options.buttons || [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a modal with custom content
     * @param {object} options - Modal options
     * @param {string} options.title - Modal title
     * @param {string|HTMLElement} options.content - HTML string or DOM element
     * @param {string} options.size - Size: 'sm', 'lg', 'xl', 'full' (default: none)
     * @param {boolean} options.closable - Show close button (default: true)
     * @param {boolean} options.closeOnOverlay - Close when clicking overlay (default: true)
     * @param {boolean} options.closeOnEscape - Close on Escape key (default: true)
     * @param {array} options.buttons - Optional footer buttons
     * @param {function} options.onOpen - Callback when modal opens, receives modal element
     * @param {function} options.onClose - Callback when modal closes, receives action
     * @returns {Promise} Resolves with action when closed
     */
    Mosaic.modal = function(options = {}) {
        return new Promise((resolve) => {
            const closable = options.closable !== false;
            const closeOnOverlay = options.closeOnOverlay !== false;
            const closeOnEscape = options.closeOnEscape !== false;

            // Build size class
            const sizeClass = options.size ? `mosaic-modal-${options.size}` : '';

            // Build close button
            const closeHTML = closable ? `
                <button type="button" class="mosaic-modal-close" data-action="close">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            ` : '';

            // Build header
            const headerHTML = (options.title || closable) ? `
                <div class="${defaults.headerClass}">
                    <h2 class="mosaic-modal-title">${options.title || ''}</h2>
                    ${closeHTML}
                </div>
            ` : '';

            // Build footer with buttons
            let footerHTML = '';
            if (options.buttons && options.buttons.length > 0) {
                const buttonsHTML = options.buttons.map(btn => `
                    <button type="button" class="mosaic-btn ${btn.class || 'mosaic-btn-secondary'}" data-action="${btn.action}">
                        ${btn.label}
                    </button>
                `).join('');
                footerHTML = `<div class="${defaults.footerClass}">${buttonsHTML}</div>`;
            }

            // Build modal HTML
            const html = `
                <div class="${defaults.overlayClass}">
                    <div class="mosaic-modal ${sizeClass}">
                        ${headerHTML}
                        <div class="${defaults.bodyClass}"></div>
                        ${footerHTML}
                    </div>
                </div>
            `;

            // Create and append modal
            const container = document.createElement('div');
            container.innerHTML = html;
            const overlay = container.firstElementChild;
            document.body.appendChild(overlay);

            const modal = overlay.querySelector('.mosaic-modal');
            const body = overlay.querySelector('.mosaic-modal-body');

            // Insert content
            if (typeof options.content === 'string') {
                body.innerHTML = options.content;
            } else if (options.content instanceof HTMLElement) {
                body.appendChild(options.content);
            }

            // Close function
            function close(result) {
                overlay.style.animation = 'mosaic-fade-out 0.15s ease-out forwards';
                modal.style.animation = 'mosaic-slide-out 0.15s ease-out forwards';
                setTimeout(() => {
                    overlay.remove();
                    if (options.onClose) {
                        options.onClose(result);
                    }
                    resolve(result);
                }, 150);
            }

            // Expose close method on modal element
            modal.close = close;

            // Button handlers
            const buttons = overlay.querySelectorAll('[data-action]');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    close(btn.dataset.action);
                });
            });

            // Overlay click
            if (closeOnOverlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) {
                        close('overlay');
                    }
                });
            }

            // Escape key
            if (closeOnEscape) {
                const escHandler = (e) => {
                    if (e.key === 'Escape') {
                        document.removeEventListener('keydown', escHandler);
                        close('escape');
                    }
                };
                document.addEventListener('keydown', escHandler);
            }

            // Callback when opened
            if (options.onOpen) {
                setTimeout(() => options.onOpen(modal, body), 100);
            }
        });
    };

    /**
     * Close all open modals
     */
    Mosaic.closeModals = function() {
        document.querySelectorAll('.mosaic-modal-overlay').forEach(overlay => {
            overlay.remove();
        });
    };

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes mosaic-fade-out {
            to { opacity: 0; }
        }
        @keyframes mosaic-slide-out {
            to { opacity: 0; transform: translateY(-20px); }
        }
    `;
    document.head.appendChild(style);

    window.Mosaic = Mosaic;

})(window);
