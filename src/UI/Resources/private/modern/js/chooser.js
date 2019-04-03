/**
 * Available options
 *
 * // vary short form
 * data-chooser: url | url_with_@value_holder_if_chained
 *
 * // short form
 * data-chooser: {
 *     url: url | url_with_@value_holder_if_chained
 * }
 *
 * data-chooser: {
 *     chains: [ array_of_fields_depend_on_this_field ],        // Optional
 *     depend: id_of_depend_of_field                            // Optional
 *     depend_msg: alert_message_when_depend_of_has_no_value    // Optional
 *     remote: url_with_@value_holder_if_chained                // short form Optional
 *     remote: {                                                // long form
 *         url: url_with_@value_holder
 *         data: {key: data_pass_to_remote}
 *         choice_label: some_callback_js                       // Optional for parse label display template
 *         value: response_data_record_value_field              // Default `id`
 *         text: response_data_record_label_field               // Default `name`
 *         min_query: typing min query                          // Default `2`
 *         query: global js fn                                  // Optional callback to prepare query string
 *         query_search_key: ?keyword=xx                        // Default `keyword`
 *         grid: short hand for query = 'grid_criteria_query'   // Options string for `query_search_key` or just true
 *     },
 *     listeners: {
 *         change: callbackName
 *     },
 *     others: @see http://selectize.github.io/selectize.js/
 * }
 *
 * Note: some_callback_js need to add global script
 *     <script>
 *         window.some_callback_js = function(data) {
 *             return data.x + ' abc ' + data.y;
 *         }
 *     </script>
 */
