<?php

/**
 * Controlador de la Clase User
 *
 * @author alfonso_fer
 */
class UsersController extends AppController {

    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array(
            'User.nombre' => 'asc'
        )
    );

    /* public function beforeFilter() {
      parent::beforeFilter();
      $this->Auth->allow('add');
      } */

    public function isAuthorized($user) {
        if ($this->action === 'acceso') {
             if (isset($user['id'])){
                 $userId = $this->request->params['pass'][0];
                 if ($userId === $user['id']){
                     return TRUE;
                 }
             }
        }
        return parent::isAuthorized($user);
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // Comprobamos si Usuario = Contraseña
                //if ((AuthComponent::password($this->Auth->user('username'))) == $this->Auth->user('password')){
                if (($this->Auth->user('resetpw') == "SI")){
                    $this->redirect(array('action' => 'acceso', $this->Auth->user('id')));
                }
                else{
                    $this->redirect($this->Auth->redirect());
                }
            } else {
                $this->Session->setFlash(__('Usuario o Contraseña incorrectos'), 'default', array('class' => 'ink-alert basic error'));
            }
        }
        else{
            $this->set('title_for_layout', __('Iniciar Sesión'));
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function index() {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Usuarios de la aplicación de Coberturas TDT'));
        
        // Select de Usuarios
        // Buscamos la provincia:
        $opciones = array(
            'fields' => array('User.id', 'User.nomComp'),
            'order' => 'User.nombre'
        );
        $this->set('usersel', $this->User->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')) {
            // Condiciones iniciales:
            $tampag = 30;
            $pagina = 1;
            
            // Condición para elegir un usuario
            if (!empty($this->request->data['Users']['usuario'])) {
                $addcond = array('User.id' => $this->request->data['Users']['usuario']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Condición para elegir un tipo de usuario
            if (!empty($this->request->data['Users']['role'])) {
                $addcond = array('User.role' => $this->request->data['Users']['role']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Cambio de página
            if (!empty($this->request->data['Users']['irapag']) && ($this->request->data['Users']['irapag'] > 0)) {
                $this->paginate['page'] = $this->request->data['Users']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Users']['regPag']) && ($this->request->data['Users']['regPag'] > 0)) {
                $this->paginate['limit'] = $this->request->data['Users']['regPag'];
            }
        }
        $this->User->recursive = 0;
        $this->paginate['conditions'] = $condiciones;
        $this->set('users', $this->paginate());
    }

    

    public function agregar() {
        if (($this->request->is('post')) || ($this->request->is('put'))) {
            $this->request->data['User']['password'] = $this->request->data['User']['username'];
            $this->request->data['User']['passconf'] = $this->request->data['User']['username'];
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Usuario creado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('No se pudo crear el usuario. Por favor, intentalo de nuevo.'), 'default', array('class' => 'ink-alert basic error'));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Nuevo Usuario de la aplicación de Coberturas TDT'));
        }
    }

    public function editar($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Se ha guardado el usuario'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('No se pudo modificar el usuario. Por favor, intentalo de nuevo.'), 'default', array('class' => 'ink-alert basic error'));
            }
        }
        else {
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Usuario de la aplicación de Coberturas TDT'));
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    
     public function acceso($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('El usuario seleccionado no existe'));
        }
        $usuario = $this->User->read(null, $id);
        $this->set('usuario', $usuario);
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['resetpw'] = 'NO';
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Se ha modificado la contraseña correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $controladorv = 'centros';
                if ($this->Auth->user('role') == 'admin'){
                    $controlador = 'users';
                }
                $this->redirect(array('controller' => $controlador, 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('No se pudo modificar el usuario. Por favor, intentalo de nuevo.'), 'default', array('class' => 'ink-alert basic error'));
            }
        }
        else {
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Contraseña de Usuario de la aplicación de Coberturas TDT'));
            $this->request->data = $usuario;
            unset($this->request->data['User']['password']);
        }
    }

    public function resetear($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'));
        }
        $usuario = $this->User->read('username', $id);
        $this->User->set(array(
            'password' => $usuario['User']['username'],
            'passconf' => $usuario['User']['username'],
            'resetpw' => 'SI'
        ));
        if ($this->User->save()) {
            $this->Session->setFlash(__('Se ha reseteado la contraseña del usuario'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('No se pudo modificar el usuario. Por favor, intentalo de nuevo.'), 'default', array('class' => 'ink-alert basic error'));
        }
        
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Usuario eliminado'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('No se pudo eliminar el usuario'), 'default', array('class' => 'ink-alert basic error'));
        $this->redirect(array('action' => 'index'));
    }

}

?>