<?php
/**
 * Modelo de Contacto de Centro TDT
 *
 * @author alfonso_fer
 */
class Contacto extends AppModel {
    public $belongsTo = array('Centro');
     public $validate = array(
        'centro_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Centro'
            ),
        ),
        'nombre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Introducir un Nombre'
            )
        ),
        'telefono' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Introducir un Teléfono'
            )
        ),
    );
}

?>
