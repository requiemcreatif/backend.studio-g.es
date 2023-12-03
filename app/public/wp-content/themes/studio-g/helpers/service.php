<?php

/**
 * A Service class containing general helper functions that can be used inside the headless theme:
 *  - Filtering functions, which are about to mutate some incoming data
 *  - Helper functions which are about to represent contents response in a correct manner
 *  - Single item data collectors
 **/

class HCMS_Service
{
    /* -------------------- Filtering functions start -------------------- */

    public static function sanitize_url($url)
    {
        $ret = trim($url, '/'); //trim trailing and leading slashes
        return '/' . $ret; //add slash to beginning
    }

    /*public static function filter_image_data($image, $concise = false)
    {
        $keys_to_copy = $concise
            ? [
                'url',
                'alt',
            ] : [
                'url',
                'alt',
                'width',
                'height',
            ];

        foreach ($keys_to_copy as $var) {
            if (isset($image[$var])) {
                $new_image[$var] = $image[$var];
            }
        }

        $new_image['url'] = self::filter_link($new_image['url']);
        return $new_image;
    }*/

    public static function filter_post_type_value($post_id)
    {
        foreach (ARCHIVED_POST_TYPES as $pt) {
            if (get_field($pt . '_archive', 'option') === $post_id) {
                return 'archive';
            }
        }
        return get_post_type($post_id);
    }

    public static function filter_field_value($field_name, $value, $post_id)
    {
        switch ($field_name) {
            

            case 'casino_for_the_slot':
                return $value
                    ? self::get_brand_flexible_data($value->ID, ['logo', 'go_url', 'terms_and_conditions'])
                    : null;

            
            case 'benefits':
                return $value
                    ? array_values(array_filter(array_map(function ($x) {
                        return $x['benefit'] ? $x['benefit'] : null;
                    }, $value)))
                    : null;



            default:
                return $value;
        }
    }


    private static function resolve_post_id($id)
    {
        return (gettype($id) == 'integer') ? $id : $id->ID;
    }

    public static function map_ids($latest)
    {
        return array_map(function ($x) {
            return $x->ID;
        }, $latest);
    }

    /*public static function build_menu(array &$menu_items, $parentId = 0)
    {
        $branch = array();
        foreach ($menu_items as &$item) {
            if ($item->menu_item_parent == $parentId) {
                $children = self::build_menu($menu_items, $item->ID);

                $item_obj = [
                    'id' => $item->ID,
                    'object_id' => $item->object_id,
                    'parent_id' => $item->menu_item_parent,
                    'title' => $item->title,
                    'post_type' => $item->object,
                    'slug' => wp_make_link_relative($item->url)
                ];

                //An array of child menu items, if any.
                if ($children) {
                    $item_obj['wpse_children'] = $children;
                }

                $branch[] = $item_obj;
                unset($item);
            }
        }
        return $branch;
    }*/




    /*public static function get_single_item($post_id)
    {
        $post_data['id'] = $post_id;
        $post_data['title'] = html_entity_decode(get_the_title($post_id));
        $post_data['link'] = self::filter_link(get_the_permalink($post_id));
        return $post_data;
    }*/
    /* -------------------- single item data collectors end -------------------- */

    
    public static function get_latest_posts_by_cpt($post_type, $nr_of_posts = 2, $fields = ['image'])
    {
        $posts = get_posts(array(
            'posts_per_page' => $nr_of_posts,
            'post_type' => $post_type,
            'post_status' => 'publish'
        ));

        $res = [];
        foreach ($posts as $p) {
            $res[] = HCMS_Contents::get_post_data($p->ID, $fields);
        };
        return $res;
    }



    public static function get_posts_fields($args = array())
    {
        $valid_fields = array(
            'ID' => '%d', 'post_author' => '%d',
            'post_type' => '%s', 'post_mime_type' => '%s',
            'post_title' => false, 'post_name' => '%s',
            'post_date' => '%s', 'post_modified' => '%s',
            'menu_order' => '%d', 'post_parent' => '%d',
            'post_excerpt' => false, 'post_content' => false,
            'post_status' => '%s', 'comment_status' => false, 'ping_status' => false,
            'to_ping' => false, 'pinged' => false, 'comment_count' => '%d'
        );
        $defaults = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'posts_per_page' => get_option('posts_per_page'),
        );
        global $wpdb;
        $args = wp_parse_args($args, $defaults);
        $where = "";
        foreach ($valid_fields as $field => $can_query) {
            if (isset($args[$field]) && $can_query) {
                if ($where != "") $where .= " AND ";
                $where .= $wpdb->prepare($field . " = " . $can_query, $args[$field]);
            }
        }
        if (isset($args['search']) && is_string($args['search'])) {
            if ($where != "") $where .= " AND ";
            $where .= $wpdb->prepare("post_title LIKE %s", "%" . $args['search'] . "%");
        }
        if (isset($args['include'])) {
            if (is_string($args['include'])) $args['include'] = explode(',', $args['include']);
            if (is_array($args['include'])) {
                $args['include'] = array_map('intval', $args['include']);
                if ($where != "") $where .= " OR ";
                $where .= "ID IN (" . implode(',', $args['include']) . ")";
            }
        }
        if (isset($args['exclude'])) {
            if (is_string($args['exclude'])) $args['exclude'] = explode(',', $args['exclude']);
            if (is_array($args['exclude'])) {
                $args['exclude'] = array_map('intval', $args['exclude']);
                if ($where != "") $where .= " AND ";
                $where .= "ID NOT IN (" . implode(',', $args['exclude']) . ")";
            }
        }
        extract($args);
        $iscol = false;
        if (isset($fields)) {
            if (is_string($fields)) $fields = explode(',', $fields);
            if (is_array($fields)) {
                $fields = array_intersect($fields, array_keys($valid_fields));
                if (count($fields) == 1) $iscol = true;
                $fields = implode(',', $fields);
            }
        }
        if (empty($fields)) $fields = '*';
        if (!in_array($orderby, $valid_fields)) $orderby = 'post_date';
        if (!in_array(strtoupper($order), array('ASC', 'DESC'))) $order = 'DESC';
        if (!intval($posts_per_page) && $posts_per_page != -1)
            $posts_per_page = $defaults['posts_per_page'];
        if ($where == "") $where = "1";
        $q = "SELECT $fields FROM $wpdb->posts WHERE " . $where;
        $q .= " ORDER BY $orderby $order";
        if ($posts_per_page != -1) $q .= " LIMIT $posts_per_page";
        return $iscol ? $wpdb->get_col($q) : $wpdb->get_results($q);
    }


}



