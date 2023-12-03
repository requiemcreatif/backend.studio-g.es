<?php

function my_acf_init()
{
    // check function exists
    if (function_exists('acf_register_post')) {
        acf_register_block(array(
            'name'                => 'news_content',
            'title'               => 'news content',
            'description'         => 'news content',
            'category'            => 'formatting',
            'icon'                => 'editor-justify',
            'mode'                => 'edit',
            'keywords'            => array('news_content', 'post', 'acf'),
        ));

        

    }
}

//Add custom Block types
add_action('acf/init', 'my_acf_init');
