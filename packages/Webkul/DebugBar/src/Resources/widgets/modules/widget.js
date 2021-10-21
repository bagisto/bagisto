(function($) {

    var csscls = PhpDebugBar.utils.makecsscls('phpdebugbar-widgets-');

    /**
     * Widget for the displaying all modules
     *
     * Options:
     *  - data
     */
    var ModulesWidget = PhpDebugBar.Widgets.ModulesWidget = PhpDebugBar.Widget.extend({
        className: csscls('modules'),
        
        render: function() {
            this.$list = new PhpDebugBar.Widgets.ListWidget({ itemRenderer: function(li, module) {
                $('<strong />').addClass(csscls('module')).addClass(csscls('name')).text(module.name).appendTo(li);

                $('<span title="Queries" />').addClass('phpdebugbar-fa phpdebugbar-widgets-queries').text(module.queries.length).appendTo(li);

                $('<span title="Views" />').addClass('phpdebugbar-fa phpdebugbar-widgets-views').text(module.views.length).appendTo(li);

                $('<span title="Models" />').addClass('phpdebugbar-fa phpdebugbar-widgets-models').text(module.models.length).appendTo(li);

                var table = $('<table><tr><th colspan="2">Details</th></tr></table>').addClass(csscls('params')).appendTo(li);

                if (module.models && module.models.length) {
                    table.append(function () {
                        var $name = $('<td />').addClass(csscls('name')).html('Models');
                        var $value = $('<td />').addClass(csscls('value'));
                        var $span = $('<span />').addClass('phpdebugbar-text-muted');

                        var index = 0;
                        var $models = new PhpDebugBar.Widgets.ListWidget({ itemRenderer: function(li, binding) {
                            var $index = $span.clone().text(index++ + '.');
                            li.append($index, '&nbsp;', binding).removeClass(csscls('list-item')).addClass(csscls('table-list-item'));
                        }});

                        $models.set('data', module.models);

                        $models.$el
                            .removeClass(csscls('list'))
                            .addClass(csscls('table-list'))
                            .appendTo($value);

                        return $('<tr />').append($name, $value);
                    });
                }

                if (module.views && module.views.length) {
                    table.append(function () {
                        var $name = $('<td />').addClass(csscls('name')).html('Views');
                        var $value = $('<td />').addClass(csscls('value'));

                        var $views = new PhpDebugBar.Widgets.ListWidget({ itemRenderer: function(li, view) {
                            li.append(view).removeClass(csscls('list-item')).addClass(csscls('table-list-item'));
                        }});

                        $views.set('data', module.views);
                        $views.$el
                            .removeClass(csscls('list'))
                            .addClass(csscls('table-list'))
                            .appendTo($value);

                        return $('<tr />').append($name, $value);
                    });
                }

                if (module.queries && module.queries.length) {
                    table.append(function () {
                        var $name = $('<td />').addClass(csscls('name')).html('Queries');
                        var $value = $('<td />').addClass(csscls('value'));

                        var $queries = new PhpDebugBar.Widgets.ListWidget({ itemRenderer: function(li, query) {
                            $('<code />').addClass(csscls('sql')).html(PhpDebugBar.Widgets.highlight(query.sql, 'sql')).appendTo(li);

                            $('<span title="Connection" />').addClass(csscls('database')).text(query.connection).appendTo(li);

                            $('<span title="Duration" />').addClass(csscls('duration')).text(query.duration_str).appendTo(li);

                        }});

                        $queries.set('data', module.queries);
                        $queries.$el
                            .removeClass(csscls('list'))
                            .addClass(csscls('table-list'))
                            .appendTo($value);

                        return $('<tr />').append($name, $value);
                    });
                }

                li.css('cursor', 'pointer').click(function() {
                    if (table.is(':visible')) {
                        table.hide();
                    } else {
                        table.show();
                    }
                });
            }});

            this.$list.$el.appendTo(this.$el);

            this.bindAttr('data', function(data) {
                this.$list.set('data', data.modules);
            });
        }
    });
})(PhpDebugBar.$);