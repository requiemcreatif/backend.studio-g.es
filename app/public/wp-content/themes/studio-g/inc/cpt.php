<?
if (! function_exists('unregister_post_type')) :
    function unregister_post_type($post_type){
        global $wp_post_types;
        if (isset($wp_post_types[ $post_type ])) {
            unset($wp_post_types[ $post_type ]);
            return true;
        }
        return false;
    }
endif;

function create_labels($name){
    return array(
        'name'                  => $name.'s',
        'singular_name'         => $name,
        'menu_name'             => $name.'s',
        'name_admin_bar'        => $name,
        'archives'              => $name.' Archives',
        'attributes'            => $name.' Attributes',
        'parent_item_colon'     => 'Parent '.$name.':',
        'all_items'             => 'All '.$name.'s',
        'add_new_item'          => 'Add New '.$name,
        'add_new'               => 'Add New',
        'new_item'              => 'New '.$name,
        'edit_item'             => 'Edit '.$name,
        'update_item'           => 'Update '.$name,
        'view_item'             => 'View '.$name,
        'view_items'            => 'View '.$name.'s',
        'search_items'          => 'Search '.$name.'s',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'insert_into_item'      => 'Insert into item',
        'prev_text'             => 'Previous',
        'next_text'             => 'Next',
        'uploaded_to_this_item' => 'Uploaded to this item',
        'items_list'            => $name.'s list',
        'items_list_navigation' => $name.'s list navigation',
        'filter_items_list'     => 'Filter '.$name.'s list',
    );
}

function custom_post_types()
{
    register_post_type('campaigns', array(
        'label'                 => 'Campaigns',
        'description'           => 'Campaigns Post Type',
        'labels'                => create_labels('Campaign'),
        'supports'              => array('editor', 'title', 'revisions', 'author'),
        'taxonomies'            => array( 'post_tag' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'campaigns',
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    ));
    add_post_type_support('campaigns', 'excerpt');
    add_post_type_support('campaigns', 'revisions');

    register_post_type('calendars', array(
        'label'                 => 'Calendars',
        'description'           => 'Calendars Post Type',
        'labels'                => create_labels('Calendar'),
        'supports'              => array('editor', 'title', 'revisions', 'author'),
        'taxonomies'            => array( 'post_tag' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'calendars',
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    ));
    add_post_type_support('calendars', 'excerpt');
    add_post_type_support('calendars', 'revisions');

    register_post_type('brand', array(
        'label'                 => 'Brands',
        'description'           => 'Brands Post Type',
        'labels'                => create_labels('Brand'),
        'supports'              => array('editor', 'title', 'revisions', 'author'),
        'taxonomies'            => array( 'post_tag' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'brands',
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    ));
    add_post_type_support('brand', 'revisions');


    /*register_post_type('game_providers', array(
        'label'                 => 'Game Providers',
        'description'           => 'Game Providers Post Type',
        'labels'                => create_labels('Game Provider'),
        'supports'              => array('editor', 'title', 'revisions', 'author'),
        'taxonomies'            => array( 'post_tag' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'game-providers',
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    ));

    add_post_type_support('game_providers', 'revisions');*/

}

add_action('init', 'custom_post_types', 0);

?>