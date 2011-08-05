<?php
class ProMenusHelper extends AppHelper {
    public $helpers = array(
        'Html',
        'Layout',
    );
    
    public $selected_links = array();
    public $breadcrumb = array();
    
    public function __construct($options = array()) {
      $this->View =& ClassRegistry::getObject('view');
      return parent::__construct($options);
    }
    
    public function menu($menuAlias, $options = array()) {
        $_options = array(
            'tag' => 'ul',
            'tagAttributes' => array(),
			'for_level' => 0,
			'sub_levels' => null,
            'selected' => 'selected',
            'selected_trail' => 'selected_trail',
            'selected_for_li' => true,
			'first' => false,
			'last' => 'last',
			'first_last_for_li' => false,
        );
        $options = array_merge($_options, $options);

        if (!isset($this->View->viewVars['pro_menus_for_layout'][$menuAlias])) {
            return false;
        }
        $menu = $this->View->viewVars['pro_menus_for_layout'][$menuAlias];
		$this->selected_links = array();
        $this->setSelectedLinks($menu['threaded']);
        
        if ($options['for_level'] == 0) {
            $output = $this->nestedLinks($menu['threaded'], $options);
        } else {
            $output = $this->levelNMenu($menu['threaded'], $options['for_level'], $options);
        }
        return $output;
    }
    public function setSelectedLinks($links) {
        foreach($links as $link) {
            if (isset($link['ProMenusLinkField'], $link['ProMenusLinkField']['selected_if']) and !empty($link['ProMenusLinkField']['selected_if'])) {
                if (eval($link['ProMenusLinkField']['selected_if'])) {
                    $this->selected_links[] = $link;
                }
            } else {
                // if link is in the format: controller:contacts/action:view
                if (strstr($link['Link']['link'], 'controller:')) {
                    $link['Link']['link'] = $this->Layout->linkStringToArray($link['Link']['link']);
                }
                // Remove locale part before comparing links
                if (!empty($this->params['locale'])) {
                    $currentUrl = substr($this->params['url']['url'], strlen($this->params['locale']));
                } else {
                    $currentUrl = $this->params['url']['url'];
                }
    
                if (Router::url($link['Link']['link']) == Router::url('/' . $currentUrl)) {
                    $this->selected_links[] = $link;
                }
            }
            if (!empty($link['children'])) {
                $this->setSelectedLinks($link['children']);
            }
        }
    }
    public function levelNMenu($links, $n, $options) {
        foreach ($links as $link) {
            $probe = false;
            foreach($this->selected_links as $linkForLayout){
                if($link['Link']['lft'] <= $linkForLayout['Link']['lft'] && $link['Link']['rght'] >= $linkForLayout['Link']['rght'] && $link['Link']['menu_id'] == $linkForLayout['Link']['menu_id']){
                    $probe = true;
                }
            }
            if ($probe) {
                if (isset($link['children']) and !empty($link['children'])) {
                    if ($n == 1) {
                        return $this->nestedLinks($link['children'], $options);
                    } else {
                        return $this->levelNMenu($link['children'], $n - 1, $options);
                    }
                } else {
                    return false;
                }
            }
        }
        return false;
    }
    public function nestedLinks($links, $options = array(), $depth = 1) {
        $_options = array();
        $options = array_merge($_options, $options);

        $output = '';
        $orig_sub_levels = $options['sub_levels'];
        foreach ($links AS $linkKey => $link) {
            $linkAttr = array(
                'id' => 'link-' . $link['Link']['id'],
                'rel' => $link['Link']['rel'],
                'target' => $link['Link']['target'],
                'title' => $link['Link']['description'],
            );

            foreach ($linkAttr AS $attrKey => $attrValue) {
                if ($attrValue == null) {
                    unset($linkAttr[$attrKey]);
                }
            }
			
			$linkClasses = array();
			$liClasses = array();
			if ($options['first'] !== false and $linkKey == 0) {
				$linkClasses[] = $options['first'];
				if ($options['first_last_for_li']) { $liClasses[] = $options['first']; }
			}
			if ($options['last'] !== false and $linkKey == (count($links) - 1)) {
				$linkClasses[] = $options['last'];
				if ($options['first_last_for_li']) { $liClasses[] = $options['last']; }
			}
            foreach($this->selected_links as $linkForLayout){
				if($link['Link']['lft'] < $linkForLayout['Link']['lft'] && $link['Link']['rght'] > $linkForLayout['Link']['rght'] && $link['Link']['menu_id'] == $linkForLayout['Link']['menu_id']){
                    $linkClasses[] = $options['selected_trail'];
					if ($options['selected_for_li']) { $liClasses[] = $options['selected_trail']; }
                }
                else{
                    if($link['Link']['lft'] == $linkForLayout['Link']['lft'] && $link['Link']['rght'] == $linkForLayout['Link']['rght'] && $link['Link']['menu_id'] == $linkForLayout['Link']['menu_id']){
                        $linkClasses[] = $options['selected'];
						if ($options['selected_for_li']) { $liClasses[] = $options['selected']; }
                    }
                }
            }
			$linkAttr['class'] = implode(' ', $linkClasses);
            $liAttr = array('class' => implode(' ', $liClasses));

			if (strstr($link['Link']['link'], 'controller:')) {
                $link['Link']['link'] = $this->Layout->linkStringToArray($link['Link']['link']);
            }
            $linkOutput = $this->Html->link($link['Link']['title'], $link['Link']['link'], $linkAttr);
            if (isset($link['children']) && count($link['children']) > 0) {
                if ($options['sub_levels'] !== null){
                    $options['sub_levels']--;
                }
                if ($options['sub_levels'] > 0 or $options['sub_levels'] == null){
                    $linkOutput .= $this->nestedLinks($link['children'], $options, $depth + 1);
                }
                $options['sub_levels'] = $orig_sub_levels;
            }
            $linkOutput = $this->Html->tag('li', $linkOutput, $liAttr);
            $output .= $linkOutput;
        }
        if ($output != null) {
            $output = $this->Html->tag($options['tag'], $output, $options['tagAttributes']);
        }

        return $output;
    }
    public function breadcrumb($menuAlias, $options = array()) {
        $_options = array(
            'separator' => ' &rarr; ',
            'tag' => 'div',
            'tagAttributes' => array('class' => 'breadcrumb')
        );
        $options = array_merge($_options, $options);
        
        if (!isset($this->View->viewVars['pro_menus_for_layout'][$menuAlias])) {
            return false;
        }
        $menu = $this->View->viewVars['pro_menus_for_layout'][$menuAlias];
        $this->selected_links = array();
        $this->setSelectedLinks($menu['threaded']);
        
        if (empty($this->selected_links)){
            return false;
        }
        
        $this->setNestedCrumbs($menu['threaded'], $options);
        
        $output = array();
        foreach ($this->breadcrumb as $crumb) {
            $output[] = $this->Html->link($crumb['Link']['title'], $crumb['Link']['link']);
        }
        $output = $this->Html->tag($options['tag'], implode($options['separator'], $output), $options['tagAttributes']);
        
        return $output;
    }
    public function setNestedCrumbs($links, $options = array()) {
        $_options = array();
        $options = array_merge($_options, $options);
        
        $linkForLayout = $this->selected_links[0];

        foreach ($links AS $linkKey => $link) {
            if($link['Link']['lft'] < $linkForLayout['Link']['lft'] && $link['Link']['rght'] > $linkForLayout['Link']['rght'] && $link['Link']['menu_id'] == $linkForLayout['Link']['menu_id']){
                $this->breadcrumb[] = $link;
                if (isset($link['children']) && count($link['children']) > 0) {
                    $this->setNestedCrumbs($link['children'], $options);
                }
            }
            else{
                if($link['Link']['lft'] == $linkForLayout['Link']['lft'] && $link['Link']['rght'] == $linkForLayout['Link']['rght'] && $link['Link']['menu_id'] == $linkForLayout['Link']['menu_id']){
                    $this->breadcrumb[] = $link;
                }
            }
        }
    }
}
?>