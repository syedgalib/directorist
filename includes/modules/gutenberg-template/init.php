<?php

namespace Directorist\Modules; 

defined( 'ABSPATH' ) || exit;

use Directorist\Directorist_Listings;
use Directorist\Helper;

class GutenbergTemplate {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_types' ], 10 );
        add_filter( 'directorist_listings_deferred_props', [ $this, 'add_deferred_props' ], 10, 1 );
        add_action( 'directorist_before_listings_loop', [ $this, 'maybe_set_listing_item_template_id' ], 10, 2 );
        add_action( 'directorist_listings_loop_item_custom_template', [ $this, 'render_listings_loop_item_custom_template' ], 10, 2 );
        
        $this->register_blocks();
    }

    public function register_post_types() {
        $labels = [
            'name'                  => _x( 'Builder Templates', 'Post Type General Name', 'directorist' ),
            'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'directorist' ),
            'menu_name'             => __( 'Directorist Template', 'directorist' ),
            'name_admin_bar'        => __( 'Directorist Template', 'directorist' ),
            'archives'              => __( 'Template Archives', 'directorist' ),
            'attributes'            => __( 'Template Attributes', 'directorist' ),
            'parent_item_colon'     => __( 'Parent Item:', 'directorist' ),
            'all_items'             => __( 'Builder Templates', 'directorist' ),
            'add_new_item'          => __( 'Add New Template', 'directorist' ),
            'add_new'               => __( 'Add New', 'directorist' ),
            'new_item'              => __( 'New Template', 'directorist' ),
            'edit_item'             => __( 'Edit Template', 'directorist' ),
            'update_item'           => __( 'Update Template', 'directorist' ),
            'view_item'             => __( 'View Template', 'directorist' ),
            'view_items'            => __( 'View Templates', 'directorist' ),
            'search_items'          => __( 'Search Template', 'directorist' ),
            'not_found'             => __( 'Not found', 'directorist' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'directorist' ),
            'featured_image'        => __( 'Featured Image', 'directorist' ),
            'set_featured_image'    => __( 'Set featured image', 'directorist' ),
            'remove_featured_image' => __( 'Remove featured image', 'directorist' ),
            'use_featured_image'    => __( 'Use as featured image', 'directorist' ),
            'insert_into_item'      => __( 'Insert into item', 'directorist' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'directorist' ),
            'items_list'            => __( 'Templates list', 'directorist' ),
            'items_list_navigation' => __( 'Templates list navigation', 'directorist' ),
            'filter_items_list'     => __( 'Filter templates list', 'directorist' ),
        ];
    
        $args = [
            'label'                 => __( 'Directorist Gutenberg Template', 'directorist' ),
            'description'           => __( 'Gutenberg templates for Directorist', 'directorist' ),
            'labels'                => $labels,
            'supports'              => [ 'title', 'editor', 'author', 'custom-fields' ],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=at_biz_dir',
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-admin-post',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rest_base'             => 'directorist-gutenberg-templates',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];
    
        register_post_type( 'directorist-template', $args );
    }

    public function register_blocks() {
        $path = ATBDP_DIR . 'template-blocks/init.php';

        if ( is_file( $path ) ) {
            require_once $path;
        }
    }

    public function add_deferred_props( array $deferred_props ) {
        $deferred_props[] = 'gbt_listings_grid_view_template_id';
        $deferred_props[] = 'gbt_listings_list_view_template_id';
        
        return $deferred_props;
    }

    public function maybe_set_listing_item_template_id( Directorist_Listings $listings_controller, array $args ) {
        if ( empty( $listings_controller->directory_type_id ) ) {
            return;
        }

        // $listings_controller->gbt_listings_grid_view_template_id = 38;
        // $listings_controller->gbt_listings_list_view_template_id = 44;

        // add_filter( 'directorist_render_custom_template_for_listings_loop_item', '__return_true', 10 );
    }

    public function render_listings_loop_item_custom_template( Directorist_Listings $listings_controller, array $args ) {
        $template_id = $args['view_type'] === 'grid' ? $listings_controller->gbt_listings_grid_view_template_id : $listings_controller->gbt_listings_list_view_template_id;
        
        Helper::get_template( 'gutenberg/archive/listing-item', [ 'template_id' => $template_id ] );
    }
};