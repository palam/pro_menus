<?php
class ProLinkBehavior extends ModelBehavior {
    public function setup(&$model, $config = array()) {
        if (is_string($config)) {
            $config = array($config);
        }

        $this->settings[$model->alias] = $config;
        
        $model->bindModel(array(
          'hasOne' => array(
            'ProMenusLinkField' => array(
              'className' => 'ProMenus.ProMenusLinkField',
              'dependent' => true
            )
          )
        ), false);
    }
    public function beforeFind(&$model, $results = array(), $primary = false) {
        $model->bindModel(array(
          'hasOne' => array(
            'ProMenusLinkField' => array(
              'className' => 'ProMenus.ProMenusLinkField',
              'dependent' => true
            )
          )
        ), false);
    }
    public function afterSave(&$model) {
        $model->data['ProMenusLinkField']['link_id'] = $model->id;
        $existing = $model->ProMenusLinkField->findByLinkId($model->id);
        if ($existing !== false) {
            #edit
            $model->ProMenusLinkField->id = $existing['ProMenusLinkField']['id'];
            $model->ProMenusLinkField->save($model->data, false, array('selected_if'));
        } else {
            #add
            $model->ProMenusLinkField->save($model->data);
        }
    }
}
?>