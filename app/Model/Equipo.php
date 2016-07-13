<?php
/**
 * Descriptción de Equipo
 *
 * @author alfonso_fer
 */
class Equipo extends AppModel {
    public $belongsTo = array('Centro');
     public $validate = array(
        'centro_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Seleccionar un Centro'
            ),
        ),
        'marca' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Debe Introducir un Número de Serie'
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
