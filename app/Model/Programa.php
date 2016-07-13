<?php

/**
 * Descriptción de Programa
 *
 * @author alfonso_fer
 */
class Programa extends AppModel {
    public $belongsTo = array('Multiple');

    public $validate = array(
        'nombre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe introducir un nombre de Programa'
            ),
            'unico' => array(
                'rule'    => 'isUnique',
                'message' => 'El nombre de Programa ya está utilizado'
            )
        ),
        'altadef' => array(
            'valid' => array(
                'rule' => array('inList', array('SI', 'NO')),
                'message' => 'Por favor, indique si el programa es en alta definición o no',
                'allowEmpty' => false
            )
        ),
        'codificado' => array(
            'valid' => array(
                'rule' => array('inList', array('SI', 'NO')),
                'message' => 'Por favor, indique si el programa es codificado o no',
                'allowEmpty' => false
            )
        )
    );

    public function uploadLogo($fichero) {
       if ((isset($fichero['error']) && $fichero['error'] == 0) ||(!empty( $fichero['tmp_name']) && $fichero['tmp_name'] != 'none')){
           return is_uploaded_file($fichero['tmp_name']);
       }
       return false;
   }
}
?>
