<?php
App::uses('AppController', 'Controller');
/**
 * Listings Controller
 *
 * @property Listing $Listing
 */
class ListingsController extends AppController {

    const MINUTES_UPDATE_CYCLE = 360;

    public $helpers = array('Listing');

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->isAssistant()) {
            $this->Auth->allow('*');
        }
        $this->Auth->allow('view');
    }

    /**
     * @param null $categoryId
     * @param null $termId
     * @return string
     */
    public function admin_index($categoryId = null, $termId = null) {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            $listings = $this->Listing->find('all', array(
                'recursive' => 1,
                'fields' => array('Listing.*', 'Provider.name'),
                'conditions' => array('Listing.category_id' => $this->request->data['category_id'], 'Listing.term_id' => $this->request->data['term_id']),
                'order' => array('Listing.parent_id' => 'ASC', 'Listing.ordering' => 'ASC')
            ));
            return json_encode($listings);
        }

        $providers = $this->Listing->Provider->find('list');
        $terms = $this->Listing->Term->findTermsList();

        $this->set('title_for_layout', __('Übersicht'));
        $this->set(compact('providers', 'terms', 'listings'));
    }

    public function admin_sort() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->Listing->recursive = -1;

            $position = 0;
            foreach ($this->request->data['listing'] as $id => $parentId) {
                $this->Listing->id = $id;
                if ($parentId === 'null') {
                    $parentId = null;
                }
                $this->Listing->set(array('Listing' => array('parent_id' => $parentId, 'ordering' => $position)));
                $this->Listing->save();
                $position += 1;
            }
            $this->Listing->reorder(array('field' => 'Listing.ordering'));
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
        $this->Listing->id = $id;

        if (!$this->Listing->exists()) {
            throw new NotFoundException(__('Ungültige Videoliste'));
        }
        $this->Listing->recursive = 0;
        $this->Listing->data = $this->Listing->read(array('Provider.name', 'Category.*', 'Listing.name', 'Listing.category_id', 'Listing.last_update', 'Listing.code'), $id);

        if ($this->Listing->isUpdateRequired() && $this->Listing->hasValidVideoListId()) {
            $this->Listing->updateVideos();
        }

        $this->Listing->Category->recursive = -1;
        $category = $this->Listing->Category->read(array('Category.name', 'Category.id'), $category_id);

        $terms = $this->Listing->Term->findTermsList();

        $this->loadModel('Post');
        $links = $this->Post->findLinks();

        $categoryList = $this->Listing->findThreadedListing($category_id, $term_id);
        $videos = $this->Listing->findVideoTree($id);

        $title_for_layout = $this->Listing->data['Listing']['name'];
        $listing_id = $id;

        $this->set('isDynamicView', (isset($videos[0]['Listing']) && $videos[0]['Listing']['dynamic_view']) );
        $this->set(compact('id', 'listing_id', 'term_id', 'category', 'videos', 'title_for_layout', 'categoryList', 'terms', 'links'));
    }

    public function admin_add() {
        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->Listing->create();

            $saved = $this->Listing->save(array(
                'Listing' => array(
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
                throw new Exception($this->Listing->validationErrors);
            }
        }
    }

    /**
     * Updates all courses at once
     */
    public function admin_edit() {
        if ($this->request->is('post') || $this->request->is('put')) {
            // Checked boxes are submitted as "on", we convert them to "1"
            foreach ($this->request->data['Listing'] as $key => $item) {
                $this->request->data['Listing'][$key]['inactive'] = (isset($this->request->data['Listing'][$key]['inactive']) && $this->request->data['Listing'][$key]['inactive'] == 'on');
                $this->request->data['Listing'][$key]['dynamic_view'] = (isset($this->request->data['Listing'][$key]['dynamic_view']) && $this->request->data['Listing'][$key]['dynamic_view'] == 'on');
                $this->request->data['Listing'][$key]['invert_sorting'] = (isset($this->request->data['Listing'][$key]['invert_sorting']) && $this->request->data['Listing'][$key]['invert_sorting'] == 'on');
            }

            if ($this->Listing->saveAll($this->request->data['Listing'])) {
                $this->Session->setFlash(__('The Listing has been saved'));
            }
            else {
                $this->Session->setFlash(__('The Listing could not be saved. Please, try again.'));
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
            $this->Listing->id = $this->request->data['id'];

            if (!$this->Listing->exists()) {
                throw new NotFoundException(__('Ungültige Id'));
            }
            if (!$this->Listing->delete()) {
                throw new NotFoundException(__('Eintrag konnte nicht gelöscht werden'));
            }
        }
    }

}
