<?php 


class New_Design_Menu_Walker extends Walker_Nav_Menu {
  private $rootCounter = 0;
	
  function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
    $has_dropdown = $args->walker->has_children;
    $is_active = in_array('current-menu-item', $item->classes) || in_array('current-menu-parent', $item->classes) || in_array('current-menu-ancestor', $item->classes) || (strpos($item->url, '/portfolio/') !== false && strpos(get_permalink(), $item->url) !== false) ? true : false;
    $is_collapsed_post = get_post_meta( $item->ID, 'menu-item-checkbox_collapse', true);
//     $collapse_on_mobile = $is_collapsed_post == 'checked' && $item->current_item_ancestor == false && $item->current_item_parent == false;
    $is_open_on_mobile = $is_collapsed_post == '' && $has_dropdown;
	$active_class = $is_active ? 'menu--active' : '';
	  
	  if($is_active || $is_open_on_mobile){
		  $collapse_attr = 'data-marked-mobile-state="open"';
	  } else{
		  $collapse_attr = '';
	  }

  	if ($depth == 0) {
	   $this->rootCounter += 1;
	   $right_invert_dropdown = $has_dropdown && $this->rootCounter >= 5 ? 'menu__item-preview--dropdown--invert' : '';
       $dropdown_class = $has_dropdown ? 'menu__item-preview--dropdown' : '';
       $dropdown_icon = $has_dropdown ? '<span class="menu__arrow-down" data-mobile-state="close"> <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none"> <path d="M11.8798 0.874701C11.9599 0.955558 12 1.04854 12 1.15366C12 1.25877 11.9599 1.35176 11.8798 1.43261L6.27655 7.08452C6.19639 7.16538 6.10421 7.20581 6 7.20581C5.89579 7.20581 5.80361 7.16538 5.72345 7.08452L0.12024 1.43261C0.0400802 1.35176 0 1.25877 0 1.15366C0 1.04854 0.0400802 0.955558 0.12024 0.874701L0.721443 0.268273C0.801603 0.187416 0.893788 0.146987 0.997996 0.146987C1.1022 0.146987 1.19439 0.187416 1.27455 0.268273L6 5.0348L10.7255 0.268273C10.8056 0.187416 10.8978 0.146987 11.002 0.146987C11.1062 0.146987 11.1984 0.187416 11.2786 0.268273L11.8798 0.874701Z" fill="currentColor"></path> </svg> </span>' : '';
  			$output .= '<li class="menu__item-preview '.$right_invert_dropdown.' '.$dropdown_class.' '.$active_class.'" '.$collapse_attr.'><a class="menu__item-link" href="'.$item->url.'"><span class="headline-four headline-four--color-inherit">'.$item->title.'</span>'.$dropdown_icon.'</a>';
  		} else{
        $dropdown_class = $has_dropdown ? 'menu__item--dropdown' : '';
        $dropdown_icon = $has_dropdown ? '<span class="menu__arrow-down" data-mobile-state="close"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none"><path d="M11.8798 0.874701C11.9599 0.955558 12 1.04854 12 1.15366C12 1.25877 11.9599 1.35176 11.8798 1.43261L6.27655 7.08452C6.19639 7.16538 6.10421 7.20581 6 7.20581C5.89579 7.20581 5.80361 7.16538 5.72345 7.08452L0.12024 1.43261C0.0400802 1.35176 0 1.25877 0 1.15366C0 1.04854 0.0400802 0.955558 0.12024 0.874701L0.721443 0.268273C0.801603 0.187416 0.893788 0.146987 0.997996 0.146987C1.1022 0.146987 1.19439 0.187416 1.27455 0.268273L6 5.0348L10.7255 0.268273C10.8056 0.187416 10.8978 0.146987 11.002 0.146987C11.1062 0.146987 11.1984 0.187416 11.2786 0.268273L11.8798 0.874701Z" fill="currentColor"></path></svg></span>' : '';
        $output .= '<li class="menu__item '.$dropdown_class.' '.$active_class.'" '.$collapse_attr.'><a class="menu__item-link" href="'.$item->url.'"><span class="headline-four headline-four--color-inherit">'.$item->title.'</span>'.$dropdown_icon.'</a>';
  		}
  }
  
  function end_el(&$output, $item, $depth=0, $args=null) { 
  		$output .= '</li>';
  }	
  
  function start_lvl(&$output, $depth=0, $args=null) {

  		$output .= '<ul class="menu__sub-menu">';
  }
  
  function end_lvl(&$output, $depth=0, $args=null) {
  	 	$output .= '</ul>';
  }	
	
}