<?php
/**
 * Descriptción de Sim
 *
 * @author alfonso_fer
 */
class Sim extends AppModel {
    public $belongsTo = array('Centro');
     public $validate = array(
        'centro_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Centro'
            ),
        ),
        'numero' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Introducir un Número'
            ),
            'unico' => array(
                'rule'    => 'isUnique',
                'message' => 'El número introducido ya está utilizado en otro centro'
            )
        )
    );
}

?>
