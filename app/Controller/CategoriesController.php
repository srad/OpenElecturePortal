<?php
App::uses('AppController', 'Controller');
/**
 * Categories Controller
 *
 * @property Category $Category
 */
class CategoriesController extends AppController {

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
        $categoryData = $this->Category->find('all', array('recursive' => -1, 'order' => 'Category.ordering ASC'));
        $this->set(compact('categoryData'));
    }

    public function admin_sort() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $position = 0;
            foreach ($this->request->data['Category'] as $categoryId) {
                $saved = $this->Category->updateAll(array('Category.ordering' => $position), array('Category.id' => $categoryId));
                if (!$saved) {
                    throw new Exception(__('Fehler beim Speichern: ') . $this->Category->validationErrors);
                }
                $position += 1;
            }
        }
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @param string $term_id
     * @return void
     */
    public function view($id, $term_id = null) {
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }

        $this->Category->recursive = -1;
        $category = $this->Category->read(array('Category.*'), $id);

        if ($category['Category']['term_free'] == false) {
            $terms = $this->Category->Lecture->Term->find('list', array('order' => array('Term.ordering ASC')));
            $this->set(compact('terms'));

            if ($term_id == null) {
                // Latest term, first array key.
                $term_id = key($terms);
                $this->redirect('/categories/view/' . $id . '/' . $term_id);
            }
        }

        $this->loadModel('Post');
        $links = $this->Post->findLinks();

        $categoryList = $this->Category->Lecture->find('threaded', array(
            'recursive' => -1,
            'conditions' => array('Lecture.category_id' => $id, 'Lecture.term_id' => $term_id),
            'order' => array('Lecture.ordering ASC')
        ));

        $title_for_layout = $category['Category']['name'];

        $this->set(compact('id', 'term_id', 'category', 'links', 'categoryList', 'category', 'title_for_layout'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Category->create();
            // Place at the end of the list
            $this->request->data['Category']['ordering'] = $this->Category->find('count');

            if ($this->Category->save($this->request->data)) {
                $this->Session->setFlash(__('The category has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * Updates all courses at once
     */
    public function admin_edit() {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Category->saveAll($this->request->data['Category'])) {
                $this->Session->setFlash(__('The Category has been saved'));
            }
            else {
                $this->Session->setFlash(__('The Category could not be saved. Please, try again.'));
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
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__('Invalid Category'));
        }
        if ($this->Category->delete()) {
            $this->Session->setFlash(__('Category deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Category was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
