<?php
/**
 * Plugin Name:       Ideas
 * Plugin URI:        https://github.com/milosz/wp-ideas
 * Description:       Ideas
 * Version:           0.3.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Milosz Galazka
 * Author URI:        https://sleeplessbeastie.eu
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Ideas;

// Register Tags
function taxonomy_tags() {
	$labels = array(
		'name'                       => __('Tags'),
		'singular_name'              => __('Tag'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,

		'public'                     => false,
		'publicly_queryable'         => false,

		'show_ui'                    => true,
		'show_in_menu'               => true,
		'show_in_nav_menus'          => false,
		'show_in_rest'               => false,
		'show_tagcloud'              => false,
		'show_in_quick_edit'         => false,

		'show_admin_column'          => true,

		'query_var'                  => false,
	);
	register_taxonomy('ideas_tags', 'ideas', $args);

}
add_action('init', __NAMESPACE__ . '\taxonomy_tags', 0);

// Register Categories
function taxonomy_categories() {
	$labels = array(
		'name'                       => __('Categories'),
		'singular_name'              => __('Category'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,

		'public'                     => false,
		'publicly_queryable'         => false,

		'show_ui'                    => true,
		'show_in_menu'               => true,
		'show_in_nav_menus'          => false,
		'show_in_rest'               => false,
		'show_tagcloud'              => false,
		'show_in_quick_edit'         => false,

		'show_admin_column'          => true,

		'query_var'                  => false,
	);
	register_taxonomy('ideas_categories', 'ideas', $args);

}
add_action('init', __NAMESPACE__ . '\taxonomy_categories', 0);

// Register My ideas post type
function create_post_type() {
	$labels = array(
		'name'                  => __('Ideas'),
		'singular_name'         => __('Idea'),
	);

	$capabilities = array(
        'edit_post'              => 'administrator',
        'read_post'              => 'administrator',
        'delete_post'            => 'administrator',

        'edit_posts'             => 'administrator',
        'edit_others_posts'      => 'administrator',
        'publish_posts'          => 'administrator',
        'read_private_posts'     => 'administrator',
    );

	$args = array(
		'labels'                => $labels,

		'public'                => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,

		'hierarchical'          => false,

		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'show_in_admin_bar'     => false,
		'show_in_rest'          => false,

		'menu_position'         => 4,
		'menu_icon'             => 'dashicons-lightbulb',

        'supports'              => array('title', 'editor'),
 		'taxonomies'            => array('ideas_categories', 'ideas_tags'),

		'has_archive'           => false,

		'rewrite'               => false,

		'query_var'             => false,

		'can_export'            => true,

        'delete_with_user'     => false,

        'capabilities'         => $capabilities,
	);
	register_post_type('ideas', $args);

}
add_action('init', __NAMESPACE__ . '\create_post_type', 0);

// Set every My Idea to private
add_filter('wp_insert_post_data', function($post) {
	if( $post['post_type'] == 'ideas' && $post['post_status'] != 'trash') $post['post_status'] = 'private';
  return $post;
});

// Disable rich editor
add_filter('user_can_richedit', function($default) {
  if( get_post_type() == 'ideas') return false;
  return $default;
});
