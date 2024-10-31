<?php

if (!defined('ABSPATH')) {
    exit;
}

if (isset($_GET['custom_post_type'])) {

    $post_type_slug = sanitize_text_field($_GET['custom_post_type']);
    $page = get_current_screen()->base;

    $posts = get_posts([
        'post_type' => 'ptmsf_pt_list',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    foreach ($posts as $post) {
        $data = get_post_meta($post->ID, 'data')[0];
        if ($data['transformed_name'] == $post_type_slug) {
            ?>
            <div class="ptmsf-main-container">
                <div class="top-container">
                    <h1><?php echo esc_html($data['menu_name']) ?></h1>
                </div>
                <div class="plugin-content">
                    <div class="form-container">
                        <form id="update-custom-post-type-form">
                            <div class="input-field">
                                <label for="name"><?php echo esc_html__('Name*', 'post-type-management') ?></label>
                                <input type="text" id="name" name="name" value="<?php echo esc_html($data['name']) ?>"
                                       required>
                                <small class="validation-error" id="name_error"><?php echo esc_html__('The "Name" field should not exceed 35 
                                characters.', 'post-type-management') ?></small>
                            </div>
                            <div class="input-field">
                                <label for="singular_name"><?php echo esc_html__('Singular Name*', 'post-type-management') ?></label>
                                <input type="text" id="singular_name" name="singular_name"
                                       value="<?php echo esc_html($data['singular_name']) ?>" required>
                                <small class="validation-error" id="singular_name_error"><?php echo esc_html__('The "Singular Name" field 
                                should not exceed 35 characters.', 'post-type-management') ?></small>
                            </div>
                            <div class="input-field">
                                <label for="slug"><?php echo esc_html__('Slug', 'post-type-management') ?></label>
                                <input type="text" id="slug" name="slug"
                                       value="<?php echo esc_html($data['transformed_name']) ?>" readonly>
                                <div class="validation-info">
                                    <div class="info-icon"><p>i</p></div>
                                    <p class="info-text"><?php echo esc_html__("You can't change Post Type slug", 'post-type-management') ?></p>
                                </div>
                            </div>
                            <?php

                            $auto = '';

                            if ($data['auto']) {
                                $auto = 'checked';
                            }

                            ?>
                            <div class="input-field">
                                <input type="checkbox" id="auto" name="auto" <?php echo esc_html($auto) ?>><?php echo esc_html__('Automatically 
                                set post type labels', 'post-type-management') ?><br>
                            </div>
                            <h3 class="advanced-title"><?php echo esc_html__('Advanced', 'post-type-management') ?>
                                <button id="toggle-advanced" class="arrow-top">&#9660;</button>
                            </h3>

                            <div class="advanced">
                                <div class="labels">
                                    <?php

                                    $post_type = (object)[
                                        'name' => 'post',
                                        'labels' => (object)[],
                                        'hierarchical' => false
                                    ];

                                    $labels = $this->set_auto_fields($data);

                                    foreach ($data['fields'] as $key => $field) {

                                        $label = ucwords(str_replace('_', ' ', $key));

                                        $class = 'edited';

                                        if (empty($field)) {
                                            $attr = 'placeholder=';
                                            $value = $labels->$key;
                                            $class = '';

                                        } else {
                                            $attr = 'value=';
                                            $value = $field;
                                        }

                                        ?>
                                        <div class="input-field">
                                            <label for="<?php echo esc_html($key) ?>"
                                                   class="<?php echo esc_html($class) ?>"><?php echo esc_html($label) ?></label>
                                            <input type="text" id="<?php echo esc_html($key) ?>"
                                                   name="labels[<?php echo esc_html($key) ?>]" <?php echo esc_html($attr) . '"' . esc_html($value) . '"' ?>>
                                            <small class="validation-error" id="<?php echo esc_html($key) . '_error' ?>">
                                                <?php
                                                printf(
                                                    // translators: %s is the label of the field being validated
                                                    esc_html__('The "%s" field should not exceed 35 characters.', 'post-type-management'),
                                                    esc_html($label)
                                                );
                                                ?>
                                            </small>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="bool-params">
                                    <label><?php echo esc_html__('Parameters:', 'post-type-management') ?></label><br>
                                    <?php foreach ($this->bool_params as $param => $value) {
                                        if ($data[$param]) {
                                            $checked = 'checked';
                                        } else {
                                            $checked = '';
                                        }

                                        $label = ucfirst(str_replace('_', ' ', $param))
                                        ?>
                                        <div class="input-field">
                                            <input type="checkbox" id="<?php echo esc_html($param) ?>"
                                                   name="<?php echo esc_html($param) ?>"
                                                   value="<?php echo esc_html($param) ?>" <?php echo esc_html($checked) ?>><?php echo esc_html($label) ?>
                                            <br>
                                        </div>
                                    <?php } ?>
                                    <div class="input-field">
                                        <input type="number" id="posts_per_page" name="posts_per_page"
                                                value="<?php echo esc_html($data['posts_per_page']) ?>" min="5" max="50">
                                                <?php echo esc_html__('Posts per page', 'post-type-management') ?><br>
                                    </div>
                                </div>

                                <div class="supp-tax">
                                    <div class="input-field supports">
                                        <label><?php echo esc_html__('Supports:', 'post-type-management') ?></label><br>
                                        <?php foreach ($this->supports as $key => $item) {
                                            $checked = '';
                                            $dash_item = strtolower(str_replace(' ', '-', $key));
                                            $lower_dash_item = strtolower(str_replace(' ', '_', $key));

                                            if (in_array($dash_item, $data['supports'])) {
                                                $checked = 'checked';
                                            } ?>
                                            <div class="input-inner">
                                                <input type="checkbox"
                                                       id="supports_<?php echo esc_html($lower_dash_item) ?>"
                                                       name="supports[]"
                                                       value="<?php echo esc_html($dash_item) ?>" <?php echo esc_html($checked) ?>> <?php echo esc_html($key) ?>
                                                <br>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="input-field supports">
                                        <label><?php echo esc_html__('Taxonomies:', 'post-type-management') ?></label><br>
                                        <?php foreach ($this->taxonomies as $key => $item) {
                                            $checked = '';
                                            $lower_dash_item = strtolower(str_replace(' ', '_', $key));

                                            if (in_array($lower_dash_item, $data['taxonomies'])) {
                                                $checked = 'checked';
                                            } ?>
                                            <div class="input-inner">
                                                <input type="checkbox"
                                                       id="taxonomies_<?php echo esc_html($lower_dash_item) ?>"
                                                       name="taxonomies[]"
                                                       value="<?php echo esc_html($lower_dash_item) ?>" <?php echo esc_html($checked) ?>> <?php echo esc_html($key) ?>
                                                <br>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="dashicons-container">
                                    <div class="input-inner">
                                        <label for="dashicon"><?php echo esc_html__('Dashicon', 'post-type-management') ?></label>
                                        <input type="text" id="dashicon" name="dashicon"
                                               value="<?php echo esc_html($data['dashicon']) ?>" readonly>
                                    </div>
                                    <div class="input-inner">
                                        <button type="button" id="select-dashicon"
                                                class="elect-dashicon button-secondary"><?php echo esc_html__('Select Dashicon', 'post-type-management') ?>
                                        </button>
                                        <div id="dashicon-preview-container">
                                            <div id="dashicon-preview" class="dashicons"></div>
                                            <button type="button" id="cancel-dashicon-preview">&times;</button>
                                        </div>
                                        <small class="validation-info"></small>
                                        <div class="validation-info dashicon-validation-info">
                                            <div class="info-icon"><p>i</p></div>
                                            <p class="info-text dashicons-info-icon">
                                                <?php echo esc_html__('Some WordPress dashicons can be missing', 'post-type-management') ?>
                                            </p>
                                        </div>
                                        <div id="dashicon-picker-modal">
                                            <div class="dashicon-picker-modal-inner">
                                                <div id="dashicon-picker">
                                                    <?php foreach ($this->dashicons as $dashicon): ?>
                                                        <span class="dashicons <?php echo esc_html($dashicon) ?>"
                                                              data-dashicon="<?php echo esc_html($dashicon) ?>"></span>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button type="button" id="close-dashicon-picker"
                                                        class="button-secondary"><?php echo esc_html__('Close', 'post-type-management') ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-inner">
                                        <a href="#" class="upload_image_button button button-secondary"><?php echo esc_html__('Upload Image', 'post-type-management') ?></a>
                                        <input type="hidden" name="custom_dashicon" id="custom_dashicon"
                                               value="<?php echo esc_html($data['custom_dashicon']) ?>"/>
                                        <div class="preview-container">
                                            <img id="custom_dashicon_preview" src="" alt="Preview"/>
                                            <button type="button" id="cancel_image_preview" class="cancel-image-preview">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-field">
                                <button type="submit" class="button-primary"><?php echo esc_html__('Save and Update', 'post-type-management') ?></button>
                                <a href="<?php echo esc_html(admin_url('tools.php?page=post-type-management')) ?>"
                                   class="button-secondary"><?php echo esc_html__('Go back to settings page', 'post-type-management') ?></a>
                                <button name="post_id" value="<?php echo esc_html($post->ID) ?>"
                                        class="delete-post-type-ptmsf button-secondary button-delete-ptmsf"><?php echo esc_html__('Delete Post Type', 'post-type-management') ?>
                                </button>
                                <input type="hidden" name="page" value="<?php echo esc_html($page) ?>" class="hidden-input">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php }
    }
} else { ?>
    <div class="ptmsf-main-container">
        <div class="top-container not-found-container">
            <h1><?php echo esc_html__('404', 'post-type-management') ?></h1>
        </div>
        <p><?php echo esc_html__('Page you are trying to access is not available. Check the link and try again.', 'post-type-management') ?></p>
        <a href="<?php echo esc_html(admin_url('tools.php?page=post-type-management')) ?>"
           class="button-secondary"><?php echo esc_html__('Go back to settings page', 'post-type-management') ?></a>
    </div>
<?php } ?>

