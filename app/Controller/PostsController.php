<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 */
class PostsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->isAdmin()) {
            $this->Auth->allow('*');
        }
        $this->Auth->allow(array('index', 'view'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Post->recursive = 0;

        $posts = $this->Post->find('all', array(
            'fields' => array('Post.*', 'User.id', 'User.firstname', 'User.lastname'),
            'conditions' => array(
                'AND' => array(
                    'Post.show_frontpage' => 1,
                    'Post.publish' => 1
                )
            ),
            'order' => 'Post.created DESC'
        ));

        $links = $this->Post->find('all', array(
            'fields' => array('Post.id', 'Post.title', 'Post.slug'),
            'conditions' => array(
                'AND' => array(
                    'Post.publish' => 1,
                    'Post.show_link' => 1
                )
            )
        ));

        $this->set(compact('posts', 'links'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Post->id = $id;
        if (!$this->Post->exists()) {
            throw new NotFoundException(__('Invalid post'));
        }

        $this->loadModel('Post');
        $links = $this->Post->findLinks();

        $this->set('post', $this->Post->read(null, $id));
        $this->set(compact('links'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('The post has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The post could not be saved. Please, try again.'));
            }
        }
        $users = $this->Post->User->find('list');
        $this->set(compact('users'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->Post->id = $id;
        if (!$this->Post->exists()) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('The post has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The post could not be saved. Please, try again.'));
            }
        }
        else {
            $this->request->data = $this->Post->read(null, $id);
        }
        $users = $this->Post->User->find('list');
        $this->set(compact('users'));
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
        $this->Post->id = $id;
        if (!$this->Post->exists()) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->Post->delete()) {
            $this->Session->setFlash(__('Post deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Post was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
