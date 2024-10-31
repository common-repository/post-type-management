<?php
/**
 * Plugin Name: Post Type Management
 * Description: A tool that helps you easily create and manage custom post types
 * Version: 1.2.1
 * Author: SheepFish
 * Author URI: https://sheep.fish/
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Text Domain: post-type-management
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('PTMbySheepFish')) {

    class PTMbySheepFish
    {
        /** @var string */
        private $plugin_page;

        /** @var string */
        private $edit_page;

        /** @var string */
        private $plugin_path;

        /** @var string */
        private $plugin_url;

        /** @var string */
        private $plugin_basename;

        /** @var array<string> */
        private $dashicons;

        /** @var array<string, bool> */
        private $supports;

        /** @var array<string, bool> */
        private $taxonomies;

        /** @var array<string> */
        private $excluded_fields;

        /** @var array<string, bool> */
        private $bool_params;

        public function __construct()
        {

            $this->plugin_page = admin_url('tools.php?page=post-type-management');
            $this->edit_page = admin_url('tools.php?page=post_type_edit_page');
            $this->plugin_path = plugin_dir_path(__FILE__);
            $this->plugin_url = plugin_dir_url(__FILE__);
            $this->plugin_basename = plugin_basename(__FILE__);
            $this->dashicons = [
                'dashicons-admin-appearance',
                'dashicons-admin-collapse',
                'dashicons-admin-comments',
                'dashicons-admin-customizer',
                'dashicons-admin-generic',
                'dashicons-admin-home',
                'dashicons-admin-links',
                'dashicons-admin-media',
                'dashicons-admin-multisite',
                'dashicons-admin-network',
                'dashicons-admin-page',
                'dashicons-admin-plugins',
                'dashicons-admin-post',
                'dashicons-admin-settings',
                'dashicons-admin-site',
                'dashicons-admin-site-alt',
                'dashicons-admin-site-alt2',
                'dashicons-admin-site-alt3',
                'dashicons-admin-tools',
                'dashicons-admin-users',
                'dashicons-airplane',
                'dashicons-album',
                'dashicons-align-center',
                'dashicons-align-full-width',
                'dashicons-align-left',
                'dashicons-align-none',
                'dashicons-align-pull-left',
                'dashicons-align-pull-right',
                'dashicons-align-right',
                'dashicons-align-wide',
                'dashicons-amazon',
                'dashicons-analytics',
                'dashicons-archive',
                'dashicons-arrow-down',
                'dashicons-arrow-down-alt',
                'dashicons-arrow-down-alt2',
                'dashicons-arrow-left',
                'dashicons-arrow-left-alt',
                'dashicons-arrow-left-alt2',
                'dashicons-arrow-right',
                'dashicons-arrow-right-alt',
                'dashicons-arrow-right-alt2',
                'dashicons-arrow-up',
                'dashicons-arrow-up-alt',
                'dashicons-arrow-up-alt2',
                'dashicons-art',
                'dashicons-awards',
                'dashicons-backup',
                'dashicons-bank',
                'dashicons-beer',
                'dashicons-bell',
                'dashicons-block-default',
                'dashicons-book',
                'dashicons-book-alt',
                'dashicons-buddicons-activity',
                'dashicons-buddicons-bbpress-logo',
                'dashicons-buddicons-buddypress-logo',
                'dashicons-buddicons-community',
                'dashicons-buddicons-forums',
                'dashicons-buddicons-friends',
                'dashicons-buddicons-groups',
                'dashicons-buddicons-pm',
                'dashicons-buddicons-replies',
                'dashicons-buddicons-topics',
                'dashicons-buddicons-tracking',
                'dashicons-building',
                'dashicons-businessman',
                'dashicons-businessperson',
                'dashicons-businesswoman',
                'dashicons-button',
                'dashicons-calculator',
                'dashicons-calendar',
                'dashicons-calendar-alt',
                'dashicons-camera',
                'dashicons-camera-alt',
                'dashicons-car',
                'dashicons-carrot',
                'dashicons-cart',
                'dashicons-category',
                'dashicons-chart-area',
                'dashicons-chart-bar',
                'dashicons-chart-line',
                'dashicons-chart-pie',
                'dashicons-clipboard',
                'dashicons-clock',
                'dashicons-cloud',
                'dashicons-cloud-saved',
                'dashicons-cloud-upload',
                'dashicons-code-standards',
                'dashicons-coffee',
                'dashicons-color-picker',
                'dashicons-columns',
                'dashicons-controls-back',
                'dashicons-controls-forward',
                'dashicons-controls-pause',
                'dashicons-controls-play',
                'dashicons-controls-repeat',
                'dashicons-controls-skipback',
                'dashicons-controls-skipforward',
                'dashicons-controls-volumeoff',
                'dashicons-controls-volumeon',
                'dashicons-cover-image',
                'dashicons-dashboard',
                'dashicons-database',
                'dashicons-database-add',
                'dashicons-database-export',
                'dashicons-database-import',
                'dashicons-database-remove',
                'dashicons-database-view',
                'dashicons-desktop',
                'dashicons-dismiss',
                'dashicons-download',
                'dashicons-drumstick',
                'dashicons-edit',
                'dashicons-edit-large',
                'dashicons-edit-page',
                'dashicons-editor-aligncenter',
                'dashicons-editor-alignleft',
                'dashicons-editor-alignright',
                'dashicons-editor-bold',
                'dashicons-editor-break',
                'dashicons-editor-code',
                'dashicons-editor-contract',
                'dashicons-editor-customchar',
                'dashicons-editor-expand',
                'dashicons-editor-help',
                'dashicons-editor-indent',
                'dashicons-editor-insertmore',
                'dashicons-editor-italic',
                'dashicons-editor-justify',
                'dashicons-editor-kitchensink',
                'dashicons-editor-ltr',
                'dashicons-editor-ol',
                'dashicons-editor-ol-rtl',
                'dashicons-editor-outdent',
                'dashicons-editor-paragraph',
                'dashicons-editor-paste-text',
                'dashicons-editor-paste-word',
                'dashicons-editor-quote',
                'dashicons-editor-removeformatting',
                'dashicons-editor-rtl',
                'dashicons-editor-spellcheck',
                'dashicons-editor-strikethrough',
                'dashicons-editor-table',
                'dashicons-editor-textcolor',
                'dashicons-editor-ul',
                'dashicons-editor-underline',
                'dashicons-editor-unlink',
                'dashicons-editor-video',
                'dashicons-ellipsis',
                'dashicons-email',
                'dashicons-email-alt',
                'dashicons-email-alt2',
                'dashicons-embed-audio',
                'dashicons-embed-generic',
                'dashicons-embed-photo',
                'dashicons-embed-post',
                'dashicons-embed-video',
                'dashicons-excerpt-view',
                'dashicons-exit',
                'dashicons-external',
                'dashicons-facebook',
                'dashicons-facebook-alt',
                'dashicons-feedback',
                'dashicons-filter',
                'dashicons-flag',
                'dashicons-food',
                'dashicons-format-aside',
                'dashicons-format-audio',
                'dashicons-format-chat',
                'dashicons-format-gallery',
                'dashicons-format-image',
                'dashicons-format-quote',
                'dashicons-format-status',
                'dashicons-format-video',
                'dashicons-forms',
                'dashicons-fullscreen-alt',
                'dashicons-fullscreen-exit-alt',
                'dashicons-games',
                'dashicons-google',
                'dashicons-grid-view',
                'dashicons-groups',
                'dashicons-hammer',
                'dashicons-heading',
                'dashicons-heart',
                'dashicons-hidden',
                'dashicons-hourglass',
                'dashicons-html',
                'dashicons-id',
                'dashicons-id-alt',
                'dashicons-image-crop',
                'dashicons-image-filter',
                'dashicons-image-flip-horizontal',
                'dashicons-image-flip-vertical',
                'dashicons-image-rotate',
                'dashicons-image-rotate-left',
                'dashicons-image-rotate-right',
                'dashicons-images-alt',
                'dashicons-images-alt2',
                'dashicons-index-card',
                'dashicons-info',
                'dashicons-info-outline',
                'dashicons-insert',
                'dashicons-insert-after',
                'dashicons-insert-before',
                'dashicons-instagram',
                'dashicons-laptop',
                'dashicons-layout',
                'dashicons-leftright',
                'dashicons-lightbulb',
                'dashicons-linkedin',
                'dashicons-list-view',
                'dashicons-location',
                'dashicons-location-alt',
                'dashicons-lock',
                'dashicons-marker',
                'dashicons-media-archive',
                'dashicons-media-audio',
                'dashicons-media-code',
                'dashicons-media-default',
                'dashicons-media-document',
                'dashicons-media-interactive',
                'dashicons-media-spreadsheet',
                'dashicons-media-text',
                'dashicons-media-video',
                'dashicons-megaphone',
                'dashicons-menu',
                'dashicons-menu-alt',
                'dashicons-menu-alt2',
                'dashicons-menu-alt3',
                'dashicons-microphone',
                'dashicons-migrate',
                'dashicons-minus',
                'dashicons-money',
                'dashicons-money-alt',
                'dashicons-move',
                'dashicons-nametag',
                'dashicons-networking',
                'dashicons-no',
                'dashicons-no-alt',
                'dashicons-open-folder',
                'dashicons-palmtree',
                'dashicons-paperclip',
                'dashicons-pdf',
                'dashicons-performance',
                'dashicons-pets',
                'dashicons-phone',
                'dashicons-pinterest',
                'dashicons-playlist-audio',
                'dashicons-playlist-video',
                'dashicons-plugins-checked',
                'dashicons-plus',
                'dashicons-plus-alt',
                'dashicons-plus-alt2',
                'dashicons-podio',
                'dashicons-portfolio',
                'dashicons-post-status',
                'dashicons-pressthis',
                'dashicons-printer',
                'dashicons-privacy',
                'dashicons-products',
                'dashicons-randomize',
                'dashicons-reddit',
                'dashicons-redo',
                'dashicons-remove',
                'dashicons-rest-api',
                'dashicons-rss',
                'dashicons-saved',
                'dashicons-schedule',
                'dashicons-screenoptions',
                'dashicons-search',
                'dashicons-share',
                'dashicons-share-alt',
                'dashicons-share-alt2',
                'dashicons-shield',
                'dashicons-shield-alt',
                'dashicons-shortcode',
                'dashicons-slides',
                'dashicons-smartphone',
                'dashicons-smiley',
                'dashicons-sort',
                'dashicons-sos',
                'dashicons-spotify',
                'dashicons-star-empty',
                'dashicons-star-filled',
                'dashicons-star-half',
                'dashicons-sticky',
                'dashicons-store',
                'dashicons-superhero',
                'dashicons-superhero-alt',
                'dashicons-table-col-after',
                'dashicons-table-col-before',
                'dashicons-table-col-delete',
                'dashicons-table-row-after',
                'dashicons-table-row-before',
                'dashicons-table-row-delete',
                'dashicons-tablet',
                'dashicons-tag',
                'dashicons-tagcloud',
                'dashicons-testimonial',
                'dashicons-text',
                'dashicons-text-page',
                'dashicons-thumbs-down',
                'dashicons-thumbs-up',
                'dashicons-tickets',
                'dashicons-tickets-alt',
                'dashicons-tide',
                'dashicons-translation',
                'dashicons-trash',
                'dashicons-twitch',
                'dashicons-twitter',
                'dashicons-twitter-alt',
                'dashicons-undo',
                'dashicons-universal-access',
                'dashicons-universal-access-alt',
                'dashicons-unlock',
                'dashicons-update',
                'dashicons-update-alt',
                'dashicons-upload',
                'dashicons-vault',
                'dashicons-video-alt',
                'dashicons-video-alt2',
                'dashicons-video-alt3',
                'dashicons-visibility',
                'dashicons-warning',
                'dashicons-welcome-add-page',
                'dashicons-welcome-comments',
                'dashicons-welcome-learn-more',
                'dashicons-welcome-view-site',
                'dashicons-welcome-widgets-menus',
                'dashicons-welcome-write-blog',
                'dashicons-whatsapp',
                'dashicons-wordpress',
                'dashicons-wordpress-alt',
                'dashicons-xing',
                'dashicons-yes',
                'dashicons-yes-alt',
                'dashicons-youtube',
            ];
            $this->supports = [
                'Title' => true,
                'Editor' => true,
                'Thumbnail' => true,
                'Excerpt' => true,
                'Author' => false,
                'Trackbacks' => false,
                'Custom Fields' => false,
                'Comments' => false,
                'Revisions' => false,
                'Page Attributes' => false,
                'Post Formats' => false,
            ];
            $this->taxonomies = [
                'Post tag' => true,
                'Category' => true
            ];
            $this->excluded_fields = [
                'name',
                'singular_name',
                'menu_name',
                'slug',
                'supports',
                'taxonomies',
                'public',
                'posts_per_page',
                'auto',
                'dashicon',
                'custom_dashicon',
                'action',
                'nonce'
            ];
            $this->bool_params = [
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_rest' => false,
                'exclude_from_search' => false,
                'can_export' => true,
                'query_var' => true,
                'rewrite' => true,
                'has_archive' => true,
                'hierarchical' => false,
            ];

            register_activation_hook(__FILE__, [$this, 'ptmsf_activate']);
            add_action('admin_init', [$this, 'ptmsf_redirect']);
            register_deactivation_hook(__FILE__, [$this, 'ptmsf_deactivate']);
            add_action('admin_menu', [$this, 'register_ptmsf_submenu_page']);
            add_filter('plugin_action_links_' . $this->plugin_basename, [$this, 'ptmsf_plugin_action_links']);
            add_action('admin_enqueue_scripts', [$this, 'ptmsf_enqueue_scripts_and_styles']);

            add_action('init', [$this, 'ptmsf_pt_list_post_type']);
            add_action('admin_menu', [$this, 'user_ptmsf_plugin_menu']);

            add_action('wp_ajax_set_post_type_data', [$this, 'set_post_type_data']);
            add_action('wp_ajax_nopriv_set_post_type_data', [$this, 'set_post_type_data']);

            add_action('init', [$this, 'register_post_types']);

            add_action('wp_ajax_delete_custom_post_type', [$this, 'delete_custom_post_type']);
            add_action('wp_ajax_nopriv_delete_custom_post_type', [$this, 'delete_custom_post_type']);

            add_action('wp_ajax_edit_custom_post_type', [$this, 'edit_custom_post_type']);
            add_action('wp_ajax_nopriv_edit_custom_post_type', [$this, 'edit_custom_post_type']);

            add_action('wp_ajax_update_custom_post_type', [$this, 'update_custom_post_type']);
            add_action('wp_ajax_nopriv_update_custom_post_type', [$this, 'update_custom_post_type']);

            add_action('wp_ajax_delete_selected', [$this, 'delete_selected']);
            add_action('wp_ajax_nopriv_delete_selected', [$this, 'delete_selected']);

            add_filter('screen_options_show_screen', [$this, 'hide_cpt_screen_options']);
            add_action('admin_head', [$this, 'add_custom_button_to_post_list']);

            add_action('wp_ajax_save_post_type_order', [$this, 'update_pt_order']);
        }

        // Activates plugin
        public function ptmsf_activate(): void
        {
            set_transient('ptmsf_redirect', true, 30);
            flush_rewrite_rules();
        }

        // Redirects user after plugin activation
        public function ptmsf_redirect(): void
        {
            if (get_transient('ptmsf_redirect')) {

                delete_transient('ptmsf_redirect');

                wp_redirect($this->plugin_page);
                exit;

            }
        }

        // Deactivates plugin
        public function ptmsf_deactivate(): void
        {
            flush_rewrite_rules();
        }

        // Includes file for uninstalling plugin
        public function ptmsf_uninstall(): void
        {
            include_once($this->plugin_path . 'uninstall.php');
        }

        // Creating submenu page for plugin settings
        public function register_ptmsf_submenu_page(): void
        {

            add_submenu_page(
                'tools.php',
                __('Post Type Management', 'post-type-management'),
                __('PT Management', 'post-type-management'),
                'manage_options',
                'post-type-management',
                [$this, 'ptmsf_plugin_page_content']
            );

        }

        // Including settings page template
        public function ptmsf_plugin_page_content(): void
        {
            require 'templates/post-type-management-page.php';
        }

        // Adds "Settings" link in plugins list
        public function ptmsf_plugin_action_links($links): array
        {
            $custom_link = '<a href="' . $this->plugin_page . '">' . __('Settings', 'post-type-management') . '</a>';
            array_push($links, $custom_link);
            return $links;
        }

        // Enqueues plugin's styles and scripts
        public function ptmsf_enqueue_scripts_and_styles(): void
        {
            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            wp_enqueue_style('ptmsf-style', $this->plugin_url . 'assets/css/style.css', [], '1.0', 'all');

            wp_enqueue_script('ptmsf-ajax-script', $this->plugin_url . 'assets/js/ajax.js', ['jquery'], '1.0', true);
            wp_localize_script('ptmsf-ajax-script', 'post_type_management', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ptmsf_nonce')
            ]);

            wp_enqueue_script('ptmsf-main-script', $this->plugin_url . 'assets/js/main.js', ['jquery'], '1.0', true);
        }

        // Registration of post type for custom post types
        public function ptmsf_pt_list_post_type(): void
        {
            register_post_type('ptmsf_pt_list', [
                'labels' => [
                    'name' => __('Post type list', 'post-type-management'),
                    'singular_name' => __('Post Type', 'post-type-management'),
                    'add_new' => __('Add new post type', 'post-type-management'),
                    'add_new_item' => __('Add new post type', 'post-type-management'),
                    'edit_item' => __('Edit new post type', 'post-type-management'),
                    'new_item' => __('New post type', 'post-type-management'),
                    'view_item' => __('New post type', 'post-type-management'),
                    'search_items' => __('Find post type', 'post-type-management'),
                    'not_found' => __('Post type not found', 'post-type-management'),
                    'not_found_in_trash' => __('Post Type not found', 'post-type-management'),
                    'parent_item_colon' => '',
                    'menu_name' => __('Post type list', 'post-type-management')
                ],
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => false,
                'query_var' => true,
                'rewrite' => true,
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => 5,
                'supports' => ['title', 'thumbnail', 'editor'],
                'menu_icon' => 'dashicons-open-folder',
                'taxonomies' => ['post_tag', 'category']
            ]);
        }

        // Creating page for user's post types settings
        public function user_ptmsf_plugin_menu(): void
        {
            add_menu_page(
                __('Post Type Settings Page', 'post-type-management'),
                __('Post Type Settings Page', 'post-type-management'),
                'manage_options',
                'post_type_edit_page',
                [$this, 'ptmsf_edit_page_content'],
                'dashicons-welcome-widgets-menus',
                40
            );

            add_action('admin_title', [$this, 'set_admin_page_title'], 10, 2);
        }

        // Including settings page template for user's post types
        public function ptmsf_edit_page_content(): void
        {
            require 'templates/post-type-edit-page.php';
        }

        // Sets title for user's post types settings page in browser tab
        public function set_admin_page_title($admin_title, $title): string
        {
            $screen = get_current_screen();
            if ($screen->id == 'toplevel_page_post_type_edit_page') {
                return __('Post Type Settings Page', 'post-type-management') . ' ‹ ' . get_bloginfo('name');
            }
            return $admin_title;
        }

        // Creates post in ptmsf_pt_list_post_type with certain meta data
        public function set_post_type_data(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            $name = sanitize_text_field($_POST['name']);
            $transformed_name = $this->make_slug_friendly($name);
            $slug_not_trimmed = sanitize_text_field($_POST['slug']);
            $slug = trim($slug_not_trimmed, ' ');
            $singular_name = sanitize_text_field($_POST['singular_name']);

            if (sanitize_text_field($_POST['labels']['menu_name'])) {
                $menu_name = sanitize_text_field($_POST['labels']['menu_name']);
            } else {
                $menu_name = $name;
            }

            if ($_POST['supports']) {
                $supports = array_map('sanitize_text_field', $_POST['supports']);
            } else {
                $supports = false;
            }

            if ($_POST['taxonomies']) {
                $taxonomies = array_map('sanitize_text_field', $_POST['taxonomies']);
            } else {
                $taxonomies = false;
            }

            $bool_arr = [];

            foreach ($this->bool_params as $key => $value) {
                $bool_arr[$key] = filter_var(sanitize_text_field($_POST[$key]), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }

            $auto = filter_var(sanitize_text_field($_POST['auto']), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $dashicon = sanitize_text_field($_POST['dashicon']);
            $custom_dashicon = sanitize_text_field($_POST['custom_dashicon']);
            $posts_per_page = sanitize_text_field($_POST['posts_per_page']);
            $fields = array_map('sanitize_text_field', $_POST['labels']);

            if (!is_array($supports)) {
                $supports = [''];
            }
            if (!is_array($taxonomies)) {
                $taxonomies = [];
            }
            if (empty($slug)) {
                $slug = false;
            }

            $exists = false;

            if ($slug) {
                $transformed_name = $slug;
            }

            $post_types = get_post_types();

            foreach ($post_types as $type) {
                $type_obj = get_post_type_object($type);
                if ($type_obj->name === $transformed_name || $type_obj->slug === $transformed_name) {
                    $exists = true;
                }
            }

            if (!$exists) {

                $main_arr = [
                    'transformed_name' => $transformed_name,
                    'name' => $name,
                    'singular_name' => $singular_name,
                    'menu_name' => $menu_name,
                    'auto' => $auto,
                    'fields' => $fields,
                    'supports' => $supports,
                    'taxonomies' => $taxonomies,
                    'posts_per_page' => $posts_per_page,
                    'dashicon' => $dashicon,
                    'custom_dashicon' => $custom_dashicon,
                    'menu_position' => 35,
                    'menu_position_changed' => false
                ];

                $data = array_merge($main_arr, $bool_arr);

                $post_data = [
                    'post_title' => $name,
                    'post_status' => 'publish',
                    'post_type' => 'ptmsf_pt_list',
                    'post_content' => $transformed_name,
                    'meta_input' => [
                        'data' => $data,
                        'ptmsf_slug' => $transformed_name
                    ],
                ];

                wp_insert_post($post_data);

                wp_send_json_success();

            } else {
                if ($slug) {

                    $error = 'slug';

                } else {

                    $error = 'name';
                }

                wp_send_json_error([
                    'error' => $error
                ]);
            }

        }

        // Registers post types from ptmsf_pt_list_post_type
        public function register_post_types(): void
        {
            $posts = get_posts([
                'post_type' => 'ptmsf_pt_list',
                'post_status' => 'publish',
                'numberposts' => -1,
                'order' => 'ASC'
            ]);

            $menu_counter = 0;

            foreach ($posts as $post) {
                $data = get_post_meta($post->ID, 'data', true);
                $dashicon = $data['dashicon'];
                $custom_dashicon = $data['custom_dashicon'];
                $slug = $data['transformed_name'];
                $posts_per_page = intval($data['posts_per_page']);


                if ($custom_dashicon) {
                    $icon = $custom_dashicon;
                } else {
                    if (in_array($dashicon, $this->dashicons)) {
                        $icon = $dashicon;
                    } else {
                        $icon = 'dashicons-admin-post';
                    }
                }

                $labels = $this->set_auto_fields($data);

                if (!$data['menu_position_changed']) {
                    $data['menu_position'] = 35 + $menu_counter;
                    update_post_meta($post->ID, 'data', $data);
                }

                $main_arr = [
                    'labels' => $labels,
                    'public' => $data['public'],
                    'capability_type' => 'post',
                    'supports' => $data['supports'],
                    'menu_icon' => $icon,
                    'taxonomies' => $data['taxonomies'],
                    'menu_position' => (int)$data['menu_position']
                ];

                $bool_arr = [];

                foreach ($data as $key => $item) {

                    foreach ($this->bool_params as $param => $value) {

                        if ($param == $key) {
                            $bool_arr[$key] = $item;
                        }

                    }

                }

                $final_arr = array_merge($main_arr, $bool_arr);

                register_post_type($slug, $final_arr);

                add_filter('get_user_option_edit_' . $slug . '_per_page', function () use ($posts_per_page) {
                    return $posts_per_page;
                }, 10, 3);

                $menu_counter++;

            }
        }

        // Deletes single post type
        public function delete_custom_post_type(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            $post_id = sanitize_text_field($_POST['post_id']);
            $page = sanitize_text_field($_POST['page']);
            $data = get_post_meta($post_id, 'data', true);

            $post_type_slug = $data['transformed_name'];

            $all_posts = get_posts([
                'post_type' => $post_type_slug,
                'numberposts' => -1,
                'post_status' => ['publish', 'draft', 'trash', 'pending', 'future', 'private']
            ]);

            if ($all_posts) {
                foreach ($all_posts as $post) {
                    wp_delete_post($post->ID, true);
                }
            }

            wp_delete_post($post_id, true);

            if ($page == 'toplevel_page_post_type_edit_page' || $page == 'edit') {

                $url = $this->plugin_page;

            } else {
                $url = 'reload';
            }

            wp_send_json_success([
                'url' => $url
            ]);
        }

        // Redirects user to user's post types settings page
        public function edit_custom_post_type(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            $post_slug = sanitize_text_field($_POST['post_slug']);

            $url = $this->edit_page . '&custom_post_type=' . $post_slug;

            wp_send_json_success([
                'url' => $url
            ]);

        }

        // Updates post type settings
        public function update_custom_post_type(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            $name = sanitize_text_field($_POST['name']);
            $slug = sanitize_text_field($_POST['slug']);
            $singular_name = sanitize_text_field($_POST['singular_name']);

            if (sanitize_text_field($_POST['labels']['menu_name'])) {
                $menu_name = sanitize_text_field($_POST['labels']['menu_name']);
            } else {
                $menu_name = $name;
            }

            if ($_POST['supports']) {
                $supports = array_map('sanitize_text_field', $_POST['supports']);
            } else {
                $supports = false;
            }

            if ($_POST['taxonomies']) {
                $taxonomies = array_map('sanitize_text_field', $_POST['taxonomies']);
            } else {
                $taxonomies = false;
            }

            $bool_arr = [];

            foreach ($this->bool_params as $key => $value) {
                $bool_arr[$key] = filter_var(sanitize_text_field($_POST[$key]), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }

            $auto = filter_var(sanitize_text_field($_POST['auto']), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $dashicon = sanitize_text_field($_POST['dashicon']);
            $custom_dashicon = sanitize_text_field($_POST['custom_dashicon']);
            $posts_per_page = intval(sanitize_text_field($_POST['posts_per_page']));
            $fields = array_map('sanitize_text_field', $_POST['labels']);

            $args = [
                'post_type' => 'ptmsf_pt_list',
                'fields' => 'ids',
                'meta_query' => [
                    [
                        'key' => 'ptmsf_slug',
                        'value' => $slug,
                        'compare' => '='
                    ]
                ]
            ];

            $post_id = get_posts($args)[0];

            if (!$taxonomies) {
                $taxonomies = [];
            }

            if (!$supports) {
                $supports = [''];
            }

            $post_meta = get_post_meta($post_id, 'data', true);

            $main_arr = [
                'transformed_name' => $slug,
                'name' => $name,
                'singular_name' => $singular_name,
                'menu_name' => $menu_name,
                'auto' => $auto,
                'fields' => $fields,
                'supports' => $supports,
                'taxonomies' => $taxonomies,
                'posts_per_page' => $posts_per_page,
                'dashicon' => $dashicon,
                'custom_dashicon' => $custom_dashicon,
                'menu_position' => $post_meta['menu_position'],
                'menu_position_changed' => $post_meta['menu_position_changed']
            ];

            $data = array_merge($main_arr, $bool_arr);

            $post_data = [
                'ID' => $post_id,
                'post_title' => $name,
                'post_content' => $slug,
            ];

            wp_update_post($post_data);

            update_post_meta($post_id, 'data', $data);

            wp_send_json_success();
        }

        // Deletes selected post types
        public function delete_selected(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            if ($_POST['post_ids']) {
                $post_ids = array_map('sanitize_text_field', $_POST['post_ids']);
            } else {
                $post_ids = [];
            }

            foreach ($post_ids as $post_id) {

                $data = get_post_meta($post_id, 'data', true);

                $post_type_slug = $data['transformed_name'];

                $all_posts = get_posts([
                    'post_type' => $post_type_slug,
                    'numberposts' => -1,
                    'post_status' => ['publish', 'draft', 'trash', 'pending', 'future', 'private']
                ]);

                if ($all_posts) {
                    foreach ($all_posts as $post) {
                        wp_delete_post($post->ID, true);
                    }
                }

                wp_delete_post($post_id);
            }

            wp_send_json_success();
        }

        // Hides custom post type screen options
        public function hide_cpt_screen_options($show_screen_options): bool
        {

            if (isset($_GET['post_type']) || isset($_GET['custom_post_type'])) {
                if (isset($_GET['post_type'])) {
                    $post_type_slug = sanitize_text_field($_GET['post_type']);
                } else {
                    $post_type_slug = sanitize_text_field($_GET['custom_post_type']);
                }

                $posts = get_posts([
                    'post_type' => 'ptmsf_pt_list',
                    'post_status' => 'publish',
                    'numberposts' => -1
                ]);

                foreach ($posts as $post) {
                    $data = get_post_meta($post->ID, 'data')[0];
                    if ($data['transformed_name'] == $post_type_slug) {
                        return false;
                    }
                }
            }

            return $show_screen_options;
        }

        // Adds buttons "Edit Post Type" and "Delete Post Type" to user's post type page
        public function add_custom_button_to_post_list(): void
        {
            if (isset($_GET['post_type'])) {

                $post_type_slug = sanitize_text_field($_GET['post_type']);
                $page = get_current_screen()->base;

                $posts = get_posts([
                    'post_type' => 'ptmsf_pt_list',
                    'post_status' => 'publish',
                    'numberposts' => -1
                ]);

                foreach ($posts as $post) {
                    $data = get_post_meta($post->ID, 'data')[0];
                    if ($data['transformed_name'] == $post_type_slug) {
                        $edit_button = '<button name="post_slug" value="' . esc_attr($data['transformed_name']) . '" class="page-title-action edit-post-type-ptmsf button-primary">' . __('Edit Post Type', 'post-type-management') . '</button>';
                        $delete_button = '<button name="post_id" class="page-title-action delete-post-type-ptmsf button-delete-ptmsf" value="' . esc_attr($post->ID) . '">' . __('Delete Post Type', 'post-type-management') . '</button>';

                        $script = "
                    jQuery(document).ready(function ($) {
                        const input = $('<input type=\"hidden\" name=\"page\" value=\"" . esc_js($page) . "\" class=\"hidden-input\">');
                        const editButton = $('" . $edit_button . "');
                        const deleteButton = $('" . $delete_button . "');
                        $('.page-title-action').after(input);
                        $('.hidden-input').after(editButton);
                        $('.edit-post-type-ptmsf').after(deleteButton);
                        
                        $('.delete-post-type-ptmsf').on('click', function (e) {
                            e.preventDefault();

                            var post_id = $(this).val();
                            var page = $('input[name=\"page\"]').val();
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
                    });
                ";

                        wp_add_inline_script('ptmsf-main-script', $script);
                    }
                }
            }
        }

        // Makes slug from post type name
        public function make_slug_friendly($name): string
        {

            $transliterated_name = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);

            $transliterated_name = preg_replace('/\s+/', '_', $transliterated_name);

            $transliterated_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $transliterated_name);

            if (strlen($transliterated_name) > 20) {
                $transliterated_name = substr($transliterated_name, 0, 20);
            }

            $transformed_name = strtolower($transliterated_name);

            return $transformed_name;
        }

        // Makes custom labels for a post type from post type name and single_name
        public function set_auto_fields($data): object
        {
            $fields = $data['fields'];
            $name = $data['name'];
            $name_lc = strtolower($name);
            $singular_name = $data['singular_name'];
            $singular_name_lc = strtolower($singular_name);
            $menu_name = $data['menu_name'];
            $auto = $data['auto'];

            $post_type = (object)[
                'name' => 'post',
                'labels' => (object)[],
                'hierarchical' => false
            ];

            $default_labels = get_post_type_labels($post_type);

            $labels = $default_labels;

            $labels->name = $name;
            $labels->singular_name = $singular_name;
            $labels->menu_name = $menu_name;

            foreach ($labels as $key => $label) {
                if (!empty($fields[$key])) {
                    $labels->$key = $fields[$key];
                }
            }

            if ($auto) {
                foreach ($labels as $key => $label) {
                    if (is_string($label)) {
                        $label = str_replace('Posts', $name, $label);
                        $label = str_replace('posts', $name_lc, $label);
                        $label = str_replace('Post', $singular_name, $label);
                        $label = str_replace('post', $singular_name_lc, $label);
                        $labels->$key = $label;
                    }
                }
            }

            return $labels;
        }

        public function update_pt_order(): void
        {
            check_ajax_referer('ptmsf_nonce', 'nonce');

            if (isset($_POST['order']) && is_array($_POST['order'])) {
                $order_data = $_POST['order'];

                foreach ($order_data as $post_id => $new_order) {

                    $data = get_post_meta($post_id, 'data', true);

                    $data['menu_position'] = 34 + $new_order;
                    $data['menu_position_changed'] = true;

                    update_post_meta($post_id, 'data', $data);
                }

                wp_send_json_success();

            } else {
                wp_send_json_error('Invalid data');
            }

        }

    }

    $ptmsh = new PTMbySheepFish;

}