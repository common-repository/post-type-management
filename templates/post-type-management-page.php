<?php

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="ptmsf-main-container">
    <div class="top-container">
        <h1><?php echo esc_html__('Post Type Management', 'post-type-management') ?></h1>
        <p><?php echo esc_html__('Create and edit your post types', 'post-type-management') ?></p>
        <div id="ptmsf-notice" class="notice notice-success" style="display:none;">
            <p><?php echo esc_html__('Changes have been saved!', 'post-type-management') ?></p>
        </div>
    </div>
    <div class="plugin-content">
        <div class="post-types-container">
            <h2><?php echo esc_html__('Created Post Types', 'post-type-management') ?></h2>
            <?php
            $posts = get_posts([
                'post_type' => 'ptmsf_pt_list',
                'post_status' => 'publish',
                'numberposts' => -1,
                'order' => 'ASC'
            ]);

            $post_types = [];

            foreach ($posts as $key => $post) {
                $data = get_post_meta($post->ID, 'data', true);
                $post_types[$key] = ['id' => $post->ID, 'slug' => $data['transformed_name'], 'menu_name' => $data['menu_name'], 'menu_position' => $data['menu_position'], 'taxonomies' => $data['taxonomies'],];
            }

            uasort($post_types, function($a, $b) {
                return $a['menu_position'] <=> $b['menu_position'];
            });


            if ($posts) { ?>
                <table id="post-type-table" class="wp-list-table widefat fixed striped">
                    <thead>
                    <tr>
                        <th class="column-order"><?php echo esc_html__('Order', 'post-type-management') ?></th>
                        <th class="column-name"><?php echo esc_html__('Name', 'post-type-management') ?></th>
                        <th class="column-slug"><?php echo esc_html__('Slug', 'post-type-management') ?></th>
                        <th class="column-taxonomies"><?php echo esc_html__('Taxonomies', 'post-type-management') ?></th>
                        <th class="column-actions"><?php echo esc_html__('Actions', 'post-type-management') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $counter = 1;

                    foreach ($post_types as $key => $item) {
                        $page = get_current_screen()->base; ?>
                        <tr class="list-item" data-post-id="<?php echo esc_html($item['id']); ?>">
                            <input type="hidden" name="page" value="<?php echo esc_html($page) ?>" class="hidden-input">
                            <td class="order-column"><?php echo esc_html($counter); ?></td>
                            <td class="order-name"><?php echo esc_html($item['menu_name']); ?></td>
                            <td class="order-slug"><?php echo esc_html($item['slug']); ?></td>
                            <td class="taxonomies-column">
                                <?php if (!empty($item['taxonomies'])) {
                                    $formatted_taxonomies = array_map(function($taxonomy) {
                                        return ucfirst(str_replace('_', ' ', $taxonomy));
                                    }, $item['taxonomies']);

                                    echo esc_html(implode(', ', $formatted_taxonomies));
                                } else {
                                    echo '—';
                                }?>
                            </td>
                            <td class="actions-column">
                                <button name="post_id" value="<?php echo esc_html($item['id']); ?>" class="delete-post-type-ptmsf button-delete-ptmsf button-secondary"><?php echo esc_html__('Delete', 'post-type-management') ?></button>
                                <button name="post_slug" value="<?php echo esc_html($item['slug']); ?>" class="edit-post-type-ptmsf button-primary"><?php echo esc_html__('Edit', 'post-type-management') ?></button>
                                <input type="checkbox" class="checkbox-delete-ptmsf" name="post_id" value="<?php echo esc_html($item['id']); ?>">
                            </td>
                        </tr>
                        <?php
                        $counter++;
                    } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="input-field">
                                <button class="delete-selected-btn button-secondary button-delete-ptmsf"><?php echo esc_html__('Delete selected post types', 'post-type-management') ?></button>
                                <label>
                                    <input type="checkbox" id="select-all-ptmsf"><?php echo esc_html__('Select All', 'post-type-management') ?>
                                </label>
                                <button id="save-order-btn-ptmsf" class="button-primary ptmsf-save-order" disabled><?php echo esc_html__('Save', 'post-type-management') ?></button>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            <?php } else { ?>
                <h4><?php echo esc_html__('No Post Types created yet...', 'post-type-management') ?></h4>
            <?php } ?>
        </div>
        <div class="form-container">
            <h2><?php echo esc_html__('Create New Post Type', 'post-type-management') ?></h2>
            <form id="custom-post-type-form">
                <div class="input-field">
                    <label for="name"><?php echo esc_html__('Name*', 'post-type-management') ?></label>
                    <input type="text" id="name" name="name" required>
                    <small class="validation-error" id="name_error"><?php echo esc_html__('The "Name" field should not exceed 35 
                    characters.', 'post-type-management') ?></small>
                </div>
                <div class="input-field">
                    <label for="singular_name"><?php echo esc_html__('Singular Name*', 'post-type-management') ?></label>
                    <input type="text" id="singular_name" name="singular_name" required>
                    <small class="validation-error" id="singular_name_error"><?php echo esc_html__('The "Singular Name" field should not exceed 
                    35 characters.', 'post-type-management') ?></small>
                </div>
                <div class="input-field">
                    <label for="slug"><?php echo esc_html__('Slug', 'post-type-management') ?></label>
                    <input type="text" id="slug" name="slug">
                    <small class="validation-error" id="slug_error"><?php echo esc_html__('The "Slug" field should not exceed 20 characters, 
                    should be lowercase, and can only contain english characters, numbers, "-", and "_".', 'post-type-management') ?></small>
                    <div class="validation-info">
                        <div class="info-icon"><p>i</p></div>
                        <p class="info-text"><?php echo esc_html__('To create slug use english characters only, numbers, "-" and "_". All 
                        letters should be lowercase and the field should not exceed 20 characters', 'post-type-management') ?></p>
                    </div>
                </div>
                <div class="input-field">
                    <input type="checkbox" id="auto" name="auto"><?php echo esc_html__('Automatically set Post Type labels', 'post-type-management') ?><br>
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

                        $default_labels = get_post_type_labels($post_type);

                        foreach ($default_labels as $key => $item) {

                            $label = ucwords(str_replace('_', ' ', $key));

                            if ($key != 'name' && $key != 'singular_name' && $key != 'parent_item_colon') { ?>

                                <div class="input-field">
                                    <label for="<?php echo esc_html($key) ?>"><?php echo esc_html($label) ?></label>
                                    <input type="text" id="<?php echo esc_html($key) ?>"
                                           name="labels[<?php echo esc_html($key) ?>]"
                                           placeholder="<?php echo esc_html($item) ?>">

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
                            <?php }
                        } ?>
                    </div>
                    <div class="bool-params">
                        <label><?php echo esc_html__('Parameters:', 'post-type-management') ?></label><br>
                        <?php foreach ($this->bool_params as $param => $value) {
                            if ($value) {
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
                            <input type="number" id="posts_per_page" name="posts_per_page" value="20" min="5" max="40"><?php echo esc_html__('Posts 
                            per page', 'post-type-management') ?><br>
                        </div>
                    </div>
                    <div class="supp-tax">
                        <div class="input-field supports">
                            <label><?php echo esc_html__('Supports:', 'post-type-management') ?></label><br>
                            <?php foreach ($this->supports as $key => $value) {
                                $dash_item = strtolower(str_replace(' ', '-', $key));
                                $lower_dash_item = strtolower(str_replace(' ', '_', $key));

                                $checked = '';
                                if ($value) {
                                    $checked = 'checked';
                                }  ?>
                                <div class="input-inner">
                                    <input type="checkbox" id="supports_<?php echo esc_html($lower_dash_item) ?>"
                                           name="supports[]"
                                           value="<?php echo esc_html($dash_item) ?>" <?php echo esc_html($checked) ?>>
                                    <p><?php echo esc_html($key) ?></p><br>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="input-field supports">
                            <label><?php echo esc_html__('Taxonomies:', 'post-type-management') ?></label><br>
                            <?php foreach ($this->taxonomies as  $key => $value) {
                                $lower_dash_item = strtolower(str_replace(' ', '_', $key));
                                $checked = '';
                                if ($value) {
                                    $checked = 'checked';
                                }  ?>
                                <div class="input-inner">
                                    <input type="checkbox" id="taxonomies_<?php echo esc_html($lower_dash_item) ?>"
                                           name="taxonomies[]"
                                           value="<?php echo esc_html($lower_dash_item) ?>" <?php echo esc_html($checked) ?>>
                                    <p><?php echo esc_html($key) ?></p><br>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="dashicons-container">
                        <div class="input-inner">
                            <label for="dashicon"><?php echo esc_html__('Dashicon', 'post-type-management') ?></label>
                            <input type="text" id="dashicon" name="dashicon" readonly>
                        </div>
                        <div class="input-inner">
                            <button type="button" id="select-dashicon" class="elect-dashicon button-secondary">
                                <?php echo esc_html__('Select Dashicon', 'post-type-management') ?>
                            </button>
                            <div id="dashicon-preview-container">
                                <div id="dashicon-preview" class="dashicons"></div>
                                <button type="button" id="cancel-dashicon-preview">&times;</button>
                            </div>
                            <small class="validation-info"></small>
                            <div class="validation-info dashicon-validation-info">
                                <div class="info-icon"><p>i</p></div>
                                <p class="info-text dashicons-info-icon"><?php echo esc_html__('Some WordPress dashicons can be missing', 'post-type-management') ?></p>
                            </div>
                            <div id="dashicon-picker-modal">
                                <div class="dashicon-picker-modal-inner">
                                    <div id="dashicon-picker">
                                        <?php foreach ($this->dashicons as $dashicon): ?>
                                            <span class="dashicons <?php echo esc_html($dashicon) ?>"
                                                  data-dashicon="<?php echo esc_html($dashicon) ?>"></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" id="close-dashicon-picker" class="button-secondary"><?php echo esc_html__('Close', 'post-type-management') ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="input-inner">
                            <a href="#" class="upload_image_button button button-secondary"><?php echo esc_html__('Upload Image', 'post-type-management') ?></a>
                            <input type="hidden" name="custom_dashicon" id="custom_dashicon" value=""/>
                            <div class="preview-container">
                                <img id="custom_dashicon_preview" src="" alt="Preview"/>
                                <button type="button" id="cancel_image_preview" class="cancel-image-preview">&times;
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="input-field">
                    <button type="submit" class="button-primary"><?php echo esc_html__('Create Post Type', 'post-type-management') ?></button>
                </div>

            </form>
        </div>
    </div>
</div>