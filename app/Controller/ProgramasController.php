<?php

/**
 * Descriptción de ProgramasController
 *
 * @author alfonso_fer
 */
class ProgramasController extends AppController {
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array('Programa.nombre' => 'asc')
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
         if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array('index', 'detalle', 'editar', 'agregar', 'borrar');
            }
            if (in_array($this->action, $accPerm)) {
                return true;
            }
            else{
                return parent::isAuthorized($user);
            }
        }
    }

    public function index() {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Programas TDT'));

        // Select de Múltiples:
        $opciones = array(
            'fields' => array('Multiple.id', 'Multiple.nombre'),
            'order' => 'Multiple.nombre'
        );
        $this->set('multiplesel', $this->Programa->Multiple->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Select de Multiple
        if (!empty($this->request->data['Programa']['multiple_id'])) {
            $addcond = array('Programa.multiple_id' => $this->request->data['Programa']['multiple_id']);
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Select de Alta Definición
        if (!empty($this->request->data['Programa']['altadef'])) {
            $addcond = array('Programa.altadef' => $this->request->data['Programa']['altadef']);
            $condiciones = array_merge($addcond, $condiciones);
        }

        // Select de Codificado
        if (!empty($this->request->data['Programa']['codificado'])) {
            $addcond = array('Programa.codificado' => $this->request->data['Programa']['codificado']);
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Cambio de página
        if (!empty($this->request->data['Programa']['irapag'])&&($this->request->data['Programa']['irapag'] > 0)){
            $this->paginate['page'] = $this->request->data['Programa']['irapag'];
        }
        // Tamaño de página
        if (!empty($this->request->data['Programa']['regPag'])&&($this->request->data['Programa']['regPag'] > 0)){
            $this->paginate['limit'] = $this->request->data['Programa']['regPag'];
        }

        $this->paginate['conditions'] = $condiciones;
        $programas = $this->paginate();
        $this->set('programas', $programas);
    }


    public function agregar(){
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Programa->uploadLogo($this->request->data['Programa']['fichlogo'])){
                // Generamos los ficheros
                App::uses('Folder', 'Utility');
                App::uses('File', 'Utility');
                $nomFichero = $this->request->data['Programa']['fichlogo']['name'];
                $fichero = new File($this->request->data['Programa']['fichlogo']['tmp_name'], true, 0644);
                if ($fichero->copy('img' . DS . 'logos' . DS . $nomFichero, true)){
                    $this->Session->setFlash(__('Se ha guardado correctamente el fichero de datos'), 'default', array('class' => 'alert alert-success'));
                    // Guardamos los datos:
                    $this->Programa->create();
                    $this->request->data['Programa']['logo'] = 'logos/' . $nomFichero ;
                    if ($this->Programa->save($this->request->data)) {

                        $this->Session->setFlash(__('Programa agregado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                        $this->redirect(array('controller' => 'programas', 'action' => 'index'));
                    }
                    else {
                        $this->Session->setFlash(__('Error al crear el programa'.  $this->validationErrors), 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
                    }
                }
                else{
                    $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el fichero de datos'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
                }
            }
            else{
                $this->Session->setFlash(__('<b>Error:</b> No se ha seleccionado ningún archivo o ha habido un error al subirlo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Nuevo Programa TDT de la Comunitat'));

            // Select de Múltiples:
            $opciones = array(
                'fields' => array('Multiple.id', 'Multiple.nombre'),
                'order' => 'Multiple.nombre'
            );
            $this->set('multiplesel', $this->Programa->Multiple->find('list', $opciones));

        }
    }

    public function editar($id = null){
        $this->Programa->id = $id;
        if (!$this->Programa->exists()) {
            throw new NotFoundException(__('Error: el múltiple seleccionado no existe'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Programa->uploadLogo($this->request->data['Programa']['fichlogo'])){
                // Generamos los ficheros
                App::uses('Folder', 'Utility');
                App::uses('File', 'Utility');
                //$explodeFich = explode('.', $this->request->data['Programa']['fichlogo']['name']);
                $nomFichero = $this->request->data['Programa']['fichlogo']['name'];
                $fichero = new File($this->request->data['Programa']['fichlogo']['tmp_name'], true, 0644);
                if ($fichero->copy('img' . DS . 'logos' . DS . $nomFichero, true)){
                    $this->Session->setFlash(__('Se ha guardado correctamente el fichero de datos'), 'default', array('class' => 'alert alert-success'));
                    // Guardamos los datos:
                    $this->request->data['Programa']['logo'] = 'logos/' . $nomFichero ;
                    if ($this->Programa->save($this->request->data)) {

                        $this->Session->setFlash(__('Programa modificado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                        $this->redirect(array('controller' => 'programas', 'action' => 'index'));
                    }
                    else {
                        $this->Session->setFlash(__('Error al modificar el programa'.  $this->validationErrors), 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
                    }
                }
                else{
                    $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el fichero de datos'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
                }
            }
            else{
                $this->Session->setFlash(__('<b>Error:</b> No se ha seleccionado ningún archivo o ha habido un error al subirlo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'programas', 'action' => 'agregar'));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Programa TDT de la Comunitat'));
            $this->request->data = $this->Programa->read(null, $id);

            // Select de Múltiples:
            $opciones = array(
                'fields' => array('Multiple.id', 'Multiple.nombre'),
                'order' => 'Multiple.nombre'
            );
            $this->set('multiplesel', $this->Programa->Multiple->find('list', $opciones));
        }
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Programa->id = $id;
        if (!$this->Programa->exists()) {
            throw new NotFoundException(__('Error: el programa seleccionado no existe'));
        }
        // Buscamos los datos de cobertura:
        $programa = $this->Programa->read(null, $id);

        if ($this->Programa->delete()) {
            $this->Session->setFlash(__('Programa') . ' ' . $programa['Programa']['nombre'] . ' ' . __('eliminado correctamente'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'programas', 'action' => 'index'));
        }
        else{
            $this->Session->setFlash(__('Error al eliminar el Programa ' . $programa['Programa']['nombre']), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'programas', 'action' => 'index'));
        }
    }
}

?>
