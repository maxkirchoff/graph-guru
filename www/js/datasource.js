/*
 * Fuel UX Data components - static data source
 * https://github.com/ExactTarget/fuelux-data
 *
 * Copyright (c) 2012 ExactTarget
 * Licensed under the MIT license.
 */

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['underscore'], factory);
    } else {
        root.StaticDataSource = factory();
    }
}(this, function () {

    var StaticDataSource = function (options) {
        this._formatter = options.formatter;
        this._columns = options.columns;
        this._delay = options.delay || 0;
        this._data = options.data;
    };

    StaticDataSource.prototype = {

        columns: function () {
            return this._columns;
        },

        data: function (options, callback) {
            var self = this;

            setTimeout(function () {
                var data = $.extend(true, [], self._data);

                // SEARCHING
                if (options.search) {
                    data = _.filter(data, function (item) {
                        var match = false;

                        _.each(item, function (prop) {
                            if (_.isString(prop) || _.isFinite(prop)) {
                                if (prop.toString().toLowerCase().indexOf(options.search.toLowerCase()) !== -1) match = true;
                            }
                        });

                        return match;
                    });
                }

                // FILTERING
                if (options.filter) {
                    data = _.filter(data, function (item) {
                        switch(options.filter.value) {
                            case 'video':
                                if(item.type === 'video') return true;
                                break;
                            case 'photo':
                                if(item.type === 'photo') return true;
                                break;
                            case 'status':
                                if(item.type === 'status') return true;
                                break;
                            case 'link':
                                if(item.type === 'link') return true;
                                break;
                            default:
                                return true;
                                break;
                        }
                    });
                }

                var count = data.length;

                // SORTING
                if (options.sortProperty) {
                    data = _.sortBy(data, options.sortProperty);
                    if (options.sortDirection === 'desc') data.reverse();
                }

                // PAGING
                var startIndex = options.pageIndex * options.pageSize;
                var endIndex = startIndex + options.pageSize;
                var end = (endIndex > count) ? count : endIndex;
                var pages = Math.ceil(count / options.pageSize);
                var page = options.pageIndex + 1;
                var start = startIndex + 1;

                data = data.slice(startIndex, endIndex);

                if (self._formatter) self._formatter(data);

                callback({ data: data, start: start, end: end, count: count, pages: pages, page: page });

            }, this._delay)
        }
    };

    return StaticDataSource;
}));