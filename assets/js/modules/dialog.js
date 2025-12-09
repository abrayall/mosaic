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

        const inputHTML = options.input ? `
            <input type="text" class="mosaic-input mosaic-dialog-input" value="${options.inputValue || ''}" placeholder="${options.inputPlaceholder || ''}">
        ` : '';

        const buttonsHTML = options.buttons.map(btn => `
            <button type="button" class="mosaic-btn ${btn.class || 'mosaic-btn-secondary'}" data-action="${btn.action}">
                ${btn.label}
            </button>
        `).join('');

        return `
            <div class="${defaults.overlayClass}">
                <div class="${defaults.modalClass}">
                    <div class="${defaults.headerClass}">
                        ${iconHTML}
                        <h2 class="mosaic-modal-title">${options.title || ''}</h2>
                    </div>
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
                    } else if (action === 'cancel') {
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
     */
    Mosaic.alert = function(message, title = 'Notice') {
        return showDialog({
            title,
            message,
            type: 'info',
            icon: 'info',
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a success alert
     */
    Mosaic.success = function(message, title = 'Success') {
        return showDialog({
            title,
            message,
            type: 'success',
            icon: 'yes-alt',
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a warning alert
     */
    Mosaic.warning = function(message, title = 'Warning') {
        return showDialog({
            title,
            message,
            type: 'warning',
            icon: 'warning',
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show an error alert
     */
    Mosaic.error = function(message, title = 'Error') {
        return showDialog({
            title,
            message,
            type: 'error',
            icon: 'dismiss',
            buttons: [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a confirm dialog
     */
    Mosaic.confirm = function(message, title = 'Confirm') {
        return showDialog({
            title,
            message,
            type: 'warning',
            icon: 'warning',
            buttons: [
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary' },
                { label: 'Confirm', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
        });
    };

    /**
     * Show a destructive confirm dialog
     */
    Mosaic.confirmDanger = function(message, title = 'Are you sure?') {
        return showDialog({
            title,
            message,
            type: 'alert',
            icon: 'warning',
            buttons: [
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary' },
                { label: 'Delete', action: 'confirm', class: 'mosaic-btn-danger' }
            ]
        });
    };

    /**
     * Show a prompt dialog
     */
    Mosaic.prompt = function(message, defaultValue = '', title = 'Input') {
        return showDialog({
            title,
            message,
            type: 'info',
            icon: 'edit',
            input: true,
            inputValue: defaultValue,
            inputPlaceholder: '',
            buttons: [
                { label: 'Cancel', action: 'cancel', class: 'mosaic-btn-secondary' },
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
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
            type: options.type || 'info',
            icon: options.icon || 'info',
            input: options.input || false,
            inputValue: options.inputValue || '',
            inputPlaceholder: options.inputPlaceholder || '',
            buttons: options.buttons || [
                { label: 'OK', action: 'confirm', class: 'mosaic-btn-primary' }
            ]
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
