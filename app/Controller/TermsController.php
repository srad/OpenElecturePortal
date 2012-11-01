<?php
App::uses('AppController', 'Controller');
/**
 * Terms Controller
 *
 * @property Term $Term
 */
class TermsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->isAssistant()) {
            $this->Auth->allow('*');
        }
        else {
            $this->Auth->allow('view');
        }
    }

    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $terms = $this->Term->find('list', array('order' => 'Term.ordering ASC'));
        $this->set(compact('terms'));
    }

    public function admin_sort() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $position = 0;
            foreach($this->request->data['term'] as $termId) {
                $saved = $this->Term->updateAll(array('Term.ordering' => $position), array('Term.id' => $termId));
                if (!$saved) {
                    throw new Exception(__('Fehler beim Speichern der Semester-Ordnung: ') . $this->Term->validationErrors);
                }
                $position += 1;
            }
        }
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Term->create();
            // Place at the end of the list
            $this->request->data['Term']['ordering'] = $this->Term->find('count');

            if ($this->Term->save($this->request->data)) {
                $this->Session->setFlash(__('The term has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The term could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * Updates all courses at once
     */
    public function admin_edit() {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Term->saveAll($this->request->data['Term'])) {
                $this->Session->setFlash(__('The term has been saved'));
            }
            else {
                $this->Session->setFlash(__('The term could not be saved. Please, try again.'));
            }
        }
        else {
            throw new MethodNotAllowedException();
        }
        $this->redirect(array('action' => 'index'));
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
        $this->Term->id = $id;
        if (!$this->Term->exists()) {
            throw new NotFoundException(__('Invalid term'));
        }
        if ($this->Term->delete()) {
            $this->Session->setFlash(__('Term deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Term was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
