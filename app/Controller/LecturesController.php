<?php
App::uses('AppController', 'Controller');
/**
 * Lectures Controller
 *
 * @property Lecture $Lecture
 */
class LecturesController extends AppController {

    public $helpers = array('Lecture', 'Text', 'Rss');

    public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->isAssistant()) {
            $this->Auth->allow('*');
        }
        $this->Auth->allow('view', 'overview');
    }

    /**
     * @param null $categoryId
     * @param null $termId
     * @return string
     */
    public function admin_index($categoryId = null, $termId = null) {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            $lectures = $this->Lecture->find('all', array(
                'recursive' => 1,
                'fields' => array('Lecture.*', 'Provider.name'),
                'conditions' => array('Lecture.category_id' => $this->request->data['category_id'], 'Lecture.term_id' => $this->request->data['term_id']),
                'order' => array('Lecture.parent_id' => 'ASC', 'Lecture.ordering' => 'ASC')
            ));
            return json_encode($lectures);
        }

        $providers = $this->Lecture->Provider->find('list');
        $terms = $this->Lecture->Term->findTermsList();

        $this->set('title_for_layout', __('Übersicht'));
        $this->set(compact('providers', 'terms', 'lectures'));
    }

    public function admin_sort() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->Lecture->recursive = -1;

            $position = 0;
            foreach ($this->request->data['Lecture'] as $id => $parentId) {
                $this->Lecture->id = $id;
                if ($parentId === 'null') {
                    $parentId = null;
                }
                $this->Lecture->set(array('Lecture' => array('parent_id' => $parentId, 'ordering' => $position)));
                $this->Lecture->save();
                $position += 1;
            }
            $this->Lecture->reorder(array('field' => 'Lecture.ordering'));
        }
    }

    /**
     * @param      $id
     * @param      $category_id
     * @param null $term_id
     * @param null $slug Not used right now.
     * @throws NotFoundException
     */
    public function view($id, $category_id, $term_id = null, $slug = null) {
        $this->Lecture->id = $id;

        if (!$this->Lecture->exists()) {
            throw new NotFoundException(__('Ungültige Videoliste'));
        }

        if ($this->RequestHandler->isRss()) {

            $lectures = $this->Lecture->find('first', array(
                'recursive' => 1,
                'conditions' => array('Lecture.id' => $id)
            ));

            $channel = array(
                'title' => $lectures['Lecture']['name'],
                'link' => '/',
                'description' => __('Neusten Videos'),
                'language' => 'DE-de'
            );

            return $this->set(compact('lectures', 'channel'));
        }

        // Update all children
        $childrenIds = $this->Lecture->findChildIds($id);

        $this->Lecture->recursive = 0;
        foreach ($childrenIds as $pos => $listId) {
            $this->Lecture->data = $this->Lecture->read(array('Provider.name', 'Category.*', 'Lecture.name', 'Lecture.category_id', 'Lecture.last_update', 'Lecture.code'), $listId);

            if ($this->Lecture->isUpdateRequired() && $this->Lecture->hasValidVideoListId()) {
                $this->Lecture->updateVideos();
            }
        }

        $this->Lecture->Category->recursive = -1;
        $category = $this->Lecture->Category->read(array('Category.name', 'Category.id'), $category_id);

        $terms = $this->Lecture->Term->findTermsList();

        $this->loadModel('Post');
        $links = $this->Post->findLinks();

        $categoryList = $this->Lecture->findThreadedLectures($category_id, $term_id);
        $videos = $this->Lecture->findVideoTree($id);

        $title_for_layout = $this->Lecture->data['Lecture']['name'];
        $lecture_id = $id;

        $this->set('isDynamicView', (isset($videos[0]['Lecture']) && $videos[0]['Lecture']['dynamic_view']));
        $this->set(compact('id', 'lecture_id', 'term_id', 'category', 'videos', 'title_for_layout', 'categoryList', 'terms', 'links'));
    }

    public function admin_add() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->Lecture->create();

            $saved = $this->Lecture->save(array(
                'Lecture' => array(
                    'name' => $this->request->data['name'],
                    'category_id' => $this->request->data['category_id'],
                    'code' => $this->request->data['code'],
                    'dynamic_view' => $this->request->data['dynamic_view'],
                    'inactive' => $this->request->data['inactive'],
                    'invert_sorting' => $this->request->data['invert_sorting'],
                    'provider_name' => (trim($this->request->data['provider_id']) === '') ? null : $this->request->data['provider_id'],
                    'term_id' => (trim($this->request->data['term_id']) === '') ? null : $this->request->data['term_id'],
                    'ordering' => $this->request->data['position']
                )
            ));

            if (!$saved) {
                throw new Exception($this->Lecture->validationErrors);
            }
        }
    }

    /**
     * Updates all courses at once
     */
    public function admin_edit() {
        if ($this->request->is('post') || $this->request->is('put')) {
            // Checked boxes are submitted as "on", we convert them to "1"
            foreach ($this->request->data['Lecture'] as $key => $item) {
                $this->request->data['Lecture'][$key]['inactive'] = (isset($this->request->data['Lecture'][$key]['inactive']) && $this->request->data['Lecture'][$key]['inactive'] == 'on');
                $this->request->data['Lecture'][$key]['dynamic_view'] = (isset($this->request->data['Lecture'][$key]['dynamic_view']) && $this->request->data['Lecture'][$key]['dynamic_view'] == 'on');
                $this->request->data['Lecture'][$key]['invert_sorting'] = (isset($this->request->data['Lecture'][$key]['invert_sorting']) && $this->request->data['Lecture'][$key]['invert_sorting'] == 'on');
            }

            if ($this->Lecture->saveAll($this->request->data['Lecture'])) {
                $this->Session->setFlash(__('Die Liste wurde gespeichert'));
            }
            else {
                $this->Session->setFlash(__('Das Speichern der Liste ist fehlgeschlagen'));
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
     * @return void
     */
    public function admin_delete() {
        $this->autoRender = false;

        if ($this->request->is('ajax') && isset($this->request->data['id'])) {
            $this->Lecture->id = $this->request->data['id'];

            if (!$this->Lecture->exists()) {
                throw new NotFoundException(__('Ungültige Id'));
            }
            if (!$this->Lecture->delete()) {
                throw new NotFoundException(__('Eintrag konnte nicht gelöscht werden'));
            }
        }
        else {
            throw new MethodNotAllowedException();
        }
    }

    /**
     * Retrieves all lectures as a flat data structure ignoring its
     * tree properties.
     */
    public function overview() {
        $this->Lecture->unbindModel(
            array(
                'hasMany' => array('Video', 'MediaSite', 'Vilea'),
                'belongsTo' => array('Provider', 'Category')
            )
        );

        $lectures = $this->Lecture->find('all', array(
            'fields' => array('Lecture.id', 'Lecture.name', 'Lecture.category_id', 'Term.*'),
            'order' => array('Lecture.name ASC', 'Term.ordering ASC'),
            'conditions' => array('Lecture.provider_name NOT' => null),
            'recursive' => 1
        ));

        $this->set(compact('lectures'));
    }

}
