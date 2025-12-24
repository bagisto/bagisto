<style>
    body {
        position: relative;
    }

    [data-blade-path] {
        outline: 1px solid rgba(255, 0, 0, 0.6);
        position: relative;
    }

    [data-blade-path]:hover {
        outline: 2px solid rgba(255, 0, 0, 1);
    }

    .blade-path-label {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #000;
        color: #fff;
        padding: 2px 6px;
        font-size: 9px;
        font-family: monospace;
        z-index: 999999;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        white-space: nowrap;
        border-radius: 2px;
        line-height: 1.3;
        max-width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 4px;
        pointer-events: auto;
    }

    .blade-path-label .path-text {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .blade-path-label .copy-icon {
        cursor: pointer;
        padding: 2px 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        flex-shrink: 0;
        font-size: 11px;
        transition: all 0.2s;
        pointer-events: auto;
        user-select: none;
    }

    .blade-path-label .copy-icon:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .blade-path-label .copy-icon.copied {
        background: #4caf50;
    }

    [data-blade-path] [data-blade-path] .blade-path-label {
        top: 22px;
    }

    [data-blade-path] [data-blade-path] [data-blade-path] .blade-path-label {
        top: 42px;
    }

    [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] .blade-path-label {
        top: 62px;
    }

    [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] .blade-path-label {
        top: 82px;
    }

    [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] .blade-path-label {
        top: 102px;
    }

    [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] [data-blade-path] .blade-path-label {
        top: 122px;
    }

    [data-blade-path]:hover .blade-path-label {
        max-width: 600px;
        font-weight: bold;
        z-index: 9999999;
        font-size: 10px;
        padding: 4px 8px;
    }

    [data-blade-path]:hover .blade-path-label .path-text {
        white-space: normal;
        word-break: break-all;
    }

    .main-container-wrapper .product-card .product-image img {
        max-width: 100%;
        height: 260px;
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.body) return;
        
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_COMMENT,
            null,
            false
        );

        let comment;

        const pathMap = new Map();

        /**
         * First Pass: Collect all blade-tracer comments.
         */
        while (comment = walker.nextNode()) {
            if (comment.nodeValue.includes('blade-tracer-start:')) {
                const match = comment.nodeValue.match(/blade-tracer-start:\s*(.+)/);

                if (match) {
                    pathMap.set(comment, match[1].trim());
                }
            }
        }

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
         * Create label element.
         */
        function createLabel(element, path) {
            const label = document.createElement('div');
            label.className = 'blade-path-label';
            label.style.pointerEvents = 'auto';
            
            const pathText = document.createElement('span');
            pathText.className = 'path-text';
            pathText.textContent = path;
            pathText.style.pointerEvents = 'none';
            
            const copyIcon = document.createElement('span');
            copyIcon.className = 'copy-icon';
            copyIcon.textContent = '📋';
            copyIcon.title = 'Copy path';
            copyIcon.setAttribute('data-path', path);
            copyIcon.style.pointerEvents = 'auto';
            copyIcon.style.cursor = 'pointer';
            copyIcon.style.zIndex = '9999999';
            copyIcon.style.position = 'relative';
            
            label.appendChild(pathText);
            label.appendChild(copyIcon);
            
            return label;
        }

        /** 
         * Store all traced elements and their paths.
         */
        const tracedElements = new Map();

        /**
         * Ensure label exists for an element.
         */
        function ensureLabel(element, path) {
            if (!document.body.contains(element)) {
                tracedElements.delete(element);

                return;
            }

            const existingLabel = element.querySelector('.blade-path-label');

            if (!existingLabel) {
                const label = createLabel(element, path);

                element.appendChild(label);
            }
        }

        /**
         * Ensure all traced elements have labels.
         */
        function ensureAllLabels() {
            const elements = document.querySelectorAll('[data-blade-path]');

            elements.forEach(function(element) {
                const path = element.getAttribute('data-blade-path');

                if (path) {
                    tracedElements.set(element, path);

                    ensureLabel(element, path);
                }
            });
        }

        /**
         * Second Pass: Assign data attributes and create labels.
         */
        pathMap.forEach((path, startComment) => {
            let nextNode = startComment.nextSibling;
            
            while (nextNode && nextNode.nodeType !== 1) {
                if (nextNode.nodeType === 8 && nextNode.nodeValue.includes('blade-tracer-end')) {
                    break;
                }

                nextNode = nextNode.nextSibling;
            }
            
            if (nextNode && nextNode.nodeType === 1) {
                nextNode.setAttribute('data-blade-path', path);
                tracedElements.set(nextNode, path);

                const label = createLabel(nextNode, path);
                nextNode.appendChild(label);
            }
        });

        /**
         * Observe DOM changes to restore labels if removed.
         */
        const observer = new MutationObserver(function(mutations) {
            clearTimeout(observer.timeout);

            observer.timeout = setTimeout(ensureAllLabels, 50);
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: false
        });

        /**
         * Periodically check and restore labels (for aggressive Vue.js replacements).
         */
        setInterval(ensureAllLabels, 500);

        /**
         * Also check on various events to ensure labels are present.
         */
        window.addEventListener('load', ensureAllLabels);
        
        document.addEventListener('DOMContentLoaded', ensureAllLabels);
    });
</script>
