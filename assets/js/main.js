jQuery(document).ready(function ($) {
    var $container = $('.ptmsf-main-container');

    if (sessionStorage.getItem('showNotice') === 'true') {

        $('#ptmsf-notice').show().delay(3000).fadeOut();

        sessionStorage.removeItem('showNotice');

    }

    function updatePreviewImage() {
        var imageUrl = $container.find('#custom_dashicon').val();
        if (imageUrl) {
            $container.find('#custom_dashicon_preview').attr('src', imageUrl).show();
            $container.find('#cancel_image_preview').show();
        } else {
            $container.find('#custom_dashicon_preview').hide();
            $container.find('#cancel_image_preview').hide();
        }
    }

    $container.on('click', '.upload_image_button', function (e) {
        e.preventDefault();
        var uploader = wp.media({
            title: 'Custom image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = uploader.state().get('selection').first().toJSON();
            $container.find('#custom_dashicon').val(attachment.url);
            updatePreviewImage();
        }).open();
    });

    $container.on('click', '#cancel_image_preview', function (e) {
        e.preventDefault();
        $container.find('#custom_dashicon').val('');
        updatePreviewImage();
    });

    $container.find('#select-all-ptmsf').on('change', function () {
        $container.find('.checkbox-delete-ptmsf').prop('checked', this.checked);
    });

    $container.find('.checkbox-delete-ptmsf').on('change', function () {
        if ($container.find('.checkbox-delete-ptmsf:checked').length === $container.find('.checkbox-delete-ptmsf').length) {
            $container.find('#select-all-ptmsf').prop('checked', true);
        } else {
            $container.find('#select-all-ptmsf').prop('checked', false);
        }
    });

    function initializeDashiconPreview() {
        var selectedDashicon = $container.find('#dashicon').val();
        if (selectedDashicon) {
            $container.find('#dashicon-preview').removeClass().addClass('dashicons ' + selectedDashicon);
            $container.find('#dashicon-preview-container').show();
        }
    }

    initializeDashiconPreview();

    $container.find('#select-dashicon').on('click', function () {
        $container.find('#dashicon-picker-modal').show();
    });

    $container.find('#dashicon-picker').on('click', '.dashicons', function () {
        var selectedDashicon = $(this).data('dashicon');
        $container.find('#dashicon').val(selectedDashicon);
        $container.find('#dashicon-preview').removeClass().addClass('dashicons ' + selectedDashicon);
        $container.find('#dashicon-preview-container').show();
        $container.find('#dashicon-picker-modal').hide();
    });

    $container.find('#close-dashicon-picker').on('click', function () {
        $container.find('#dashicon-picker-modal').hide();
    });

    $container.find('#cancel-dashicon-preview').on('click', function () {
        $container.find('#dashicon').val('');
        $container.find('#dashicon-preview').removeClass().addClass('dashicons');
        $container.find('#dashicon-preview-container').hide();
    });

    $container.find('#toggle-advanced').on('click', function (e) {
        e.preventDefault();
        var $advanced = $container.find('.advanced');

        if ($advanced.is(':visible')) {
            $advanced.slideUp(300, function() {
                $advanced.css('display', '');
            });
        } else {
            $advanced.css('display', 'flex').hide().slideDown(300);
        }
        $(this).toggleClass('arrow-down');
    });

    updatePreviewImage();
});