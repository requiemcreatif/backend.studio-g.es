<?php

/**
 * A Blocks class containing functions that can be used for custom blocks handlinng
 **/

class HCMS_Blocks
{
	public static function map_block($block)
	{
		switch ($block['blockName']) {
			case 'core/columns':
				return self::headless_block('columns', $block['attrs']['className'], self::map_inner_block($block));

			case 'core/column':
				return self::headless_block('column', $block['attrs']['className'], self::map_inner_block($block));

			case 'core/list':
				return self::headless_block('list', $block['attrs']['className'], self::map_inner_block($block), $block['attrs']);

			case 'core/quote':
				return self::headless_block('quote', $block['attrs']['className'], self::map_inner_block($block));


			case 'acf/news-content':
				$block_fields = self::get_block_fields_acf($block);
				return self::headless_block(substr($block['blockName'], 4), $block['attrs']['className'], null, $block_fields);


                /*case 'acf/fortune-fetcher':
                    $block_fields = self::get_block_fields_acf($block);
                    $fortune_fetcher_data = [];
                
                    foreach ($block_fields['fortune_fetcher'] as $fetcher) {
                        $casino_id = $fetcher['casino'] ?? false;
                        $casino_details = false;
                
                        if ($casino_id) {
                            $casino_details = [
                                'id' => $casino_id,
                                'post_title' => html_entity_decode(get_the_title($casino_id)),
                                'go_url' => get_field('go_url', $casino_id),
                                'terms_and_conditions' => get_field('terms_and_conditions', $casino_id),
                            ];
                        }
                
                        $entry_data = [
                            'lottery_name' => $fetcher['lottery_name'],
                            'description' => $fetcher['description'],
                            'top_title' => $fetcher['top_title'], 
                            'top_description' => $fetcher['top_description'],
                            'casino' => $casino_details,
                            'image' => $fetcher['image'] ?? false,
                            'draw_one_color' => $fetcher['draw_one_color'] ?? false,
                            'draw_extra_color' => $fetcher['draw_extra_color'] ?? false,
                            'joker_color' => $fetcher['joker_color'] ?? false,
                        ];
                
                        $fortune_fetcher_data[] = $entry_data;
                    }
                
                    return self::headless_block('fortune-fetcher', $block['attrs']['className'], null, $fortune_fetcher_data);	*/		
    
                    // Sport Results Block
                /*case 'acf/sport-results':
                    $block_fields = self::get_block_fields_acf($block);
                    $sport_result_data = [];
                
                    foreach ($block_fields['sport_results'] as $fetcher) {
                        $casino_id = $fetcher['casino'] ?? false;
                        $casino_details = false;
                
                        if ($casino_id) {
                            $casino_details = [
                                'id' => $casino_id,
                                'post_title' => html_entity_decode(get_the_title($casino_id)),
                                'go_url' => get_field('go_url', $casino_id),
                                'terms_and_conditions' => get_field('terms_and_conditions', $casino_id),
                            ];
                        }
                
                        $entry_data = [
                            'product_name' => $fetcher['product_name'],
                            'result_type' => $fetcher['result_type'], // 'Results' or 'Odds
                            'description' => $fetcher['description'],
                            'top_title' => $fetcher['top_title'], 
                            'top_description' => $fetcher['top_description'],
                            'casino' => $casino_details,
                            'image' => $fetcher['image'] ?? false,
                            'theme_color' => $fetcher['theme_color'] ?? false,
                        ];
                
                        $sport_result_data[] = $entry_data;
                    }
                
                    return self::headless_block('sport-results', $block['attrs']['className'], null, $sport_result_data);*/
    
                    
            }
        }
			


	public static function map_inner_block($block)
	{
		$post_blocks = array();
		$count = 0;
		$total_inner_blocks = count($block['innerBlocks']);
		foreach ($block['innerBlocks'] as $inner_block) {
			//merge blocks to optimize DOM
			$map = self::map_block($inner_block);
			switch ($map->blockName) {
					// merge `html` block if previous block was an `html` block
				case "html": {
						if ($count > 0 && $post_blocks[$count - 1]->blockName === "html") {
							$post_blocks[$count - 1]->innerHTML .= $map->innerHTML;
							break;
						}
					}
				case "column": {
						$count++;
						$map->total_columns = $total_inner_blocks; // & here
						array_push($post_blocks, $map);
						break;
					}

				default: {
						$count++;
						array_push($post_blocks, $map);
						break;
					}
			}
			$ret[] = self::map_block($inner_block);
		}
		return $post_blocks;
	}

	public static function headless_block($block_name = 'default', $custom_css = '', $inner_blocks = null, $data = null, $inner_html = null)
	{
		$obj = new stdClass();
		$obj->blockName = $block_name;
		$obj->blockCss = $custom_css;

		if ($inner_html) {
			$obj->innerHTML .= HCMS_Service::filter_link($inner_html);
		}

		if ($inner_blocks !== null) {
			$obj->innerBlocksCount = count($inner_blocks);
			$obj->innerBlocks = $inner_blocks;
		}

		if ($data) {
			$obj->data = $data;
		}
		return $obj;
	}

	public static function get_block_fields_acf($block)
	{
		acf_setup_meta($block['attrs']['data'], $block['attrs']['id'], true);
		$fields = get_fields();

		// Restore global context
		acf_reset_meta($block['attrs']['id']);

		return $fields;
	}
}
