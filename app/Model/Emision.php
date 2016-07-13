<?php

/**
 * Descriptción de Emision
 *
 * @author alfonso_fer
 */
class Emision extends AppModel {
    public $belongsTo = array('Centro', 'Multiple');
     public $validate = array(
        'centro_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Centro'
            ),
        ),
        'multiple_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Múltiple'
            )
        )
    );
}

?>
