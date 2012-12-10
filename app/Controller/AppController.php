<?php
/**
 * Application level Controller
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $helpers = array('Datetime');

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
            ),
            'authenticate' => array(
                'Form' => array(
                    'scope' => array(
                        'User.active' => 1
                    )
                )
            ),
            'authorize' => 'controller'
        ),
    );

    public function beforeFilter() {
        if ($this->name !== 'Categories') {
            $this->loadModel('Category');
        }
        $categories = $this->Category->find('list', array(
            'fields' => array('Category.id', 'Category.name'),
            'conditions' => array('Category.hide' => 0),
            'order' => array('Category.ordering ASC')
        ));
        $this->set(compact('categories'));

        $this->set('loggedIn', $this->Auth->loggedIn());
        $this->set('group', $this->Auth->user('Group.name'));
        $this->set('username', $this->Auth->user('name'));

        $this->loadLESS();
    }

    public function loadLESS() {
        // only compile it on development mode
        if (Configure::read('debug') > 0) {
            // import the file to application
            App::import('Vendor', 'lessc');

            // set the LESS file location
            $less = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'css' . DS . 'main.less';

            // set the CSS file to be written
            $css = ROOT . DS . APP_DIR . DS . 'webroot' . DS . 'css' . DS . 'main.css';

            // compile the file
            lessc::ccompile($less, $css);
        }
    }

    public function isAuthorized($user) {
        if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] === 'admin') {
            // Assistants can access: listings, categories, terms controllers.
            if (($this->name == 'listings' || $this->name == 'categories' || $this->name == 'terms') && $this->isAssistant()) {
                return true;
            }
            return $this->isAdmin();
        }
        return false;
    }

    protected function isAdmin() {
        return $this->Auth->user('Group.name') === 'admin';
    }

    protected function isAssistant() {
        return $this->Auth->user('Group.name') === 'assistant';
    }

    protected function getUserId() {
        return $this->Auth->user('id');
    }

}
