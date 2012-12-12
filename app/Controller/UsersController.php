<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->isAdmin()) {
            $this->Auth->allow('*');
        }
        $this->Auth->allow('login', 'logout', 'createAdminUser');
    }

    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        else {
            $this->request->data = $this->User->read(null, $id);
        }
        $groups = $this->User->Group->find('list');
        $this->set(compact('groups'));
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                if ($this->isAdmin() || $this->isAssistant()) {
                    $this->redirect('/admin/listings');
                }
            }
            else {
                $this->Session->setFlash(__('Ungültiges Passwort oder Benutzername. Setzen Sie Ihr Passwort zurück falls nötig. <br />Beachten Sie, dass Ihr Konto auch deaktiviert worden sein könnte.'), 'flash_error');
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }


    public function createAdminUser() {
        $this->User->create();

        $group = $this->User->Group->find('first', array('conditions' => array('Group.name' => 'admin')));
        $this->User->set(array(
            'User' => array(
                'username'  => 'admin',
                'password'  => 'n4z074znmqx9037t4',
                'firstname' => 'admin',
                'lastname'  => 'admin',
                'active'    => 1,
                'group_id'  => $group['Group']['id']
            )
        ));
        debug($this->User->save());
        $this->redirect('/users/login');
    }


}
