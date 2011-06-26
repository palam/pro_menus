<?php
class ProMenusComponent extends Object {
    
    public $pro_menus_for_layout = array();
/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param object $controller Controller with components to startup
 * @return void
 */
    public function startup(&$controller) {
        $this->controller =& $controller;
        if (isset($this->controller->params['admin'])) {
            //App::build(array('views' => array(APP . 'plugins' . DS . 'pro_menus' . DS . 'views' . DS)));
        } else {
            $this->menus();
            $this->controller->helpers[] = 'ProMenus.ProMenus';
        }
    }
    
    public function menus() {
        $menus = array();
        $themeData = $this->controller->Croogo->getThemeData(Configure::read('Site.theme'));
        if (isset($themeData['menus']) && is_array($themeData['menus'])) {
            $menus = Set::merge($menus, $themeData['menus']);
        }
        $menus = Set::merge($menus, array_keys($this->controller->Croogo->blocksData['menus']));

        foreach ($menus AS $menuAlias) {
            $menu = $this->controller->Link->Menu->find('first', array(
                'conditions' => array(
                    'Menu.status' => 1,
                    'Menu.alias' => $menuAlias,
                    'Menu.link_count >' => 0,
                ),
                'cache' => array(
                    'name' => 'croogo_menu_'.$menuAlias,
                    'config' => 'croogo_menus',
                ),
                'recursive' => '-1',
            ));
            if (isset($menu['Menu']['id'])) {
                $this->pro_menus_for_layout[$menuAlias] = array();
                $this->pro_menus_for_layout[$menuAlias]['Menu'] = $menu['Menu'];
                $findOptions = array(
                    'conditions' => array(
                        'Link.menu_id' => $menu['Menu']['id'],
                        'Link.status' => 1,
                        'AND' => array(
                            array(
                                'OR' => array(
                                    'Link.visibility_roles' => '',
                                    'Link.visibility_roles LIKE' => '%"' . $this->controller->Croogo->roleId . '"%',
                                ),
                            ),
                        ),
                    ),
                    'order' => array(
                        'Link.lft' => 'ASC',
                    ),
                    'cache' => array(
                        'name' => 'croogo_menu_'.$menu['Menu']['id'].'_pro_links_'.$this->controller->Croogo->roleId,
                        'config' => 'croogo_menus',
                    ),
                    'recursive' => 0
                );
                $links = $this->controller->Link->find('threaded', $findOptions);
                $this->pro_menus_for_layout[$menuAlias]['threaded'] = $links;
            }
        }
    }
/**
 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
 * Controller::render()
 *
 * @param object $controller Controller with components to beforeRender
 * @return void
 */
    public function beforeRender(&$controller) {
        $this->controller->set('pro_menus_for_layout', $this->pro_menus_for_layout);
    }
}
?>