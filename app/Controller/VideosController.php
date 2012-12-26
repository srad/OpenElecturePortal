<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Videos Controller
 *
 * @property Video $Video
 */
class VideosController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('latest', 'search', 'view'));
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

    public function search() {
        if ($this->request->is('post') && isset($this->request->data['term'])) {

            $search_term = trim($this->request->data['term']);
            if ($search_term == '') {
                return;
            }
            $search_term = explode(' ', $search_term);

            if (!empty($search_term)) {
                $terms = array();

                foreach ($search_term as $term) {
                    $term = trim($term);
                    $term = Sanitize::escape($term);

                    array_push($terms, array(
                            'OR' => array(
                                'Video.title LIKE'       => '%'.$term.'%',
                                'Video.description LIKE' => '%'.$term.'%',
                                'Video.subtitle LIKE'    => '%'.$term.'%',
                                'Video.speaker LIKE'     => '%'.$term.'%',
                                'Lecture.name LIKE'      => '%'.$term.'%',
                            )
                        )
                    );
                }

                if ($this->request->is('ajax')) {
                    $this->autoRender = false;

                    $videos = $this->Video->find('all', array(
                        'recursive' => 0,
                        'fields' => array('Lecture.term_id', 'Lecture.category_id', 'Lecture.name', 'Lecture.id', 'Video.*'),
                        'order' => array('Video.video_date' => 'DESC'),
                        'limit' => 20,
                        'group' => array('Lecture.id'),
                        'conditions' => array('OR' => $terms)
                    ));

                    return json_encode($videos);
                }
                else {
                    $videos = $this->Video->find('all', array(
                        'fields' => array('Lecture.name', 'Lecture.id', 'Video.*'),
                        'order' => array('Video.video_date' => 'DESC'),
                        'limit' => 50,
                        'group' => array('Lecture.id'),
                        'conditions' => array('OR' => $terms)
                    ));
                }

                $search = $this->request->data['term'];
                $this->set(compact('videos', 'search'));
            }
        } else {
            $this->setLinks();
        }
        $this->set(compact('links'));
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
