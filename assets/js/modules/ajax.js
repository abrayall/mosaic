/**
 * Mosaic Design System - AJAX Module
 *
 * WordPress AJAX wrapper with loading states and error handling.
 */

(function(window, $) {
    'use strict';

    const Mosaic = window.Mosaic || {};

    /**
     * Default AJAX options
     */
    const defaults = {
        method: 'POST',
        dataType: 'json',
        timeout: 30000,
        showLoading: true,
        loadingElement: null,
        loadingClass: 'mosaic-btn-loading',
        disableOnLoading: true
    };

    /**
     * Get WordPress AJAX URL
     */
    function getAjaxUrl() {
        // Try common WordPress AJAX URL variables
        if (typeof ajaxurl !== 'undefined') {
            return ajaxurl;
        }
        if (typeof mosaicContext !== 'undefined' && mosaicContext.ajaxurl) {
            return mosaicContext.ajaxurl;
        }
        // Fallback to standard WordPress path
        return '/wp-admin/admin-ajax.php';
    }

    /**
     * Get nonce value
     */
    function getNonce(nonceKey = 'nonce') {
        if (typeof mosaicContext !== 'undefined' && mosaicContext[nonceKey]) {
            return mosaicContext[nonceKey];
        }
        return null;
    }

    /**
     * Show loading state
     */
    function showLoading(element, options) {
        if (!element) return;

        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.add(options.loadingClass);
        if (options.disableOnLoading) {
            el.disabled = true;
        }
    }

    /**
     * Hide loading state
     */
    function hideLoading(element, options) {
        if (!element) return;

        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.remove(options.loadingClass);
        if (options.disableOnLoading) {
            el.disabled = false;
        }
    }

    /**
     * Make AJAX request (Promise-based)
     */
    Mosaic.ajax = function(action, data = {}, options = {}) {
        const opts = { ...defaults, ...options };

        return new Promise((resolve, reject) => {
            const loadingEl = opts.loadingElement;

            if (opts.showLoading && loadingEl) {
                showLoading(loadingEl, opts);
            }

            const requestData = {
                action: action,
                ...data
            };

            // Add nonce if available
            const nonce = getNonce(opts.nonceKey || 'nonce');
            if (nonce && !requestData.nonce) {
                requestData.nonce = nonce;
            }

            const ajaxOptions = {
                url: opts.url || getAjaxUrl(),
                method: opts.method,
                data: requestData,
                dataType: opts.dataType,
                timeout: opts.timeout
            };

            // Use jQuery if available, otherwise fetch
            if (typeof $ !== 'undefined' && $.ajax) {
                $.ajax(ajaxOptions)
                    .done((response) => {
                        if (opts.showLoading && loadingEl) {
                            hideLoading(loadingEl, opts);
                        }

                        if (response.success === false) {
                            const error = new Error(response.data?.message || 'Request failed');
                            error.response = response;
                            reject(error);
                        } else {
                            resolve(response.data !== undefined ? response.data : response);
                        }
                    })
                    .fail((xhr, status, error) => {
                        if (opts.showLoading && loadingEl) {
                            hideLoading(loadingEl, opts);
                        }

                        const err = new Error(error || status || 'Request failed');
                        err.xhr = xhr;
                        err.status = status;
                        reject(err);
                    });
            } else {
                // Fetch fallback
                const formData = new FormData();
                Object.keys(requestData).forEach(key => {
                    formData.append(key, requestData[key]);
                });

                fetch(ajaxOptions.url, {
                    method: opts.method,
                    body: formData
                })
                .then(response => response.json())
                .then(response => {
                    if (opts.showLoading && loadingEl) {
                        hideLoading(loadingEl, opts);
                    }

                    if (response.success === false) {
                        const error = new Error(response.data?.message || 'Request failed');
                        error.response = response;
                        reject(error);
                    } else {
                        resolve(response.data !== undefined ? response.data : response);
                    }
                })
                .catch(error => {
                    if (opts.showLoading && loadingEl) {
                        hideLoading(loadingEl, opts);
                    }
                    reject(error);
                });
            }
        });
    };

    /**
     * GET request shorthand
     */
    Mosaic.get = function(action, data = {}, options = {}) {
        return Mosaic.ajax(action, data, { ...options, method: 'GET' });
    };

    /**
     * POST request shorthand
     */
    Mosaic.post = function(action, data = {}, options = {}) {
        return Mosaic.ajax(action, data, { ...options, method: 'POST' });
    };

    /**
     * Request with automatic error handling
     */
    Mosaic.request = async function(action, data = {}, options = {}) {
        try {
            return await Mosaic.ajax(action, data, options);
        } catch (error) {
            if (options.showError !== false) {
                const message = error.response?.data?.message || error.message || 'An error occurred';
                if (typeof Mosaic.error === 'function') {
                    Mosaic.error(message);
                } else {
                    console.error('Mosaic AJAX Error:', message);
                }
            }
            throw error;
        }
    };

    /**
     * Poll for status updates
     */
    Mosaic.poll = function(action, data = {}, options = {}) {
        const opts = {
            interval: 2000,
            maxAttempts: 30,
            shouldStop: (response) => response.complete === true,
            onProgress: null,
            ...options
        };

        let attempts = 0;
        let intervalId = null;

        return new Promise((resolve, reject) => {
            const check = async () => {
                attempts++;

                try {
                    const response = await Mosaic.ajax(action, data, { showLoading: false });

                    if (typeof opts.onProgress === 'function') {
                        opts.onProgress(response, attempts);
                    }

                    if (opts.shouldStop(response)) {
                        clearInterval(intervalId);
                        resolve(response);
                    } else if (attempts >= opts.maxAttempts) {
                        clearInterval(intervalId);
                        reject(new Error('Max polling attempts reached'));
                    }
                } catch (error) {
                    clearInterval(intervalId);
                    reject(error);
                }
            };

            intervalId = setInterval(check, opts.interval);
            check(); // Initial check
        });
    };

    /**
     * Cancel polling
     */
    Mosaic.cancelPoll = function(pollPromise) {
        // Polling uses setInterval internally, handled by promise resolution
        // This is a placeholder for future enhancement
    };

    window.Mosaic = Mosaic;

})(window, typeof jQuery !== 'undefined' ? jQuery : null);
