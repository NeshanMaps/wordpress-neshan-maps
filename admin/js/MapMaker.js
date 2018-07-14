var NeshanMapMaker;

(function ($, undefined) {
    // var _toolsBaseUrl = 'http://localhost/rajman/NeshanWebSDK/v1/tools/';
    var _toolsBaseUrl = 'https://static.neshan.org/api/web/v1/tools/';

    /**
     *
     * @param options
     * @param formEl
     * @constructor
     */
    NeshanMapMaker = function (options, formEl) {
        var self = this, timeoutId;

        this.messages = options.messages || {};

        if (!this.messages.api_key_help) {
            this.messages.api_key_help = 'لطفا ابتدا <span class="ltr">API Key</span> مرتبط با نقشه را که از پنل توسعه‌دهندگان دریافت کرده‌اید در فیلد زیر درج نمایید.';
        }

        /** @type {{maptype: String, zoom: Number|String, lat: Number|String, lng: Number|String, width: Number|String, height: Number|String}} */
        this.mapOptions = {
            target: options.target,
            maptype: options.maptype || 'neshan',
            zoom: options.zoom || 14,
            minZoom: options.minZoom || 5,
            maxZoom: options.maxZoom || 19,
            width: options.width || '100%',
            height: options.height || 400,
            minSize: options.minSize || 50,
            maxSize: options.maxSize || 1200,
            lat: options.lat || 35.700538,
            lng: options.lng || 51.337907
        };

        this.formEl = formEl;

        formEl.find('#neshan_maptype_switcher_wrapper input[type=radio]:checked').prev().addClass('active');

        formEl.find('input[type=text], input[type=radio]:checked').each(function () {
            var el = $(this), val, id;

            if (el.attr('type') === 'radio') {
                self.mapOptions[el.attr('name')] = el.val();
            } else if (val = $.trim(el.val())) {
                id = el.attr('id');

                if (id === 'key') {
                    options.mapApiKey = val;
                } else {
                    self.mapOptions[id] = val;
                }
            }
        });

        this.onChange = options.onChange;
        this.onError = options.onError;

        /** @type {String} */
        this.key = null;

        /** @type {ol.Map} **/
        this.map = null;

        /** @type {jQuery|HTMLElement} */
        this.mapTargetEl = $('#' + this.mapOptions.target);

        this.formEl.on('keyup', '.neshan_dynamic_changer', function (evt) {
            var value, id, el = $(this);

            if (evt.keyCode > 36 && evt.keyCode < 41) {
                return;
            }

            if (timeoutId) {
                clearTimeout(timeoutId);
            }

            id = $(this).attr('id');

            if (id === 'lat' || id === 'lng') {
                value = parseFloat(el.val());
            } else if (id !== 'key') {
                value = parseInt(el.val());
            } else {
                value = $.trim(el.val());
            }

            if (id === 'key' && self.key === value) {
                return;
            }

            if (id !== 'key' && isNaN(value)) {
                el.addClass('error');

                if (id === 'lat' || id === 'lng') {
                    $('#lat_lng_error').removeClass('hidden');
                }

                self.onError.call(self);
                return;
            }

            /*if ((id === 'width' || id === 'height') && (parseInt(value) > self.mapOptions.maxSize || parseInt(value) < self.mapOptions.minSize)) {
                el.addClass('error');

                self.onError.call(self);
                return;
            }*/

            if (id === 'zoom' && (value > self.mapOptions.maxZoom || value < self.mapOptions.minZoom)) {
                el.addClass('error');

                self.onError.call(self);
                return;
            }

            el.removeClass('error');

            if (id === 'lat' || id === 'lng') {
                self.formEl.find('#lat_lng_error').addClass('hidden');
            }

            if (id === 'width' || id === 'height') {
                value = el.val();
            }

            timeoutId = setTimeout(function () {
                self.set(id, value);
            }, 500);
        });

        this.formEl.on('click', '#neshan_maptype_switcher_wrapper label:not(.active)', function (evt) {
            var el = $(this);

            el.parents('#neshan_maptype_switcher_wrapper').find('.active').removeClass('active');
            el.addClass('active');

            self.set('maptype', el.attr('rel'));
        });

        this.formEl.find('#neshan_maptype_switcher_wrapper label').each(function () {
            var el = $(this), type = el.attr('rel');

            $('<img />').attr({
                src: _toolsBaseUrl + 'assets/images/map-types-' + type + '.png',
                alt: type
            }).appendTo(el);
        });

        options.mapApiKey ? this.setApiKey(options.mapApiKey) : this.switchEmptyApiKeyWarning(true);

        this.updateForm('width', this.mapTargetEl[0].style.width.indexOf('%') > 0 ? this.mapTargetEl[0].style.width : this.mapTargetEl.outerWidth());
        this.updateForm('height', this.mapTargetEl[0].style.height.indexOf('%') > 0 ? this.mapTargetEl[0].style.height : this.mapTargetEl.outerHeight());

        this.unlock();
    };

    NeshanMapMaker.prototype.set = function (key, value) {
        this.mapOptions[key] = value;

        if (key === 'width' || key === 'height') {
            this.mapTargetEl.css(key, value);

            this.mapOptions.width = this.mapTargetEl.width();
            this.mapOptions.height = this.mapTargetEl.height();

            if (this.map) {
                this.map.setSize([this.mapOptions.width, this.mapOptions.height]);
                this.map.renderSync();
            }
        } else if (key === 'lat' || key === 'lng') {
            this.map && this.map.getView().setCenter(ol.proj.fromLonLat([parseFloat(this.mapOptions.lng), parseFloat(this.mapOptions.lat)]));

            key = 'center';
            value = [this.mapOptions.lng, this.mapOptions.lat];
        } else if (key === 'zoom') {
            this.map && this.map.getView().setZoom(parseInt(this.mapOptions.zoom));
        } else if (key === 'maptype') {
            this.map && this.map.setMapType(this.mapOptions.maptype);
        } else if (key === 'key') {
            this.setApiKey(value);
        }

        this.onChange.apply(this, [key, value]);

        return this;
    };


    NeshanMapMaker.prototype.updateForm = function (key, value) {
        if (key === 'center') {
            this.formEl.find('#lng').val(value[0]);
            this.formEl.find('#lat').val(value[1]);

            this.mapOptions.lng = value[0];
            this.mapOptions.lat = value[1];
        } else {
            this.formEl.find('#' + key).val(value);

            this.mapOptions[key] = value;
        }

        this.onChange.apply(this, [key, value]);
    };

    NeshanMapMaker.prototype.setApiKey = function (key) {
        key = $.trim(key);

        if (key && key !== '' && key.length > 8) {
            this.key = key;

            this.createMap();
        }

        return this;
    };


    NeshanMapMaker.prototype.createMap = function () {
        var self = this;

        if (this.map) {
            $(this.map.getViewport()).remove();
        }

        this.switchEmptyApiKeyWarning(false);

        this.mapTargetEl.css({
            width: this.mapOptions.width,
            height: this.mapOptions.height
        });

        this.map = new ol.Map({
            target: this.mapOptions.target,
            maptype: this.mapOptions.maptype,
            key: this.key,
            nowarn: true,
            view: new ol.View({
                center: ol.proj.fromLonLat([parseFloat(this.mapOptions.lng), parseFloat(this.mapOptions.lat)]),
                zoom: this.mapOptions.zoom,
                minZoom: this.mapOptions.minZoom,
                maxZoom: this.mapOptions.maxZoom,
                extent: [4891969.8103, 2856910.3692, 7051774.4815, 4847942.0820]
            }),
            interactions: ol.interaction.defaults({
                altShiftDragRotate: true,
                pinchRotate: false,
                shiftDragZoom: false
            })
        });

        this.map.on('key:failed', function (evt) {
            self.switchEmptyApiKeyWarning(true, evt.target.message);
        });

        self.map.on('moveend', function () {
            self.map && self.updateForm('zoom', parseInt(self.map.getView().getZoom()));
        });

        self.map.getView().on('change:center', function () {
            var center;

            if (!self.map) {
                return;
            }

            center = self.map.getView().getCenter();

            if (center[0] < 0 || center[1] < 0) {
                return;
            }

            center = ol.proj.toLonLat(center);

            self.updateForm('center', [parseFloat(center[0]).toFixed(6), parseFloat(center[1]).toFixed(6)]);
        });

        self.map.getView().dispatchEvent('change:center');

        $('<div id="neshan_map_center_marker" class="ol-unselectable" />').appendTo(this.mapTargetEl.find('.ol-overlaycontainer-stopevent').first());

        return this;
    };

    NeshanMapMaker.prototype.switchEmptyApiKeyWarning = function (showFlag, extra) {
        var message = '';

        if (showFlag) {
            if (this.map) {
                $(this.map.getViewport()).remove();
                this.map = null;
            }

            this.mapTargetEl.addClass('api_key_warning');

            message = this.messages.api_key_help;

            if (extra) {
                message = '<div class="ltr">' + extra + '</div><hr/>' + message;
            }

            $('<div id="api_key_warning_message" />').html(message).appendTo(this.mapTargetEl);
        } else {
            this.mapTargetEl.removeClass('api_key_warning');
            this.mapTargetEl.find('#api_key_warning_message').remove();
        }
    };

    NeshanMapMaker.prototype.lock = function () {
        $('<div class="neshan_locker" />').appendTo(this.formEl);
    };

    NeshanMapMaker.prototype.unlock = function () {
        $('.neshan_locker').remove();
    };
})(jQuery);