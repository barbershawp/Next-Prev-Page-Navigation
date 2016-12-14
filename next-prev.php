<?php
		$parents = get_post_ancestors( $post );		
		$current_page_parent = $post->post_parent;
				
		$pagelist = get_pages("child_of=".$current_page_parent."&parent=".$current_page_parent."&sort_column=menu_order&sort_order=asc");
		
		$pages = array();
		foreach ($pagelist as $page) {
		   $pages[] = $page->ID;
		}
		
		//print_r($pages);
		
		$current = array_search($post->ID, $pages);
		
		//PREV LINK
		
		if(isset($pages[$current-1])) {
			$prevID = $pages[$current-1];
		} elseif(isset($parents[0])) {
			$prevID = $parents[0];	
		} 
				
		//NEXT LINK
		$child_pages = get_pages(array(
			'child_of' => $pages[$current],
			'parent' => $pages[$current],
			'sort_column'=>'menu_order',
			'sort_order'=>'asc',
		));
			
		if(!empty($child_pages)) {
			
			$nextID = $child_pages[0]->ID;
		
		} elseif(isset($pages[$current+1])) {
			
			$nextID = $pages[$current+1];
		
		} elseif(!empty($parents)) {
			
			for($i=0; $i<count($parents);$i++) {
								
				if(!isset($parents[$i+1])) {
					$child_of = 0;
				} else {
					$child_of = $parents[$i+1];
				}
				
				$parent_pages = get_pages(array(
					'child_of' => $child_of,
					'parent' => $child_of,
					'sort_column'=>'menu_order',
					'sort_order'=>'asc',
				));
									
				$parent_pages_array = array();
				foreach ($parent_pages as $page) {
				   if($page->post_parent == $child_of) {
				   	$parent_pages_array[] = $page->ID;
				   }
				}
				
				if(isset($parents[$i+1])) {
					$thing = $parents[$i];
				} else {
					$thing = $parents[count($parents)-1];
				}
				
				if(in_array($thing, $parent_pages_array)) {
					$current_parent = array_search($thing, $parent_pages_array);
				} else {
					$current_parent = 0;
				}
				
					
				if(isset($parent_pages_array[$current_parent+1])) {
					$nextID = $parent_pages_array[$current_parent+1];
					break;
				}
				
				
			}
			
		}
		
		?>
