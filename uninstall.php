<?php
/**
 * Post Type Management Uninstall
 * Uninstalling Post Type Management deletes custom post types and it's posts.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$posts = get_posts([
    'post_type' => 'ptmsf_pt_list',
    'numberposts' => -1
]);

foreach ($posts as $post) {
    $data = get_post_meta($post->ID, 'data')[0];

    $post_type_slug = $data['transformed_name'];

    $all_posts = get_posts([
        'post_type' => $post_type_slug,
        'numberposts' => -1,
        'post_status' => ['publish', 'draft', 'trash', 'pending', 'future', 'private']
    ]);

    if ($all_posts) {
        foreach ($all_posts as $item) {
            wp_delete_post($item->ID, true);
        }
    }

    wp_delete_post($post->ID, true);
}
