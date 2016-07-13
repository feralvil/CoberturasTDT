<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'centros', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'centros', 'action' => 'index'),
            'authorize' => array('Controller'),
            'flash' => array('element' => 'default', 'key' => 'auth', 'params' => array('class' => 'ink-alert basic error')),
            'authError' => 'No dispone de permisos para acceder a esta ubicación o su sesión ha expirado',
        ),
        'DebugKit.Toolbar'
    );

    //public $components = array('Session', 'DebugKit.Toolbar');

    // Helpers: Todos los controllers usarán estos helpers. Por eso los declaramos aquí:
    public $helpers = array('Html', 'Form', 'Paginator', 'Js');

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['role']) && ($user['role'] === 'admin')) {
            return true;
        }

        if ($this->action === 'logout') {
            return true;
        }

        // Default deny
        return false;
    }
}
