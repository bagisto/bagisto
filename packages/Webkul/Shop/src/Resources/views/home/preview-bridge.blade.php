{{-- Talks to the appearance editor that frames this page. Preview only. --}}
@pushOnce('styles')
    <style>
        /**
         * The section being edited. Everything else is pushed back rather than the
         * section merely being outlined, so it stays obvious on a busy storefront.
         */
        [data-section-id].is-editing {
            position: relative;
            z-index: 1;
            outline: 3px solid #3b82f6;
            outline-offset: -3px;
            border-radius: 4px;
            box-shadow: 0 0 0 9999px rgba(17, 24, 39, .45);
            transition: outline-color .2s ease-in-out;
        }

        /**
         * A section with nothing in it yet collapses to no height, leaving nothing to
         * outline or click. Given a box, it can be found and selected like any other.
         */
        [data-section-id].is-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 96px;
            margin: 12px;
            border: 2px dashed #cbd5e1;
            border-radius: 6px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
        }

        [data-section-id].is-empty::after {
            content: attr(data-section-name);
        }

        /**
         * Names the section over its top edge so the outline is not the only clue.
         */
        [data-section-id].is-editing::before {
            content: attr(data-section-name);
            position: absolute;
            top: 0;
            inset-inline-start: 0;
            z-index: 2;
            padding: 2px 10px;
            border-bottom-right-radius: 4px;
            background: #3b82f6;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            line-height: 20px;
            letter-spacing: .02em;
            pointer-events: none;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script type="module">
        /**
         * Highlight a section and bring it into view.
         */
        const focusSection = (id) => {
            document.querySelectorAll('[data-section-id]').forEach(node => {
                node.classList.remove('is-editing');
            });

            const target = document.querySelector(`[data-section-id="${id}"]`);

            if (! target) {
                return;
            }

            target.classList.add('is-editing');

            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };

        /**
         * Mark the sections that render nothing, so they can still be seen and picked.
         */
        const markEmptySections = () => {
            document.querySelectorAll('[data-section-id]').forEach(node => {
                node.classList.toggle('is-empty', node.getBoundingClientRect().height < 4);
            });
        };

        markEmptySections();

        window.addEventListener('load', markEmptySections);

        window.addEventListener('message', event => {
            if (event.origin !== window.location.origin) {
                return;
            }

            if (event.data?.type === 'section-focus') {
                focusSection(event.data.id);
            }
        });

        /**
         * Cancel navigation away from this page, which is the only one the storefront
         * allows to be framed. The navigation is intercepted rather than the click,
         * because components such as the carousel assign `window.location.href` directly.
         */
        window.navigation?.addEventListener('navigate', event => {
            if (
                ! event.cancelable
                || new URL(event.destination.url).pathname === window.location.pathname
            ) {
                return;
            }

            event.preventDefault();
        });

        /**
         * The same for browsers without the navigation api, bound on the way down so a
         * component that stops propagation cannot slip past it.
         */
        document.addEventListener('click', event => {
            if (event.target.closest('a')) {
                event.preventDefault();
            }

            const section = event.target.closest('[data-section-id]');

            if (! section) {
                return;
            }

            event.preventDefault();

            window.parent.postMessage({
                type: 'section-selected',
                id: Number(section.dataset.sectionId),
            }, window.location.origin);
        }, true);

        document.addEventListener('submit', event => event.preventDefault(), true);
    </script>
@endPushOnce
