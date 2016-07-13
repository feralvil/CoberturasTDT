<?php

/**
 * Descriptción de Multiple
 *
 * @author alfonso_fer
 */
class Multiple extends AppModel {
    public $hasmany = array('Programa', 'Emision');
    
    public $validate = array(
        'nombre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe introducir un nombre de Múltiple'
            ),
            'unico' => array(
                'rule'    => 'isUnique',
                'message' => 'El nombre de Múltiple ya está utilizado'
            )
        ),
        'ambito' => array(
            'valid' => array(
                'rule' => array('inList', array('NAC', 'AUT', 'LOC')),
                'message' => 'Por favor, seleccione un ámbito válido',
                'allowEmpty' => false
            )
        ),
        'soportado' => array(
            'valid' => array(
                'rule' => array('inList', array('SI', 'NO')),
                'message' => 'Por favor, indique si el múltiple esta soportado o no',
                'allowEmpty' => false
            )
        )
    );
}

?>
