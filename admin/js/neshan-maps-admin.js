(function ($) {
    'use strict';

    $(function () {
        var formEl, /** @type NeshanMapMaker */maker;

        formEl = $('#neshan_form');

        maker = new NeshanMapMaker({
            target: 'neshan_map',
            messages: {
                api_key_help: neshan_options.translate.api_key_help
            },
            onChange: function (key, value) {
                if (key === 'key') {
                    if (!value || value === '') {
                        this.switchEmptyApiKeyWarning(true);
                    }
                }
            },
            onError: function () {
            }
        }, formEl);

        formEl.on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;

            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#neshan_form_box_save').on('click', function (evt) {
            var result = {}, isValid = true;

            evt.preventDefault();

            formEl.find('input[type=text], input[type=hidden], input[type=radio]:checked').each(function () {
                var el = $(this), val, id;

                val = $.trim(el.val());
                id = el.attr('id') || el.attr('name');

                val === '' && (val = null);

                if (el.attr('type') === 'radio') {
                    id = el.attr('name');
                }

                result[id] = val;

                if (val === null) {
                    isValid = false;
                }
            });

            if (isValid === true) {
                result.action = 'save_neshan_map';

                maker.lock();

                $('#neshan_form_alert_error').addClass('hidden');

                $.ajax({
                    url: ajaxurl,
                    data: result,
                    method: 'POST'
                }).done(function (res) {
                    var el;
                    if (!res || !res.id || res.id === 0) {
                        return onErrorSaving();
                    }

                    el = $('#neshan_form_alert_success');

                    el.find('pre').html('[neshan-map id="' + res.id + '"]');
                    el.removeClass('hidden');
                    formEl.parent().find('.alert-dismissible').remove();
                    formEl.remove();
                }).always(function () {
                    $('#neshan_form_alert_fill_all_fields').remove();
                    maker.unlock();
                }).fail(function () {
                    onErrorSaving();
                });
            }
        });

        function onErrorSaving() {
            $('#neshan_form_alert_error').removeClass('hidden');
        }
    });

})(jQuery);
