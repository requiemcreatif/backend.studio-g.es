<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */
define('ALL_POST_TYPES', array(
    'page',
    'post',
));

// Helper functions
require_once 'helpers/service.php';
require_once 'helpers/contents.php';
require_once 'helpers/blocks.php';

// Custom Post Types
require_once __DIR__ . '/inc/cpt.php';

// Add ACF json functions
require_once __DIR__ . '/inc/acf.php';

// REST api.
require_once __DIR__ . '/inc/rest.php';



/**
 * Modifies the path where the Advanced Custom Fields (ACF) plugin saves its JSON files.
 *
 * @param $path
 * @return string
 */
function acf_json_save_point($path)
{
    // update path
    $path = get_template_directory() . '/acf-json';

    // return
    return $path;
}

add_filter('acf/settings/save_json', 'acf_json_save_point');


/**
 * Modifies the paths where the Advanced Custom Fields (ACF) plugin looks for its JSON files.
 *
 * @param $paths
 * @return mixed
 */
function acf_json_load_point($paths)
{
    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_template_directory() . '/acf-json';

    // return
    return $paths;
}

add_filter('acf/settings/load_json', 'acf_json_load_point');



/**
 * add_theme_caps function
 * adds 'unfiltered_html' capability to administrator only
 * admin can add script tags into content and shortcodes without getting stripped out
 * required for advert banner shortcode, do not use this for non admin roles!
 */
function add_theme_caps()
{
    // gets the author role
    $role = get_role('administrator');

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap('unfiltered_html');
}

add_action('admin_init', 'add_theme_caps');


