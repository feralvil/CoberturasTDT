<?php
/* Controlador de contactos */

class ContactosController extends AppController {
    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
        if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array(
                    'index', 'detalle', 'centro', 'editar', 'agregar', 'borrar'
                );
            }
            elseif ($rol == 'consum') {
                $accPerm = array('centro');
            }
            if (in_array($this->action, $accPerm)) {
                return true;
            }
            else{
                return parent::isAuthorized($user);
            }
        }
    }

    public function centro ($idcentro = null){
        // Fijamos el Título de la vista
        $this->set('title_for_layout', __('Contactos de Centro TDT'));

        // Leemos el centro:
        $this->Contacto->Centro->id = $idcentro;
        if (!$this->Contacto->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $this->Contacto->Centro->recursive = -1;
        $centro = $this->Contacto->Centro->read(null, $idcentro);
        $this->set('centro', $centro);

        // Leemos los equipos:
        $contactos = $this->Contacto->findAllByCentroId($idcentro);
        $this->set('contactos', $contactos);
    }

    public function editar ($id = null){
        $this->Contacto->id = $id;
        if (!$this->Contacto->exists()) {
            throw new NotFoundException(__('Error: el contacto seleccionado no existe'));
        }
        $contacto = $this->Contacto->read(null, $id);
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Contacto->save($this->request->data)) {
                $this->Session->setFlash(__('Contacto modificado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'contactos', 'action' => 'centro', $contacto['Contacto']['centro_id']));
            }
            else {
                $this->Session->setFlash(__('Error al guardar el Contacto'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'contactos', 'action' => 'centro', $contacto['Contacto']['centro_id']));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Editar Contacto de Equipo TDT'));
            $this->request->data = $contacto;
        }
    }

    public function agregar ($idcentro = null){
        // Leemos el centro:
        $this->Contacto->Centro->id = $idcentro;
        if (!$this->Contacto->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $this->Contacto->Centro->recursive = -1;
        $centro = $this->Contacto->Centro->read(null, $idcentro);
        $this->set('centro', $centro);

        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Contacto->save($this->request->data)) {
                $this->Session->setFlash(__('Contacto agregado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'contactos', 'action' => 'centro', $idcentro));
            }
            else {
                $this->Session->setFlash(__('Error al agregar el Contacto'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'contactos', 'action' => 'centro', $contacto['Contacto']['centro_id']));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Agregar Contacto de Centro TDT'));
        }
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Contacto->id = $id;
        if (!$this->Contacto->exists()) {
            throw new NotFoundException(__('Error: el Contacto seleccionado no existe'));
        }
        // Buscamos los datos de cobertura:
        $contacto = $this->Contacto->read(null, $id);

        if ($this->Contacto->delete()) {
            $this->Session->setFlash(__('Contacto') . ' ' . $contacto['Contacto']['nombre'] . ' ' . __('eliminado correctamente'), 'default', array('class' => 'ink-alert basic success'));
        }
        else{
            $this->Session->setFlash(__('Error al eliminar el Múltiple ' . $Multiple['Multiple']['nombre']), 'default', array('class' => 'ink-alert basic error'));
        }
        $this->redirect(array('controller' => 'contactos', 'action' => 'centro', $contacto['Contacto']['centro_id']));
    }
}
?>