window.ChangChooser = function (selector, scope) {
    $(selector, scope).each(function () {
        var $target, current, chains, loader, options, id, remote, depend, depend_msg, listeners;

        $target = $(this);

        // already setup
        if ($target.data('selectize')) {
            return;
        }

        options = $target.data('chooser') || {};

        // just configured string assume to be url.
        if (typeof options === 'string') {
            options = {
                url: options
            };
        }

        if ($target.data('tags')) {
            options.create = function (input) {
                if (input.length < 3) {
                    return false;
                }

                return {
                    value: input,
                    text: input
                }
            };
        }

        // render option
        if (options.render && typeof options.render.option === 'string') {
            options.render.option = window[options.render.option];
        }

        // render items
        if (options.render && typeof options.render.item === 'string') {
            options.render.item = window[options.render.item];
        }

        // disable typing for filter
        if (options.filter_disabled) {
            options.score = function () {
                return function () {
                    return 1;
                };
            }; //https://stackoverflow.com/a/35920145
            delete options.filter_disabled;
        }

        // vary short config
        if (options.url) {
            options.remote = {
                url: options.url
            };

            delete options.url;
        }

        id = $target.attr('id');

        // read chain option then remove from standard select option
        if (options.chains) {
            chains = options.chains;
            delete options.chains;
        }

        // this chain depend on other
        if (options.depend) {
            depend = options.depend;
            delete options.depend;
        }

        // alert message when depend field has no value
        if (options.depend_msg) {
            depend_msg = options.depend_msg;
            delete options.depend_msg;
        }

        // listeners
        if (options.listeners) {
            listeners = options.listeners;
            delete options.listeners;
        }

        if (options.remote) {
            // short hand config remote only with url (no other options)
            if (typeof options.remote === 'string') {
                options.remote = {
                    url: options.remote
                };
            }

            remote = options.remote;
            remote.data = remote.data || {};

            delete options.remote;

            loader = function (callback) {
                var me = this, opt = me.__remote__, url = opt.uri || opt.url;

                if (me.__depend__ && /(@|%40)value/.test(url)) {
                    var val = $('#' + me.__depend__).val();

                    if (val) {
                        url = url.replace(/(@|%40)value/g, val);
                    } else {
                        $.notifier.alert(me.__depend_msg__ || "ยังไม่สามารถเลือกตัวเลือกนี้ได้ในตอนนี้");
                        return callback();
                    }

                }

                if (!opt.value) {
                    opt.value = 'id';
                }

                if (!opt.text) {
                    opt.text = 'name';
                }

                if (typeof options.labelTextField === 'string') {
                    opt.text = options.labelTextField;
                }

                if (typeof opt.clearOnLoad === 'undefined') {
                    opt.clearOnLoad = true;
                }

                if (me.$wrapper.hasClass('multi')) {
                    opt.clearOnLoad = false;
                }

                if (opt.clearOnLoad) {
                    me.clearOptions();
                }

                if (opt.clearOnLoad) {
                    //me.disable();
                }

                return $.ajax({
                    type: 'GET',
                    url: url,
                    data: opt.data,
                    complete: function () {
                        me.enable()
                    },
                    error: function () {
                        callback()
                    },
                    success: function (res) {
                        var data, items;

                        if (res._embedded) {
                            data = res._embedded.items;
                        } else {
                            data = res;
                        }

                        items = [];

                        $.each(data, function (i, it) {
                            return items.push({
                                value: opt['choice_label'] == undefined ? it[opt.value] : window[opt['choice_label']](it),
                                text: it[opt.text],
                                item: it
                            });
                        });

                        // fix it's not open after loaded
                        setTimeout(function () {
                            me.blur();
                            me.focus();
                        }, 1);

                        return callback(items);
                    }
                });
            };

            options.load = function (query, callback) {
                if (query.length < (remote.min_query || 2)) {
                    return callback();
                }

                if (this.__remote__.grid) {
                    this.__remote__.query = 'grid_criteria_query';

                    if (typeof this.__remote__.grid === 'string') {
                        this.__remote__.query_search_key = this.__remote__.grid;
                    }
                }

                var searchKey = this.__remote__.query_search_key || 'keyword';

                var grid_criteria_query = function (remote, query) {
                    var criteria = {};

                    criteria[searchKey] = {
                        type: 'contains',
                        value: query
                    };

                    remote['data'] = {
                        criteria: criteria
                    };
                };

                if (this.__remote__.query) {
                    if ('grid_criteria_query' === this.__remote__.query) {
                        grid_criteria_query(this.__remote__, query);
                    } else {
                        window[this.__remote__.query](this.__remote__, query);
                    }
                } else {
                    this.__remote__.data[searchKey] = query;
                }

                return loader.call(this, callback);
            };
        }

        // https://github.com/selectize/selectize.js/issues/239
        options.onInitialize = function () {
            var s = this;
            this.revertSettings.$children.each(function () {
                $.extend(s.options[this.value], $(this).data());
            });

            var vals = s.$input.val();

            if (!vals || !remote) {
                return;
            }

            s.load(function (callback) {
                var criteria = {};
                // Need to config grid.filters.[remote.value]
                criteria[remote.value] = {
                    type: 'in',
                    value: vals
                };

                this.__remote__ = remote;
                this.__remote__.data = {
                    criteria: criteria
                };

                return loader.call(this, function (items) {
                    setTimeout(function () {
                        s.clear(true);
                        s.clearOptions();
                        callback(items);

                        for (var i in items) {
                            s.addItem(items[i].value);
                        }
                    }, 1);
                });
            });
        };

        $target.selectize(options);

        current = $target.data('selectize');
        current.__depend__ = depend;
        current.__depend_msg__ = depend_msg;
        current.__remote__ = remote;
        current.__loader__ = loader;
        current.__chains__ = typeof chains === 'string' ? [chains] : chains;

        if (listeners) {
            var listener;
            for (listener in listeners) {
                current.on(listener, window[listeners[listener]]);
            }
        }

        if (chains) {
            current.on('change', function (value) {
                var chaining, j, len, ref1;

                ref1 = this.__chains__;

                for (j = 0, len = ref1.length; j < len; j++) {
                    chaining = $('#' + ref1[j]).data('selectize');

                    remote = chaining.__remote__;

                    if (remote) {
                        remote.uri = remote.url.replace(/(@|%40)value/g, value);

                        if (value) {
                            chaining.load(chaining.__loader__);
                        } else {
                            chaining.clearOptions();
                        }
                    } else {
                        console.warn('no remote configured for chain');
                    }
                }
            });
        }
    });
};
