<?php
App::uses('AppController', 'Controller');
/**
 * Videos Controller
 *
 * @property Video $Video
 */
class VideosController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('latest', 'view'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Video->recursive = 0;
        $this->set('videos', $this->paginate());
    }

    public function latest() {
        $this->setLinks();

        $videos = $this->Video->find('all', array(
            'fields' => array('Video.*'),
            'recursive' => 1,
            'order' => array('Video.video_date DESC'),
            'limit' => 3,
        ));

        $this->set(compact('links', 'videos'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Video->id = $id;
        if (!$this->Video->exists()) {
            throw new NotFoundException(__('Invalid video'));
        }
        $this->set('video', $this->Video->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Video->create();
            if ($this->Video->save($this->request->data)) {
                $this->Session->setFlash(__('The video has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The video could not be saved. Please, try again.'));
            }
        }
        $lectures = $this->Video->Lecture->find('list');
        $types = $this->Video->Type->find('list');
        $this->set(compact('lectures', 'types'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->Video->id = $id;
        if (!$this->Video->exists()) {
            throw new NotFoundException(__('Invalid video'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Video->save($this->request->data)) {
                $this->Session->setFlash(__('The video has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The video could not be saved. Please, try again.'));
            }
        }
        else {
            $this->request->data = $this->Video->read(null, $id);
        }
        $lectures = $this->Video->Lecture->find('list');
        $types = $this->Video->Type->find('list');
        $this->set(compact('lectures', 'types'));
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
        $this->Video->id = $id;
        if (!$this->Video->exists()) {
            throw new NotFoundException(__('Invalid video'));
        }
        if ($this->Video->delete()) {
            $this->Session->setFlash(__('Video deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Video was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
