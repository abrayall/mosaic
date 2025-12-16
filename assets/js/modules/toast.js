/**
 * Mosaic Design System - Toast Module
 *
 * Non-blocking popup notifications.
 */

(function(window) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    let container = null;
    let position = 'top-right';

    /**
     * Get or create toast container
     */
    function getContainer() {
        if (!container || !document.body.contains(container)) {
            container = document.createElement('div');
            container.className = `mosaic-toast-container mosaic-toast-container-${position}`;
            document.body.appendChild(container);
        }
        return container;
    }

    /**
     * Set toast position
     * @param {string} pos - Position: 'top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'
     */
    Mosaic.setToastPosition = function(pos) {
        position = pos;
        if (container) {
            container.className = `mosaic-toast-container mosaic-toast-container-${position}`;
        }
    };

    /**
     * Show a toast notification
     * @param {string} message - Toast message
     * @param {object} options - Toast options
     * @param {string} options.type - Type: 'default', 'success', 'error', 'warning', 'info'
     * @param {string} options.title - Optional title
     * @param {number} options.duration - Duration in ms (default: 4000, 0 for persistent)
     * @param {boolean} options.closable - Show close button (default: true)
     * @param {boolean} options.progress - Show progress bar (default: false)
     * @param {string} options.icon - Dashicon name (auto-selected if not specified)
     * @param {string} options.variant - 'dark' or 'light' (default: 'dark')
     * @param {array} options.actions - Action buttons [{label, onClick}]
     * @param {function} options.onClose - Callback when toast closes
     * @returns {object} Toast controller with close() method
     */
    Mosaic.toast = function(message, options = {}) {
        const type = options.type || 'default';
        const title = options.title || '';
        const duration = options.duration !== undefined ? options.duration : 4000;
        const closable = options.closable !== false;
        const showProgress = options.progress || false;
        const variant = options.variant || 'dark';
        const actions = options.actions || [];

        // Auto-select icon based on type
        const icons = {
            success: 'yes-alt',
            error: 'dismiss',
            warning: 'warning',
            info: 'info'
        };
        const icon = options.icon || icons[type] || null;

        // Build toast HTML
        const toast = document.createElement('div');
        let classes = ['mosaic-toast'];

        if (type !== 'default') {
            classes.push(`mosaic-toast-${type}`);
        }

        if (variant === 'light') {
            classes.push('mosaic-toast-light');
        }

        if (showProgress && duration > 0) {
            classes.push('mosaic-toast-with-progress');
        }

        toast.className = classes.join(' ');

        // Icon HTML
        const iconHTML = icon ? `
            <div class="mosaic-toast-icon">
                <span class="dashicons dashicons-${icon}"></span>
            </div>
        ` : '';

        // Title HTML
        const titleHTML = title ? `<div class="mosaic-toast-title">${title}</div>` : '';

        // Actions HTML
        let actionsHTML = '';
        if (actions.length > 0) {
            actionsHTML = '<div class="mosaic-toast-actions">' +
                actions.map((action, i) =>
                    `<button class="mosaic-toast-action" data-action="${i}">${action.label}</button>`
                ).join('') +
            '</div>';
        }

        // Close button HTML
        const closeHTML = closable ? `
            <button class="mosaic-toast-close">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        ` : '';

        // Progress bar HTML
        const progressHTML = (showProgress && duration > 0) ?
            `<div class="mosaic-toast-progress" style="animation-duration: ${duration}ms"></div>` : '';

        toast.innerHTML = `
            ${iconHTML}
            <div class="mosaic-toast-content">
                ${titleHTML}
                <div class="mosaic-toast-message">${message}</div>
                ${actionsHTML}
            </div>
            ${closeHTML}
            ${progressHTML}
        `;

        // Add to container
        const cont = getContainer();

        // Add to top or bottom based on position
        if (position.startsWith('bottom')) {
            cont.insertBefore(toast, cont.firstChild);
        } else {
            cont.appendChild(toast);
        }

        // Close function
        let timeout = null;
        function close() {
            if (timeout) clearTimeout(timeout);
            toast.classList.add('mosaic-toast-out');
            setTimeout(() => {
                toast.remove();
                if (options.onClose) {
                    options.onClose();
                }
            }, 200);
        }

        // Close button handler
        if (closable) {
            toast.querySelector('.mosaic-toast-close').addEventListener('click', close);
        }

        // Action handlers
        actions.forEach((action, i) => {
            const btn = toast.querySelector(`[data-action="${i}"]`);
            if (btn && action.onClick) {
                btn.addEventListener('click', () => {
                    action.onClick();
                    if (action.closeOnClick !== false) {
                        close();
                    }
                });
            }
        });

        // Auto-close
        if (duration > 0) {
            timeout = setTimeout(close, duration);
        }

        // Pause on hover
        toast.addEventListener('mouseenter', () => {
            if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }
            const progress = toast.querySelector('.mosaic-toast-progress');
            if (progress) {
                progress.style.animationPlayState = 'paused';
            }
        });

        toast.addEventListener('mouseleave', () => {
            if (duration > 0 && !timeout) {
                timeout = setTimeout(close, 1000);
            }
            const progress = toast.querySelector('.mosaic-toast-progress');
            if (progress) {
                progress.style.animationPlayState = 'running';
            }
        });

        return { close };
    };

    /**
     * Show success toast
     */
    Mosaic.toastSuccess = function(message, options = {}) {
        return Mosaic.toast(message, { ...options, type: 'success' });
    };

    /**
     * Show error toast
     */
    Mosaic.toastError = function(message, options = {}) {
        return Mosaic.toast(message, { ...options, type: 'error' });
    };

    /**
     * Show warning toast
     */
    Mosaic.toastWarning = function(message, options = {}) {
        return Mosaic.toast(message, { ...options, type: 'warning' });
    };

    /**
     * Show info toast
     */
    Mosaic.toastInfo = function(message, options = {}) {
        return Mosaic.toast(message, { ...options, type: 'info' });
    };

    /**
     * Clear all toasts
     */
    Mosaic.clearToasts = function() {
        if (container) {
            container.innerHTML = '';
        }
    };

    window.Mosaic = Mosaic;

})(window);
