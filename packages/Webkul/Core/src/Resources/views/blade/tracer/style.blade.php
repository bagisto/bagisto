<style>
    body {
        position: relative;
    }

    [data-blade-path] {
        outline: 1px solid rgba(255, 0, 0, 0.6);
    }

    [data-blade-path]:hover {
        outline: 2px solid rgba(255, 0, 0, 1);
    }

    .blade-path-label {
        position: fixed !important;
        bottom: 10px !important;
        left: 10px !important;
        background: rgba(0, 0, 0, 0.95) !important;
        color: #fff !important;
        padding: 8px 12px !important;
        font-size: 11px !important;
        font-family: monospace !important;
        z-index: 9999999 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        border-radius: 4px !important;
        line-height: 1.4 !important;
        max-width: 90vw !important;
        word-break: break-all !important;
        display: none !important;
        align-items: center !important;
        gap: 8px !important;
        pointer-events: none !important;
        margin: 0 !important;
        transform: none !important;
        top: auto !important;
        right: auto !important;
    }

    .blade-path-label .path-text {
        flex: 1 !important;
        pointer-events: none !important;
    }

    .blade-path-label .copy-icon {
        cursor: pointer !important;
        padding: 4px 8px !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 3px !important;
        flex-shrink: 0 !important;
        font-size: 12px !important;
        transition: all 0.2s !important;
        pointer-events: auto !important;
        user-select: none !important;
    }

    .blade-path-label .copy-icon:hover {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    .blade-path-label .copy-icon.copied {
        background: #4caf50 !important;
    }

    .blade-path-label.active {
        display: flex !important;
        pointer-events: auto !important;
    }

    .main-container-wrapper .product-card .product-image img {
        max-width: 100%;
        height: 260px;
        object-fit: cover;
    }
</style>

<script>
    (function() {
        if (!document.body) return;
        
        /**
         * Store processed elements to avoid duplicates.
         */
        const processedElements = new WeakSet();

        /**
         * Copy text to clipboard.
         */
        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text);
            }
            
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                document.body.removeChild(textarea);

                return Promise.resolve();
            } catch (err) {
                document.body.removeChild(textarea);

                return Promise.reject(err);
            }
        }
        
        /**
         * Use event delegation for copy functionality.
         */
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('copy-icon')) {
                e.preventDefault();
                e.stopPropagation();
                
                const path = e.target.getAttribute('data-path');
                
                copyToClipboard(path).then(() => {
                    e.target.textContent = '✓ Copied!';
                    e.target.classList.add('copied');
                    e.target.style.minWidth = '60px';
                    e.target.style.textAlign = 'center';
                    
                    setTimeout(() => {
                        e.target.textContent = '📋';
                        e.target.classList.remove('copied');
                        e.target.style.minWidth = '';
                        e.target.style.textAlign = '';
                    }, 2000);
                }).catch(err => {
                    e.target.textContent = '✗ Failed';

                    setTimeout(() => {
                        e.target.textContent = '📋';
                    }, 2000);
                });
            }
        }, true);

        /**
         * Store labels separately in a WeakMap for elements that can't have children.
         */
        const labels = new WeakMap();

        /**
         * Create label element.
         */
        function createLabel(path) {
            const label = document.createElement('div');
            label.className = 'blade-path-label';
            
            const pathText = document.createElement('span');
            pathText.className = 'path-text';
            pathText.textContent = path;
            
            const copyIcon = document.createElement('span');
            copyIcon.className = 'copy-icon';
            copyIcon.textContent = '📋';
            copyIcon.title = 'Copy path';
            copyIcon.setAttribute('data-path', path);
            
            label.appendChild(pathText);
            label.appendChild(copyIcon);
            
            return label;
        }

        /**
         * Check if element can have children.
         */
        function canHaveChildren(element) {
            const voidElements = ['IMG', 'INPUT', 'BR', 'HR', 'META', 'LINK', 'AREA', 'BASE', 'COL', 'EMBED', 'PARAM', 'SOURCE', 'TRACK', 'WBR'];

            return !voidElements.includes(element.tagName);
        }

        /**
         * Add label to element if it has data-blade-path attribute.
         */
        function addLabelToElement(element) {
            if (processedElements.has(element) || !element.hasAttribute('data-blade-path')) {
                return;
            }
            
            const path = element.getAttribute('data-blade-path');

            if (!path) return;
            
            processedElements.add(element);
            
            if (element.querySelector('.blade-path-label') || labels.has(element)) {
                return;
            }

            const label = createLabel(path);
            
            if (canHaveChildren(element)) {
                try {
                    element.appendChild(label);
                    labels.set(element, label);
                } catch (e) {
                    document.body.appendChild(label);
                    labels.set(element, label);
                }
            } else {
                document.body.appendChild(label);
                labels.set(element, label);
            }
        }

        /**
         * Process all elements with data-blade-path attribute.
         */
        function processAllElements() {
            const elements = document.querySelectorAll('[data-blade-path]');

            elements.forEach(addLabelToElement);
        }

        /**
         * Initial processing.
         */
        processAllElements();

        /**
         * Watch for DOM changes with debounce.
         */
        let debounceTimer;

        const observer = new MutationObserver(function(mutations) {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(processAllElements, 100);
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['data-blade-path']
        });

        /**
         * Periodic check for Vue.js rendered content.
         */
        setInterval(processAllElements, 1000);

        /**
         * Run on various events.
         */
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', processAllElements);
        }
        
        window.addEventListener('load', processAllElements);

        /**
         * Handle hover to show only the directly hovered element's label.
         */
        let currentActiveLabel = null;

        document.addEventListener('mouseover', function(e) {
            const target = e.target.closest('[data-blade-path]');
            
            if (target) {
                if (currentActiveLabel) {
                    currentActiveLabel.classList.remove('active');
                }
                
                let label = target.querySelector(':scope > .blade-path-label');
                
                if (!label) {
                    label = labels.get(target);
                }
                
                if (label) {
                    if (label.parentElement === document.body) {
                        const rect = target.getBoundingClientRect();
                        label.style.top = (rect.top + window.scrollY) + 'px';
                        label.style.left = (rect.left + window.scrollX) + 'px';
                    }
                    
                    label.classList.add('active');
                    currentActiveLabel = label;
                }
                
                e.stopPropagation();
            }
        }, true);

        document.addEventListener('mouseout', function(e) {
            const target = e.target.closest('[data-blade-path]');
            
            if (target) {
                let label = target.querySelector(':scope > .blade-path-label');

                if (!label) {
                    label = labels.get(target);
                }
                
                if (label && currentActiveLabel === label) {
                    label.classList.remove('active');
                    currentActiveLabel = null;
                }
            }
        }, true);

        /**
         * Keyboard shortcut to copy path (Ctrl+Shift+C or Cmd+Shift+C).
         */
        let currentHoveredElement = null;

        document.addEventListener('mouseover', function(e) {
            const target = e.target.closest('[data-blade-path]');

            if (target) {
                currentHoveredElement = target;
            }
        }, true);

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                
                if (currentHoveredElement) {
                    const path = currentHoveredElement.getAttribute('data-blade-path');
                    
                    if (path) {
                        copyToClipboard(path).then(() => {
                            showCopyNotification('✓ Path Copied!');
                        }).catch(err => {
                            showCopyNotification('✗ Copy Failed');
                        });
                    }
                }
            }
        });

        /**
         * Show copy notification.
         */
        function showCopyNotification(message) {
            const existing = document.querySelector('.blade-copy-notification');

            if (existing) {
                existing.remove();
            }

            const notification = document.createElement('div');
            notification.className = 'blade-copy-notification';
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4caf50;
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                font-family: monospace;
                font-size: 14px;
                z-index: 99999999;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                animation: slideIn 0.3s ease-out;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        const style = document.createElement('style');

        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;

        document.head.appendChild(style);
    })();
</script>