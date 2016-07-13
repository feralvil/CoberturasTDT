<?php

/**
 * Descriptción de Municipio
 *
 * @author alfonso_fer
 */
class Centro extends AppModel {
    public $hasMany = array('Cobertura', 'Emision', 'Sim', 'Equipo');
    public $validate = array(
        'centro' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe introducir un nombre de Centro'
            ),
            'unico' => array(
                'rule'    => 'isUnique',
                'message' => 'El nombre de Centro elegido ya está utilizado'
            )
        ),
        'tipologia' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe seleccionar una tipología de centro'
            ),
            'valid' => array(
                'rule' => array('inList', array('C1', 'C2')),
                'message' => 'Por favor, seleccione una tipología válida',
                'allowEmpty' => false
            )
        )
    );

    public function uploadHw($fichero) {
       if ((isset($fichero['error']) && $fichero['error'] == 0) ||(!empty( $fichero['tmp_name']) && $fichero['tmp_name'] != 'none')){
           return is_uploaded_file($fichero['tmp_name']);
       }
       return false;
   }
}

?>
