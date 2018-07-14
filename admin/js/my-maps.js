(function ($, undefined) {
    'use strict';

    $(function () {
        $('#neshan_my_maps_wrapper').on('click', '.trash a:not(.locked)', function (evt) {
            var el;

            evt.preventDefault();

            if (!window.confirm(neshan_options.translate.delete_confirm_message)) {
                return;
            }

            el = $(this);

            el.addClass('locked');

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'delete_neshan_map',
                    token: el.attr('rel'),
                    id: el.attr('rev')
                },
                method: 'POST'
            }).done(function (res) {
                if (res && res.status === 'OK') {
                    el.parents('tr').first().remove();
                } else {
                    onDeleteError();
                }
            }).always(function () {
                el.removeClass('locked');
            }).fail(function () {
                onDeleteError();
            });
        });

        function onDeleteError() {
            alert('An error occurred. Please try again.');
        }
    });
})(jQuery);