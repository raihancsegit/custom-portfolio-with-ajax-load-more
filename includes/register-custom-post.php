<?php
function Register_Book_type()
{
    $labels = array(
        'name'                  => esc_html_x('Book', 'Book', 'book'),
        'singular_name'         => esc_html_x('Book', 'Book', 'book'),
        'menu_name'             => esc_html__('Book', 'book'),
        'name_admin_bar'        => esc_html__('Book', 'book'),
        'parent_item_colon'     => esc_html__('Parent Book:', 'book'),
        'all_items'             => esc_html__('All Book', 'book'),
        'add_new_item'          => esc_html__('Add New Book', 'book'),
        'add_new'               => esc_html__('Add Book', 'book'),
        'new_item'              => esc_html__('New Book', 'book'),
        'edit_item'             => esc_html__('Edit Book', 'book'),
        'update_item'           => esc_html__('Update Book', 'book'),
        'view_item'             => esc_html__('View Book', 'book'),
        'search_items'          => esc_html__('Search Book', 'book'),
        'not_found'             => esc_html__('Not found', 'book'),
        'not_found_in_trash'    => esc_html__('Not found in Trash', 'book'),
        'items_list'            => esc_html__('Book list', 'book'),
        'items_list_navigation' => esc_html__('Book list navigation', 'book'),
        'filter_items_list'     => esc_html__('Filter Book list', 'book'),
    );

    $args = array(
        'label' => esc_html__('Book', 'book'),
        'description' => esc_html__('Book Post Type', 'book'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'author', 'thumbnail'),
        'taxonomies' => array('Book-tag', 'Book-category'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-images-alt2',
        'show_in_admin_bar' => true,
        'can_export' => true,
        'exclude_from_search' => true,
        'capability_type' => 'post',
    );

    register_post_type('book', $args);
}
add_action('init','Register_Book_type');

function Register_Book_Category()
{
    $labels = array(
        'name'                       => esc_html_x('Book Categories', 'Taxonomy General Name', 'book'),
        'singular_name'              => esc_html_x('Book Category ', 'Taxonomy Singular Name', 'book'),
        'menu_name'                  => esc_html__('Book Category', 'book'),
        'all_items'                  => esc_html__('All Book', 'book'),
        'parent_item'                => esc_html__('Parent Book', 'book'),
        'parent_item_colon'          => esc_html__('Parent Book:', 'book'),
        'new_item_name'              => esc_html__('New Book Name', 'book'),
        'add_new_item'               => esc_html__('Add New Book', 'book'),
        'edit_item'                  => esc_html__('Edit Book', 'book'),
        'update_item'                => esc_html__('Update Book', 'book'),
        'view_item'                  => esc_html__('View Book', 'book'),
        'separate_items_with_commas' => esc_html__('Separate Book with commas', 'book'),
        'add_or_remove_items'        => esc_html__('Add or remove Book', 'book'),
        'choose_from_most_used'      => esc_html__('Choose from the most used', 'book'),
        'popular_items'              => esc_html__('Popular Book', 'book'),
        'search_items'               => esc_html__('Search Book', 'book'),
        'not_found'                  => esc_html__('Not Found', 'book'),
        'no_terms'                   => esc_html__('No Book', 'book'),
        'items_list'                 => esc_html__('Book list', 'book'),
        'items_list_navigation'      => esc_html__('Category list navigation', 'book'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
    );
    register_taxonomy('bookcategory', array('book'), $args);
}
add_action('init', 'Register_Book_Category');