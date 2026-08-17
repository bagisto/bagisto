(function () {
    const csscls = PhpDebugBar.utils.makecsscls('phpdebugbar-widgets-');

    /**
     * Builds a row for the details table: a label on the left, and a list of
     * entries rendered by `itemRenderer` on the right.
     */
    function detailRow(label, items, itemRenderer) {
        const row = document.createElement('tr');

        const name = document.createElement('td');
        name.classList.add(csscls('name'));
        name.textContent = label;

        const value = document.createElement('td');
        value.classList.add(csscls('value'));

        const list = new PhpDebugBar.Widgets.ListWidget({ itemRenderer });
        list.set('data', items);
        list.el.classList.remove(csscls('list'));
        list.el.classList.add(csscls('table-list'));
        value.append(list.el);

        row.append(name, value);

        return row;
    }

    /**
     * Widget for displaying every module, with the models, views and queries
     * each of them accounted for during the request.
     *
     * Options:
     *  - data
     */
    class ModulesWidget extends PhpDebugBar.Widget {
        get className() {
            return csscls('modules');
        }

        render() {
            this.list = new PhpDebugBar.Widgets.ListWidget({
                itemRenderer(li, module) {
                    const name = document.createElement('strong');
                    name.classList.add(csscls('module'), csscls('name'));
                    name.textContent = module.name;
                    li.append(name);

                    for (const [title, cls, count] of [
                        ['Queries', 'phpdebugbar-widgets-queries', module.queries.length],
                        ['Views', 'phpdebugbar-widgets-views', module.views.length],
                        ['Models', 'phpdebugbar-widgets-models', module.models.length],
                    ]) {
                        const badge = document.createElement('span');
                        badge.setAttribute('title', title);
                        badge.classList.add('phpdebugbar-fa', cls);
                        badge.textContent = count;
                        li.append(badge);
                    }

                    const table = document.createElement('table');
                    table.classList.add(csscls('params'));

                    const thead = document.createElement('thead');
                    thead.innerHTML = '<tr><th colspan="2">Details</th></tr>';

                    const tbody = document.createElement('tbody');
                    table.append(thead, tbody);

                    if (module.models && module.models.length) {
                        let index = 0;

                        tbody.append(detailRow('Models', module.models, function (li, model) {
                            const position = document.createElement('span');
                            position.classList.add('phpdebugbar-text-muted');
                            position.textContent = ++index + '. ';

                            li.append(position, model);
                            li.classList.remove(csscls('list-item'));
                            li.classList.add(csscls('table-list-item'));
                        }));
                    }

                    if (module.views && module.views.length) {
                        tbody.append(detailRow('Views', module.views, function (li, view) {
                            li.append(view);
                            li.classList.remove(csscls('list-item'));
                            li.classList.add(csscls('table-list-item'));
                        }));
                    }

                    if (module.queries && module.queries.length) {
                        tbody.append(detailRow('Queries', module.queries, function (li, query) {
                            const sql = document.createElement('code');
                            sql.classList.add(csscls('sql'));
                            sql.innerHTML = PhpDebugBar.Widgets.highlight(query.sql, 'sql');
                            li.append(sql);

                            const connection = document.createElement('span');
                            connection.setAttribute('title', 'Connection');
                            connection.classList.add(csscls('database'));
                            connection.textContent = query.connection;
                            li.append(connection);

                            const duration = document.createElement('span');
                            duration.setAttribute('title', 'Duration');
                            duration.classList.add(csscls('duration'));
                            duration.textContent = query.duration_str;
                            li.append(duration);
                        }));
                    }

                    li.append(table);

                    /**
                     * The details start closed; the row itself is the toggle, so the
                     * list reads as one line per module until one is opened.
                     */
                    table.style.display = 'none';
                    li.style.cursor = 'pointer';

                    li.addEventListener('click', function () {
                        table.style.display = table.style.display === 'none' ? '' : 'none';
                    });
                },
            });

            this.el.append(this.list.el);

            this.bindAttr('data', function (data) {
                this.list.set('data', data.modules);
            });
        }
    }

    PhpDebugBar.Widgets.ModulesWidget = ModulesWidget;
})();
