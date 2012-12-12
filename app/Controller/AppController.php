<?php
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
        $this->setCategories();
        $this->setAuthVars();
        $this->set('debugMode', Configure::read('debug'));
        $this->loadLESS();
    }

    /**
     * Anything authentication related variables for the view are set here.
     */
    private function setAuthVars() {
        $this->set('loggedIn', $this->Auth->loggedIn());
        $this->set('group', $this->Auth->user('Group.name'));
        $this->set('username', $this->Auth->user('name'));
    }

    /**
     * Sets the variables to render the navigation bar.
     * We cache the categories because they change very rarely.
     */
    private function setCategories() {
        $categories = Cache::read('categories', 'long');

        if (!$categories) {
            if ($this->name !== 'Categories' && !$categories) {
                $this->loadModel('Category');
            }
            $categories = $this->Category->find('list', array(
                'fields' => array('Category.id', 'Category.name'),
                'conditions' => array('Category.hide' => 0),
                'order' => array('Category.ordering ASC')
            ));
            Cache::write('categories', $categories, 'long');
        }

        $this->set(compact('categories'));
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

    /**
     * Callback for auth component.
     *
     * @param $user
     * @return bool
     */
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
