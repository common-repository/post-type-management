jQuery(document).ready(function ($) {

    const boolParams = [
        'public',
        'publicly_queryable',
        'show_ui',
        'show_in_menu',
        'show_in_nav_menus',
        'show_in_rest',
        'exclude_from_search',
        'can_export',
        'query_var',
        'rewrite',
        'has_archive',
        'hierarchical'
    ];

    $('.ptmsf-main-container #custom-post-type-form').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var formData = new FormData(this);
        var isValid = true;

        $form.find('input[type="text"]').each(function () {
            var id = $(this).attr('id');
            var errorId = '#' + id + '_error';
            if (id !== 'slug') {
                isValid &= validateField('#' + id, errorId, 35);
            }
        });

        isValid &= validateSlug();

        if (!isValid) {
            return;
        }

        boolParams.forEach(function (param) {
            formData.append(param, $('#' + param).is(':checked'));
        });

        formData.append('auto', $('#auto').is(':checked'));

        formData.append('action', 'set_post_type_data');
        formData.append('nonce', post_type_management.nonce);

        $form.find('input, button').attr('disabled', true);

        $.ajax({
            type: 'POST',
            url: post_type_management.ajax_url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {

                    if (response.data.error === 'slug') {
                        alert('Post type with this slug already exists.');
                    } else if (response.data.error === 'name') {
                        alert('Post type with slug that equals name you entered already exists. Please change the name or enter a slug');
                    } else {
                        alert('Failed to create a post type.');
                    }

                    $form.find('input, button').attr('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + error);
                $form.find('input, button').attr('disabled', false);
            }
        });
    });

    $('.delete-post-type-ptmsf').on('click', function (e) {
        e.preventDefault();

        var post_id = $(this).val();
        var page = $('input[name="page"]').val();
        const userConfirmed = confirm('Are you sure you want to delete this post type?');

        if (!userConfirmed) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: post_type_management.ajax_url,
            data: {
                action: 'delete_custom_post_type',
                post_id: post_id,
                page: page,
                nonce: post_type_management.nonce
            },
            success: function (response) {
                if (response.success) {
                    if (response.data.url === 'reload') {
                        location.reload();
                    } else {
                        window.location.href = response.data.url;
                    }
                } else {
                    alert('Failed to delete post type.');
                }
            },
            error: function () {
                alert('Error occurred while deleting post type.');
            }
        });
    });

    $('.edit-post-type-ptmsf').on('click', function (e) {
        e.preventDefault();

        var post_slug = $(this).val();

        $.ajax({
            type: 'POST',
            url: post_type_management.ajax_url,
            data: {
                action: 'edit_custom_post_type',
                post_slug: post_slug,
                nonce: post_type_management.nonce
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = response.data.url;
                } else {
                    alert('Failed to delete post type.');
                }
            },
            error: function () {
                alert('Error occurred while editing post type.');
            }
        });
    });

    $('.ptmsf-main-container #update-custom-post-type-form').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var formData = new FormData(this);
        var isValid = true;

        $form.find('input[type="text"]').each(function () {
            var id = $(this).attr('id');
            var errorId = '#' + id + '_error';
            if (id !== 'slug') {
                isValid &= validateField('#' + id, errorId, 35);
            }
        });

        if (!isValid) {
            return;
        }

        boolParams.forEach(function (param) {
            formData.append(param, $('#' + param).is(':checked'));
        });

        formData.append('action', 'update_custom_post_type');
        formData.append('nonce', post_type_management.nonce);

        $form.find('input, button').attr('disabled', true);

        $.ajax({
            type: 'POST',
            url: post_type_management.ajax_url,
            data: formData,
            processData: false,
            contentType: false,
            nonce: post_type_management.nonce,
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to update post type.');
                    $form.find('input, button').attr('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + error);
                $form.find('input, button').attr('disabled', false);
            }
        });
    });

    $('.ptmsf-main-container .delete-selected-btn').on('click', function (e) {
        e.preventDefault();

        var post_ids = [];
        $('.checkbox-delete-ptmsf:checked').each(function () {
            post_ids.push($(this).val());
        });

        if (post_ids.length === 0) {
            alert('No post types selected');
            return;
        }

        const userConfirmed = confirm('Are you sure you want to delete selected post types?');

        if (!userConfirmed) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: post_type_management.ajax_url,
            data: {
                action: 'delete_selected',
                post_ids: post_ids,
                nonce: post_type_management.nonce
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to delete selected posts.');
                }
            },
            error: function () {
                alert('Error occurred while deleting selected posts.');
            }
        });
    });

    $('#post-type-table tbody').sortable({
        placeholder: 'ui-state-highlight',
        update: function (event, ui) {
            $('#save-order-btn-ptmsf').prop('disabled', false);

            $('#post-type-table tbody tr').each(function (index) {
                $(this).find('.order-column').text(index + 1);
            });
        }
    });

    $('#save-order-btn-ptmsf').on('click', function (e) {
        e.preventDefault();

        var orderData = {};

        $('#post-type-table tbody tr').each(function (index) {
            var postId = $(this).data('post-id');
            var newOrder = index + 1;
            orderData[postId] = newOrder;
        });

        $.ajax({
            url: post_type_management.ajax_url,
            type: 'POST',
            data: {
                action: 'save_post_type_order',
                order: orderData,
                nonce: post_type_management.nonce
            },
            success: function (response) {
                if (response.success) {
                    sessionStorage.setItem('showNotice', 'true');
                    location.reload();
                } else {
                    alert('Failed to save the order.');
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });

    function validateField(fieldId, errorId, maxLength) {
        var $field = $(fieldId);
        var value = $field.val().trim();
        if (value.length > maxLength) {
            $(errorId).show();
            return false;
        } else {
            $(errorId).hide();
            return true;
        }
    }

    function validateSlug() {
        var $slug = $('.ptmsf-main-container #slug');
        var value = $slug.val().trim();
        if (value === '') {
            $('.ptmsf-main-container #slug-error').hide();
            return true;
        }
        var isValid = /^[a-z0-9_-]+$/.test(value) && value.length <= 20;
        if (!isValid) {
            $('.ptmsf-main-container #slug_error').show();
            return false;
        } else {
            $('.ptmsf-main-container #slug_error').hide();
            return true;
        }
    }

});