<?php

/**
 * A Contents class containing functions that can be used for generating content particles response
 **/

class HCMS_Contents
{
    public static function get_post_data($post_id, $custom_fields = null, $include_author_data = false)
    {
        $post_data = array(
            'ID' => $post_id,
            'post_date' => get_the_date('Y-m-d', $post_id) . ' ' . (get_post_time('H:i:s', $post_id) ? get_post_time('H:i:s', $post_id) : '00:00:00'),
            'post_modified' => get_the_modified_date('Y-m-d', $post_id) . ' ' . get_the_modified_time('H:i:s', $post_id),
            'post_title' => html_entity_decode(get_the_title($post_id)),
            'post_excerpt' => html_entity_decode(get_the_excerpt($post_id)),
            'post_type' => HCMS_Service::filter_post_type_value($post_id),
            'permalink' => HCMS_Service::filter_link(get_the_permalink($post_id)),
        );

        
        if ($include_author_data) {
            $author_id = get_post_field('author_biography', $post_id)
                ? get_post_field('author_biography', $post_id)
                : get_post_field('post_author', $post_id);
            $hide_short_description = get_field('show_short_description', $post_id) ? get_field('show_short_description', $post_id) : false;
            $post_data['author'] = HCMS_Service::get_author_bio($author_id, $hide_short_description);
        }

        $post_type = $post_data['post_type'];

        if ($post_type === 'archive') {
            foreach (ARCHIVED_POST_TYPES as $pt) {
                if (get_field($pt . '_archive', 'option') === $post_id) {
                    $post_data['archive_type'] = $pt;
                    $custom_fields = [];
                }
            }
        }
        // Get all custom fields data
        $acf_data = self::get_acf_fields($post_id, $custom_fields) ?: [];

        // if ($post_type === 'brand') {
        //     unset($acf_data['game_providers']);
        //     $acf_data['is_promoted'] = hub_promote_brand($post_id);
        //     $acf_data['rating'] = HCMS_Service::calculate_average_rating($post_id);
        //     if (isset($acf_data['inbanner'])) {
        //         foreach ($acf_data['inbanner'] as $key => $value) {
        //             if (empty($value)) {
        //                 unset($acf_data['inbanner'][$key]);
        //             }
        //         }
        //         if (empty($acf_data['inbanner'])) {
        //             unset($acf_data['inbanner']);
        //         }
        //     }
        // }

        // if ($post_type === 'campaigns' || $post_type === 'calendars') {
        //     $acf_data['expiry_date_passed'] = HCMS_Service::get_expiry_date_passed($acf_data['expiry_date']);
        // }

        // if ($post_type === 'slots') {
        //     $acf_data['jackpot_value'] = fetch_jackpot_value($post_id);
        // }

        //return array_merge($post_data, $acf_data);
    }

    public static function get_acf_fields($post_id, $custom_fields)
    {
        if ($custom_fields) {
            foreach ($custom_fields as $cf) {
                $response[$cf] = get_field($cf, $post_id);
            }
        } else if (gettype($custom_fields) === 'array' && count($custom_fields) === 0) {
            return;
        } else {
            $response = get_fields($post_id);
        }

        $ret_obj = [];

        // foreach ($response as $field_name => $field_value) {
        //     if ($field_name === 'jackpotSlots' && $field_value) {
        //         $ret_obj['jackpotUpdateTime'] = get_field('jackpot_updated', 'option');
        //     }

        //     if ($field_name === 'show_latest_author_posts' && $field_value === true) {
        //         $field_name = 'author_latest_posts';
        //     }

        //     $val = HCMS_Service::filter_field_value($field_name, $field_value, $post_id);

        //     // Unset any null values
        //     if ($val === null) {
        //         unset($ret_obj[$field_name]);
        //     } else {
        //         $ret_obj[$field_name] = $val;
        //     }
        // }

        return $ret_obj;
    }







    



}