// general response function
function general_response($post_id, $is_preview = false)
{
    global $post;
    $post = get_post($post_id, OBJECT);
    // get post general data
    $ret_obj = HCMS_Contents::get_post_data($post_id, null, true);

    $post_blocks = array();
    $count = 0;
    $post_content = get_post_field('post_content', $post_id);
    if (has_blocks($post_content)) {
        $blocks = parse_blocks($post_content);
        foreach ($blocks as $block) {
            $map = HCMS_Blocks::map_block($block);
            if ($map) {
                //merge blocks to optimize DOM
                switch ($map->blockName) {
                        // merge `html` block if previous block was an `html` block
                    case "html": {
                            if ($count > 0 && $post_blocks[$count - 1]->blockName === "html") {
                                $post_blocks[$count - 1]->innerHTML .= HCMS_Service::filter_link($map->innerHTML);
                                break;
                            }
                        }

                    default: {
                            $count++;
                            array_push($post_blocks, $map);
                            break;
                        }
                }
            }
        }
    }

    if ($count > 0) {
        $ret_obj['post_blocks'] = $post_blocks;
    } else {
        $ret_obj['post_blocks'] = [
            array(
                'blockName' => 'html',
                'innerHTML' => HCMS_Service::filter_link(get_post_field('post_content', $post_id))
            )
        ];
    }

    // $ret_obj['seo'] = HCMS_Contents::get_seo_data($post_id, $post_id == get_option('page_on_front') ? false : true);
    // $ret_obj['og'] = HCMS_Contents::get_og_data($post_id);
    // $ret_obj['breadcrumbs'] = HCMS_Contents::get_breadcrumbs_data($post_id);

    // if ($post_id == get_option('page_on_front')) {
    //     $ret_obj['latest_news'] = HCMS_Service::get_latest_posts_by_cpt('news', 1, ['image', 'image_mobile'])[0];
    //     $ret_obj['latest_campaigns'] = HCMS_Contents::get_active_campaigns() ? HCMS_Contents::get_active_campaigns()[0] : null;
    // }

    // $post_type = $ret_obj['post_type'];
    // if ($post_type === 'brand' || $post_type === 'campaigns' || $post_type === 'calendars' || $post_type === 'guides' || $post_type === 'news') {
    //     $ret_obj['top_casinos'] = HCMS_Contents::get_top_casinos_from_hub();
    // }

    // switch ($post_type) {
    //     case 'page':
    //         if ($post_id != get_option('page_on_front')) {
    //             $ret_obj['meta']['_wp_page_template'] = get_post_meta($post_id)['_wp_page_template'];
    //         }
    //         break;

    //     case 'brand':
    //         $brand_campaign = HCMS_Contents::get_brand_campaigns($post_id, true, 1)->posts[0];
    //         $fields = ['expiry_date', 'category', 'image'];
    //         $ret_obj['sidebar_campaign'] = $brand_campaign ? HCMS_Contents::get_post_data($brand_campaign->ID, $fields) : null;
    //         break;

    //     case 'campaigns':
    //         $active_campaigns = HCMS_Contents::get_active_campaigns(true);
    //         $ret_obj['active_campaign'] = $active_campaigns && ($post_id !== $active_campaigns[0]['ID'])
    //             ? $active_campaigns[0]
    //             : $active_campaigns[1];
    //         break;

    //     case 'guides':
    //         $latest_guides = HCMS_Service::get_latest_posts_by_cpt('guides');
    //         $ret_obj['latest_guides'] = $latest_guides && ($post_id !== $latest_guides[0]['ID'])
    //             ? $latest_guides[0]
    //             : $latest_guides[1];
    //         break;

    //     case 'news':
    //         $latest_news = HCMS_Service::get_latest_posts_by_cpt('news');
    //         $ret_obj['latest_news'] = $latest_news && ($post_id !== $latest_news[0]['ID'])
    //             ? $latest_news[0]
    //             : $latest_news[1];
    //         break;

    //     case 'slots':
    //         $ret_obj['game_provider'] = $ret_obj['game_provider']->post_title;
    //         break;

    //     case 'archive':
    //         $page_num = (get_query_var('paged')) ? get_query_var('paged') : 1;
    //         switch ($ret_obj['archive_type']) {
    //             case 'calendars':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('calendars', 6, [], true, $page_num);
    //                 break;

    //             case 'guides':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('guides', 6, ['image'], true, $page_num);
    //                 break;

    //             case 'news':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('news', 12, ['image'], true, $page_num);
    //                 break;

    //             case 'campaigns':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('campaigns', 12, ['image'], true, $page_num);
    //                 break;

    //             case 'brand':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('brand', 12, ['banner'], true, $page_num);
    //                 break;

    //             case 'slots':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('slots', 8, ['image', 'rating', 'game_provider'], false, $page_num);
    //                 break;

    //             case 'game_providers':
    //                 $ret_obj['archive_items'] = HCMS_Service::get_all_archive_items('game_providers', 8, ['logo'], false, $page_num);
    //                 break;

    //             default:
    //                 break;
    //         }

    //     default:
    //         break;
    // }

    // $is_public_sitemap = get_page_template_slug() === 'template-html-sitemap.php';
    // if ($is_public_sitemap) {
    //     $ret_obj['post_type'] = 'public_sitemap';
    //     $sitemap = [];
    //     $post_types = ['brand', 'game_providers', 'page'];
    //     foreach ($post_types as $pt) {
    //         $sitemap[$pt] = HCMS_Service::get_public_sitemap_data($pt);
    //     }

    //     $sitemap['categories'] = [];
    //     $cat_post_types = ['calendars', 'guides', 'news', 'campaigns',];
    //     foreach ($cat_post_types as $pt) {
    //         $archive_id = get_field($pt . '_archive', 'option');
    //         if ($archive_id) {
    //             $xml_data_object['id'] = $archive_id;
    //             $xml_data_object['title'] = html_entity_decode(get_the_title($archive_id));
    //             $xml_data_object['url'] = HCMS_Service::filter_link(get_permalink($archive_id));
    //             $sitemap['categories'][] = $xml_data_object;
    //         }
    //     }
    //     $ret_obj['sitemap'] = $sitemap;
    // }

    // if ($is_preview) {
    //     $ret_obj['seo']['noindex'] = true;
    // }

    return $ret_obj;
}
