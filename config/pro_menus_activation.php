<?php
class ProMenusActivation {
/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeActivation(&$controller) {
        $sql = file_get_contents(APP.'plugins'.DS.'pro_menus'.DS.'config'.DS.'pro_menus_activate.sql');
        if(!empty($sql)){
            App::import('Core', 'File');
            App::import('Model', 'ConnectionManager');
            $db = ConnectionManager::getDataSource('default');

            $statements = explode(';', $sql);

            foreach ($statements as $statement) {
                if (trim($statement) != '') {
                    $db->query($statement);
                }
            }
        }
        return true;
    }
    public function onActivation(&$controller) {
    }
/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeDeactivation(&$controller) {
        $sql = file_get_contents(APP.'plugins'.DS.'pro_menus'.DS.'config'.DS.'pro_menus_deactivate.sql');
        if(!empty($sql)){
            App::import('Core', 'File');
            App::import('Model', 'ConnectionManager');
            $db = ConnectionManager::getDataSource('default');
            $statements = explode(';', $sql);

            foreach ($statements as $statement) {
                if (trim($statement) != '') {
                    $db->query($statement);
                }
            }
        }
        return true;
    }
    public function onDeactivation(&$controller) {
    }
}
?>