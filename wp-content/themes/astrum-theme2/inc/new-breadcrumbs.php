<?php 

/* new breadcrumbs */


function new_bc($menu_array, $item_parent_id, $array_title_and_url ){

	foreach ($menu_array as $items) {
		
		if ($items->ID == $item_parent_id ){
			//$array_bc[$items->ID] = $items->title;
			//array_push( $array_title_and_link['title'], $items->title );
			//array_push( $array_title_and_link['link'], $items->url );	
			$array_title_and_url['title'][] = $items->title;
			$array_title_and_url['url'][]  = $items->url;
			
			if ( isset( $items->menu_item_parent ) ) {
				
				$array_title_and_url = new_bc($menu_array, $items->menu_item_parent, $array_title_and_url);
			} 
			
		}
			
	}
	
	return $array_title_and_url;
	
}

/* Временный код */
/*function url_mode() {
	$url_params = explode('?', $_SERVER['REQUEST_URI'] )[1];
	$url_params_arr = explode('&', $url_params);
	$url_params_obj = [];
	foreach ($url_params_arr as $elem) {
		$elem_arr = explode('=', $elem);		
		$url_params_obj[$elem_arr[0]] = $elem_arr[1];
	}
	
	if ($url_params_obj['breadcrumbs_mode'] == 2) {		
		$breadcrumbs_class_container = 'breadcrumbs-container--second';
		//$space_ps = '';
	} else {
		$breadcrumbs_class_container = 'breadcrumbs-container--first';
		//$space_ps = '<div class="modern-terms-dot"></div>';
	}
	return $breadcrumbs_class_container;
}*/

function new_output($output){
	$new_output = [];
	$strip_output = strip_tags( $output );		
	$new_output = explode( '>',  $strip_output);
	
	return $new_output;
}

function new_bc_output($array_title_and_url, $bsa){
	$array_title_reverse = array_reverse($array_title_and_url['title']);
	$array_url_reverse   = array_reverse($array_title_and_url['url']);
	
	$j = 0;
	$duplicate_el = '';//$array_url_reverse[0];
	$index;
	foreach ($array_url_reverse as $el) {
		if ( $duplicate_el == $el) {
			$index = $j;
			//$duplicate_el = $el;
			unset($array_title_reverse[$index]);
			unset($array_url_reverse[$index]);			
		} else {
			//$index = -1;
			$duplicate_el = $el;			
		}
		
		$j++;		
	}
	$k = 0;
	foreach ( $array_title_reverse as $el ){ 
		$k += strlen($el);
	}
	$k = $k+4;
	if ( $k > 100 ) {
		$cut_count = 20;//6;
		//$str_bctip = 'data-bctip="'.$el.'"';
		$str_bctip="";
	} else {
		$cut_count = 25;//8;
		$str_bctip="";
		//$str_bctip = 'bctip="'.$el.'"';
	}
	if ( wp_is_mobile() ) { $new_title_str = get_the_title(); $str_bctip=""; } //else { $output = '<a href="'.$homeLink.'">Home</a>'.$bsa; }
	
	$homeLink = home_url('/');
	$arr_count = count( $array_title_reverse ) - 1;
	if ( wp_is_mobile() && $arr_count > 1  ) {$output = ''; }else { $output = '<a href="'.$homeLink.'">Home</a>'.$bsa; }
	$i = 0;
	foreach ( $array_title_reverse as $el ){
		
		if ($i == $arr_count){//<span class="hover-current_element">'.$el.'</span>
			if ( count(  explode( ' ', $el)  ) > 6 ) { 
				if ( wp_is_mobile() ) {
					$cut_count = 45;
				}
				$output .= /*$bsa.*/'<span class="breadcrumbs-current-elemet" '.$str_bctip.' ><span class="current_element">'.cut_title_bc($el, $cut_count).'</span></span>'; 
			} else {
				$output .= /*$bsa.*/'<span class="breadcrumbs-current-elemet"  ><span class="current_element">'.$el.'</span></span>';//cut_title_bc(, $cut_count)
			}
		} else {
			$output .= /*$bsa.*/'<a href="'.$array_url_reverse[$i].'" >'.$el.'</a>'.$bsa;//cut_title_bc($el, $cut_count)
		}		
		$i++;
	}
		
	return $output;
}

