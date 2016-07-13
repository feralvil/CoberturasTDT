<?php

/**
 * Descriptción de Municipio
 *
 * @author alfonso_fer
 */
class Cobertura extends AppModel {
    public $belongsTo = array('Centro', 'Municipio');
    public $validate = array(
        'centro_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Centro'
            )
        ),
        'municipio_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Municipio'
            )
        ),
        'porcentaje' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe indicar un porcentaje de cobertura'
            ),
            'minimo' => array(
                'rule'    => array('comparison', '>', 0),
                'message' => 'El porcentaje de cobertura debe ser mayor que 0'
            ),
            'maximo' => array(
                'rule'    => array('comparison', '<=', 100),
                'message' => 'El porcentaje de cobertura debe ser menor o igual que 100'
            )
        )
    );
}

?>
