<?php
App::uses('AppController', 'Controller');
/**
 * VideosTypes Controller
 *
 * @property VideosType $VideosType
 */
class VideosTypesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->VideosType->recursive = 0;
		$this->set('videosTypes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->VideosType->id = $id;
		if (!$this->VideosType->exists()) {
			throw new NotFoundException(__('Invalid videos type'));
		}
		$this->set('videosType', $this->VideosType->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->VideosType->create();
			if ($this->VideosType->save($this->request->data)) {
				$this->Session->setFlash(__('The videos type has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The videos type could not be saved. Please, try again.'));
			}
		}
		$videos = $this->VideosType->Video->find('list');
		$types = $this->VideosType->Type->find('list');
		$this->set(compact('videos', 'types'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->VideosType->id = $id;
		if (!$this->VideosType->exists()) {
			throw new NotFoundException(__('Invalid videos type'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->VideosType->save($this->request->data)) {
				$this->Session->setFlash(__('The videos type has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The videos type could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->VideosType->read(null, $id);
		}
		$videos = $this->VideosType->Video->find('list');
		$types = $this->VideosType->Type->find('list');
		$this->set(compact('videos', 'types'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->VideosType->id = $id;
		if (!$this->VideosType->exists()) {
			throw new NotFoundException(__('Invalid videos type'));
		}
		if ($this->VideosType->delete()) {
			$this->Session->setFlash(__('Videos type deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Videos type was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
