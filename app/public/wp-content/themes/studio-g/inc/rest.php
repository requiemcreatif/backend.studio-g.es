<?php

// function register_routes()
// {
//     register_rest_route('rest/v1', 'frontpage', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'frontpage',
//     ));

//     register_rest_route('rest/v1', 'masterdata', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'master_data',
//     ));

//     register_rest_route('rest/v1', 'redirects', array(
//         'methods'  => WP_REST_Server::READABLE,
//         'callback' => 'redirections'
//     ));

//     register_rest_route('rest/v1', 'out\/(?P<outID>[_a-zA-Z0-9\s]+)\/?(?P<pageID>[0-9]+)?', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'out_url'
//     ));

//     register_rest_route('rest/v1', 'sitemap-index', array(
//         'methods'  => WP_REST_Server::READABLE,
//         'callback' => 'expose_sitemap_index',
//     ));

//     register_rest_route('rest/v1', 'sitemap-single/(?P<slug>[_-a-zA-Z\s]+)', array(
//         'methods'  => WP_REST_Server::READABLE,
//         'callback' => 'expose_sitemap_single',
//     ));

//     register_rest_route('rest/v1', 'archive/(?P<post_type>[\w\W]+)', array(
//         'methods'  => WP_REST_Server::READABLE,
//         'callback' => 'archive_data',
//     ));

//     register_rest_route('rest/v1', 'toplist/(?P<id>[\d]+)', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'rest_get_toplist',
//         'args' => array(
//             'id' => array(
//                 'validate_callback' => function ($param, $request, $key) {
//                     return is_numeric($param);
//                 }
//             ),
//         ),
//     ));

//     register_rest_route('rest/v1', 'preview/(?P<id>[\d]+)', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'get_preview_content',
//         'args' => array(
//             'id' => array(
//                 'validate_callback' => function ($param, $request, $key) {
//                     return is_numeric($param);
//                 }
//             ),
//         ),
//     ));

//     register_rest_route('rest/v1', 'search', array(
//         'methods' => WP_REST_Server::READABLE,
//         'callback' => 'search',
//     ));
// }

// add_action('rest_api_init', 'register_routes');

// /* ---------- Rest Functions ---------- */

// function frontpage()
// {
//     // Get the frontpage ID and data
//     $frontpage_id = get_option('page_on_front');
//     wp_send_json(general_response($frontpage_id));
// }

// function master_data()
// {
//     $response = array(
//         'menus' => array(
//             'sidebar' => HCMS_Service::build_menu(wp_get_nav_menu_items('sidebar'), 0),
//         ),
//         'footer' => get_field('footer_settings', 'option'),
//         '404_pop_links' => HCMS_Contents::err_page_data(),
//         'archive_permalinks' => array(
//             'casinos' => HCMS_Service::filter_link(get_the_permalink(get_field('brand_archive', 'option'))),
//             'news' => HCMS_Service::filter_link(get_the_permalink(get_field('news_archive', 'option'))),
//             'campaigns' => HCMS_Service::filter_link(get_the_permalink(get_field('campaigns_archive', 'option'))),
//             'guides' => HCMS_Service::filter_link(get_the_permalink(get_field('guides_archive', 'option'))),
//         )
//     );
//     wp_send_json($response);
// }

// function redirections()
// {
//     global $wpdb;
//     $results = $wpdb->get_results("SELECT * FROM wp_redirection_items WHERE status = 'enabled' AND action_code = 301");
//     $redirections = [];
//     foreach ($results as $item) {
//         $url = $item->url;
//         $redirections[$url] = $item->action_data;
//     }
//     return $redirections;
// }

// function out_url(WP_REST_Request $request)
// {
//     $outID = $request->get_param('outID');
//     $pageID = $request->get_param('pageID');

//     $outlink_data = HCMS_Service::get_outlink_data($outID, $pageID);

//     return $outlink_data;
// }

// function expose_sitemap_index()
// {
//     $post_types = [];
//     foreach (ALL_POST_TYPES as $pt) {
//         $x['slug'] = $pt;
//         $lastmod = get_posts([
//             'posts_per_page' => 1,
//             'post_type' => $pt,
//             'post_status' => 'publish',
//             'orderby' => 'modified',
//             'order' => 'DESC'
//         ]);
//         $x['lastmod'] = date("Y-m-d\TH:i:s+00:00", strtotime($lastmod[0]->post_modified_gmt));
//         $post_types[] = $x;
//     }
//     return $post_types;
// }

// function expose_sitemap_single(WP_REST_Request $request)
// {
//     $post_type = $request->get_param('slug');
//     return HCMS_Service::get_sitemap_data($post_type);
// }

// function archive_data(WP_REST_Request $request)
// {
//     $post_type = $request->get_param('post_type');
//     $page_num = $request->get_param('paged') ? $request->get_param('paged') : 1;

//     switch ($post_type) {
//         case 'calendars':
//             return HCMS_Service::get_all_archive_items('calendars', 6, [], true, $page_num);
//             break;

//         case 'guides':
//             return HCMS_Service::get_all_archive_items('guides', 6, ['image'], true, $page_num);
//             break;

//         case 'news':
//             return HCMS_Service::get_all_archive_items('news', 12, ['image'], true, $page_num);
//             break;

//         case 'campaigns':
//             return HCMS_Service::get_all_archive_items('campaigns', 12, ['image'], true, $page_num);
//             break;

//         case 'brand':
//             return HCMS_Service::get_all_archive_items('brand', 12, ['banner'], true, $page_num);
//             break;

//         case 'slots':
//             return HCMS_Service::get_all_archive_items('slots', 8, ['image', 'rating', 'game_provider'], false, $page_num);

//         case 'game_providers':
//             return HCMS_Service::get_all_archive_items('game_providers', 8, ['logo'], false, $page_num);
//             break;

//         default:
//             break;
//     }
// }

// function rest_get_toplist(WP_REST_Request $request)
// {
//     $id = $request->get_param('id');
//     $toplist = HCMS_Service::get_hub_toplist($id);

//     if (!$toplist) {
//         return new WP_Error('no_toplist', 'No toplist found', array('status' => 404));
//     } else {
//         return $toplist;
//     }
// }

// function get_preview_content(WP_REST_Request $request)
// {
//     wp_send_json(general_response(intval($request->get_param('id')), true));
// }

// function search(WP_REST_Request $request)
// {
//     // Get params.
//     $query_params = $request->get_query_params();
//     $q = isset($query_params['q']) ? (string)$query_params['q'] : null;
//     if ($q == '') {
//         return new WP_Error('bad_data', 'Please define search phrase q', array('status' => 400));
//     }
//     $search_results = [];
//     $results_count = 0;

//     foreach (ALL_POST_TYPES as $post_type) {
//         // Search posts
//         add_filter('posts_search', 'HCMS_Service::title_filter', 10, 2);
//         $query = new WP_Query(array(
//             's'                => $q,
//             'post_type'        => $post_type,
//             'posts_per_page'   => 500,
//             'orderby'          => 'date',
//             'order'            => 'DESC',
//             'post_status'      => 'publish',
//         ));
//         remove_filter('posts_search', 'HCMS_Service::title_filter');
//         $posts = $query->posts;
//         $results_count += count($posts);

//         // Get search results
//         $search_results[$post_type] =  $posts ? array_map(function (WP_Post $post) {
//             return HCMS_Service::get_post_basic_data($post->ID);
//         }, $posts) : [];
//     }

//     // Response.
//     wp_send_json(array(
//         'search_phrase' => $q,
//         'search_results' => $search_results,
//         'results_count' => $results_count,
//     ));
// }