function cut_title_bc($title, $count){
	$new_title_arr = explode( ' ', $title );
	if (count($new_title_arr) > $count)
	{
		$dot = '...';
	} else {$dot = '';}
	array_splice( $new_title_arr , $count, count($new_title_arr) );
	$new_title_str = implode(' ', $new_title_arr);
	return $new_title_str.$dot;
}

function get_menu_id_and_menu_url_arr($this_url, $menu_terms){
	$menu_url_array = [];
	foreach ( $menu_terms as $menu_item ) {
		$menu_url_array[] = $menu_item->url;
		if (   $this_url == $menu_item->url ) {
			$find_ttl = $menu_item->title;			
		}
		
		if (  $menu_item->title == $find_ttl ) {
			$this_id = $menu_item->ID;
		} 
	}
	$output['this_id'] = $this_id;
	$output['menu_url_array'] = $menu_url_array;
	return $output;
}


function nd_breadcrumbs() {
	

	$menu_terms = wp_get_nav_menu_items('Menu');
	$this_url = $_SERVER['SCRIPT_URI'];
	$c = 0;
	$find_ttl = get_the_title();
	$array_itm = [];
	$array_title_and_url = [];	
	

	$get_menu_id_and_menu_url_arr = get_menu_id_and_menu_url_arr($this_url, $menu_terms);
	$this_id = $get_menu_id_and_menu_url_arr['this_id'];
	$menu_url_array = $get_menu_id_and_menu_url_arr['menu_url_array'];
	$new_bc = new_bc( $menu_terms, $this_id, $array_title_and_url );
	
	//var_dump($new_bc['title']);
	//echo '<br><br><br>';
	//echo '<pre>'; var_dump($_SERVER); echo '</pre>'; 
	//echo '<pre>'; var_dump($menu_terms); echo '</pre>'; 
	
//$menuitems = wp_get_menu_array('Menu');
//echo '<pre>'; var_dump( $menuitems ); echo '</pre>'; 


	
  $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = ''; // delimiter between crumbs
  $home = __('Home','purepress'); // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current_element">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
  $bsa = '<span class="breadcrumbs-separator-arrows"></span>';//&nbsp;>&nbsp;	
  $zpt = '&nbsp;/&nbsp;';	
	
  global $post;
  $homeLink = home_url('/');
  $frontpageuri = astrum_get_posts_page('url');
  $frontpagetitle = ot_get_option('pp_blog_page');
  $output = '';
  if (is_home() || is_front_page()) { 
    if ($showOnHome == 1)
      //echo '<nav id="breadcrumbs"><ul><li>';
      //_e('You are here:','purepress');    
	  echo '<a href="' . $homeLink . '"></i>' . $home . '</a>'.$bsa. '<span class="breadcrumbs-current-elemet" >'.$frontpagetitle.'</span>';
      //echo '</ul></nav>';	
  } else { 
	if ( wp_is_mobile() ) {
		$home_page_elem = '';
	} else {
		$home_page_elem = '<a href="' . $homeLink . '">' . $home . '</a>'.$bsa;	
	}
	
    $output .= $home_page_elem.'<span class="breadcrumbs-current-elemet" >'.$delimiter.'</span>';

    if ( is_category() ) { //echo 1;
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) $output .= '<li>'.get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ').'<li>';
	  if ( get_query_var('paged') ) {
		  //echo 1;
		  $this_url = str_replace('page/'.get_query_var('paged').'/', '', $this_url);
		  $this_id = get_menu_id_and_menu_url_arr($this_url, $menu_terms)['this_id'];
	  } else { //echo 2; 
			 }
	  //echo $this_url;
      $output .= $before . '<span class="breadcrumbs-current-elemet" title="'. single_cat_title('', false) .'">Archive by category "' . single_cat_title('', false) . '"</span>' . $after;
		if (  in_array( $this_url, $menu_url_array )  ) {
			//echo 234;
			//var_dump( $new_bc );
			//echo 'jjjj'.$this_id;
			
			$new_bc = new_bc( $menu_terms, $this_id, $array_title_and_url );
			$output = new_bc_output($new_bc, $bsa);
		} 

    } elseif ( is_search() ) {//echo 2;
      $output .= $before .'<span class="breadcrumbs-current-elemet" title="'. get_search_query() .'">Search results for "' . get_search_query() . '"</span>' . $after;

    } elseif ( is_day() ) {//echo 3;
      $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
      $output .= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
      $output .= $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {//echo 4;
      $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' </li>';
      $output .= $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {//echo 5;
      $output .= $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) { //echo 6;
      if ( get_post_type() != 'post' ) {//echo 7;		
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
		$cur_terms = get_the_terms( $post->ID, 'filters' );
		$bd_str = $post_type->labels->singular_name.$cur_terms[0]->name.$cur_terms[1]->name.get_the_title();
		$length_mobile_str = $post_type->labels->singular_name.$cur_terms[0]->name;
		//echo $bd_str.'<br>';
		//echo $count_bd_str = strlen($bd_str) + 4;   
		$i = 0;
		$ct_length = count( $cur_terms );
			if( is_array( $cur_terms ) ){
				$terms_output = '';
				//$terms_output = '<span class="breadcrumbs-terms-container"><a href="'. get_term_link( $cur_terms[0]->term_id, $cur_terms[0]->taxonomy ) .'">'.$cur_terms[0]->name.'...</a><span class="breadcrumbs-terms-wrapper">';
				foreach( $cur_terms as $cur_term ){
					if ( $i == $ct_length - 1 ) { $zpt = ''; }
					//$terms_output .= '<span class="breadcrumbs-term">'. $cur_term->name .'</span>'.$zpt;
					$terms_output .= $cur_term->name . $zpt;
					$i++;
				}
				if ( isset($cur_terms[1]) && !wp_is_mobile() ) {
					if ( isset($cur_terms[2]) ) {$three_net = '...';} else { $three_net = '';}
					$second_terms = ' / <a href="'. get_term_link( $cur_terms[1]->term_id, $cur_terms[1]->taxonomy ) .'">'.$cur_terms[1]->name.'</a>'.$three_net;
				} else {
					//if ( isset($cur_terms[1])  ) { $three_net = '...'; }  else { $three_net = ''; }
					$second_terms = '';
				}
				if ( count($cur_terms) > 2 && !wp_is_mobile() ){
					$bctip = 'data-bctip="'.$terms_output.'"';
				} else {
					$bctip = '';
				}
				
				$terms_output = '<span class="breadcrumbs-terms-container" '.$bctip.'><a href="'. get_term_link( $cur_terms[0]->term_id, $cur_terms[0]->taxonomy ) .'">'.$cur_terms[0]->name.'</a>'.$second_terms.'<span class="breadcrumbs-terms-wrapper">';
				$terms_output .= '</span></span>';
			}
		//$str_bctip = 'data-bctip="'.$el.'"';
        $output .= '<a href="' . $homeLink . /*'/' .*/ $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>'.$bsa;
		  $output .= $terms_output;
		  
		if ( wp_is_mobile() ) { 
			if ( strlen($length_mobile_str)  >  25)  { $n_count = 2; } 
			else { $n_count = 3;}  $new_title_str = get_the_title(); $str_bctip="";} 
		else {
			$new_title_str = cut_title_bc(get_the_title(), 10);  
			if ( strlen($new_title_str) < strlen( get_the_title() )  ) { $str_bctip = 'data-bctip="'.get_the_title().'"'; } 
			else {$str_bctip = ''; } 
		};
										
        if ($showCurrent == 1) { $output .= ' ' . $delimiter .$bsa. ' <span class="breadcrumbs-current-elemet" '.$str_bctip.'><span class="current_element">' . $new_title_str .'</span></span>'; }
		
		  
		  //<span class="breadcrumbs-current-elemet-full-title">'. get_the_title() .'</span>
      } else { //echo 8;
		
  
        $cat = get_the_category(); $cat = $cat[0];
		if ($cat->name == 'Business Articles For IT Founders and C-Level Managers') {
			$new_cat = 'Business Articles';	
			$adding_url = '<a href="https://'.$_SERVER['HTTP_HOST'].'/blog/" data-wpel-link="internal">Blog</a>'.$bsa;
		} elseif ( $cat->name == 'Company News'){
			$new_cat = 'News';
			$adding_url = '<a href="https://'.$_SERVER['HTTP_HOST'].'/about-company/" data-wpel-link="internal">About</a>'.$bsa;
		} elseif ( $cat->name == 'Technical Articles'){
			$adding_url = '<a href="https://'.$_SERVER['HTTP_HOST'].'/blog/" data-wpel-link="internal">Blog</a>'.$bsa;
			$new_cat = $cat->name;
		} else {
			$new_cat = $cat->name;
			$adding_url = '';
		}
		
		//echo '<pre>';var_dump( $_SERVER );echo '</pre>';
		$new_cats = $adding_url.'<a href="https://'.$_SERVER['HTTP_HOST'].'/category/'.$cat->slug.'/">'.$new_cat.'</a>';
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		//echo $cats ;
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
		
		
        $output .= '<span>'.$new_cats.'</span>';	
			
		  
        if ($showCurrent == 1) $output .= $bsa.'<span class="breadcrumbs-current-elemet">'.$before . get_the_title() . $after . '</span>';
		
		  
		$this_url = str_replace('page/'.get_query_var('paged').'/', '', $this_url);
		$this_id = get_menu_id_and_menu_url_arr($this_url, $menu_terms)['this_id'];
		if (  in_array( $this_url, $menu_url_array )  ) {
			$output = '';
			$new_bc = new_bc( $menu_terms, $this_id, $array_title_and_url );
			$output = new_bc_output($new_bc, $bsa);
		}
		  
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) { //echo 9;
      $post_type = get_post_type_object(get_post_type()); //echo '<pre>'; var_dump($post_type); echo '</pre>';
	  if(is_tax()) { $this_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );  if($this_term) $title_term = $this_term->name; }
	  $page_parent_s = get_page_by_title($post_type->name);	  	
      $output .= $before . '<a href="'.get_page_link( $page_parent_s->ID).'" >'.$post_type->labels->singular_name.'</a>' .$bsa. '<span class="breadcrumbs-current-elemet">'.$before . $title_term . $after.'</span>';

    } elseif ( is_attachment() ) {// echo 10;
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      //$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      $output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;				  

    } elseif ( is_page() && !$post->post_parent ) { //echo 11;
	if ( get_the_title() == 'Let’s take your project to the next level!') {
		$new_title = 'Contacts';
	} else {
		$new_title = get_the_title();
	}
 
      if ($showCurrent == 1) $output .= '<span class="breadcrumbs-current-elemet">'.$before . $new_title . $after.'</span>';
		//if ( count( new_output($output) ) < count( $new_bc['title'] ) + 1 )  {
			//echo '33333'.$this_url.'<br>';
			//echo '22222'.isset_element_in_menu( $menu_terms, $this_url );isset_element_in_array( $menu_terms, $this_url ) 
		if (  in_array( $this_url, $menu_url_array )  ) {
			$output = new_bc_output($new_bc, $bsa);
		}
		$bd_str = strip_tags($output);
		//echo strlen($bd_str);
		
		
    } elseif ( is_page() && $post->post_parent ) { //if ( is_user_logged_in() ) { echo 12; }
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>'.$bsa;
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        $output .= $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) $output .= ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) $output .= '' . $delimiter . '<span class="breadcrumbs-current-elemet">' . $before . get_the_title() . $after.'</span>';
		//$new_output = [];
		//$strip_output = strip_tags( $output );		
		//$new_output = explode( '>',  $strip_output);
		//$new_output = new_output($output);
		//if ( count( new_output($output) ) < count( $new_bc['title'] ) + 1 )  {
			//$output = new_bc_output($new_bc, $bsa);
		//}
		//echo $this_url;
		//echo '<pre>'; var_dump($menu_url_array); echo '</pre>';
		

		if (  in_array( $this_url, $menu_url_array )  ) {
			//echo 123;			
			$output = new_bc_output($new_bc, $bsa);
		}
		//var_dump( $new_output );echo '<br>';
		//echo 234234234;
		//echo '<pre>'; var_dump( wp_get_nav_menu_items( 'Menu' ) );	 echo '</pre>';
		//$bd_str = strip_tags($output);
		//echo strlen($bd_str);
    } elseif ( is_tag() ) {//echo 13;		
      $output .= $before . /*__('Posts tagged','purepress').' "' .*/ '<span class="breadcrumbs-current-elemet">'. single_tag_title('', false) . $after.'</span>';

    } elseif ( is_author() ) { //echo 14;
     global $author;
     $userdata = get_userdata($author);
     $output .= $before . __('Articles posted by ','purepress') . $userdata->display_name . $after;

   } elseif ( is_404() ) { //echo 15;
    $output .= '<span class="breadcrumbs-current-elemet">' . $before .  __('Error 404','purepress') . $after.'</span>';
  }

  //if ( get_query_var('paged') ) { echo 16;
    //if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
      //$output .= '<li>'.__('Page','purepress') . ' ' . get_query_var('paged').'</li>';
      //if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
//}

//$output .= '</ul></nav>';
//$cont_class = url_mode();
return '<div class="breadcrumbs-container--first">'.$output.'</div>';
}
}