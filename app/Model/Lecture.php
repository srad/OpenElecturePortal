<?php
App::uses('AppModel', 'Model');
/**
 * Lecture Model
 *
 * @property Category $Category
 * @property Term $Term
 * @property Video $Video
 * @property MediaSite $MediaSite
 * @property Vilea $Vilea
 */
class Lecture extends AppModel {

    /** The amount if minutes between each update from the external video portals. */
    const MINUTES_UPDATE_CYCLE = 60;

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    public $actsAs = array('Tree');

    private $httpVideoData;

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->name]['name'])) {
            $this->data[$this->name]['slug'] = Inflector::slug(strtolower($this->data[$this->name]['name']), '-');
        }
    }

    public function findThreadedLectures($category_id = null, $term_id = null) {
        $cacheKey = 'threaded_list_'. $category_id .'_'. $term_id;

        $list = Cache::read($cacheKey, 'short');

        if (!$list) {
            $list = $this->find('threaded', array(
                'recursive' => -1,
                'conditions' => array('Lecture.category_id' => $category_id, 'Lecture.term_id' => $term_id),
                'order' => array('Lecture.ordering ASC')
            ));
            Cache::write($cacheKey, $list, 'short');
        }

        return $list;
    }

    /**
     * Searches the child ids for a given parent id. Queries the whole tree and nests them to a tree.
     *
     * TODO: The ORM messes this query up by adding many duplicate SELECT statements. This must be rewritten. But the cache lowers the pain a lot.
     *
     * @param $parentId
     * @return array
     */
    public function findVideoTree($parentId) {
        $childIds = $this->findChildIds($parentId);

        $cacheKey = 'video_tree_' . $parentId;
        $videos = Cache::read($cacheKey, 'short');

        // We write the video tree to the cache
        // because the processing is pretty elaborate.
        if (!$videos) {
            $videos = $this->find('all', array(
                'fields' => array('Lecture.name', 'Lecture.id', 'Lecture.parent_id', 'Lecture.dynamic_view', 'Lecture.term_id', 'Lecture.category_id'),
                'conditions' => array('Lecture.id' => $childIds),
                'recursive' => 2
            ));

            $videos = Hash::nest($videos, array(
                'idPath' => '{n}.Lecture.id',
                'parentPath' => '{n}.Lecture.parent_id'
            ));

            Cache::write($cacheKey, $videos, 'short');
        }

        return $videos;
    }

    /**
     * Determines based on the 'last_update' database field, if an
     * update is required by using the interval set in
     *
     * @const {MINUTES_UPDATE_CYCLE}.
     * @return bool
     */
    public function isUpdateRequired() {
        if ($this->data['Lecture']['last_update'] === null) {
            $update = true;
        }
        else {
            $elapsedMinutesSinceLastUpdate = floor((time() - strtotime($this->data['Lecture']['last_update'])) / 60);
            $update = $elapsedMinutesSinceLastUpdate > self::MINUTES_UPDATE_CYCLE;
        }
        return $update;
    }

    /**
     * Checks if a video id list if set.
     *
     * @return bool
     */
    public function hasValidVideoListId() {
        return !empty($this->data['Lecture']['code']);
    }

    /**
     * This method fetches via the http data sources the latest videos.
     * 1. Delete all old videos
     * 2. Check via the Provider.name if we have to fetch the data via mediasite or vilea.
     * 3. Get the data from the model
     * 4. insert all new videos.
     *
     * @return bool
     * @throws Exception
     */
    public function updateVideos() {
        $this->loadVideosViaHttp();
        $this->Video->removeNotAnymoreExistentVideoIds($this->id, $this->httpVideoData['ids']);

        // We iterate though all returned video types
        $formats = $this->Video->Type->find('list');

        foreach ($this->httpVideoData['data'] as $videoId => $video) {
            $data = $this->Video->find('first', array('recursive' => -1, 'fields' => array('Video.id'), 'conditions' => array('Video.video_id' => $videoId)));

            if ($data) {
                $this->Video->id = $data['Video']['id'];
                $this->Video->deleteAllVideoTypes();
            }
            else {
                $this->Video->create();

                $this->Video->set(array(
                    'Video' => array(
                        'lecture_id' => $this->id,
                        'title' => $video['title'],
                        'thumbnail_url' => $video['thumbnail'],
                        'subtitle' => $video['subtitle'],
                        'speaker' => $video['speaker'],
                        'location' => $video['location'],
                        'video_date' => $video['date'],
                        'video_id' => $video['video_id'],
                        'description' => $video['description'],
                    )
                ));

                if (!$this->Video->save()) {
                    throw new Exception(__('Neues Video konnte nicht gespeichert werden: ') . $this->Video->validationErrors);
                }
            }

            // Save video types
            $saves = array(
                'Video' => array('id' => $this->Video->id),
                'Type' => array()
            );

            // Build a list for the video type list
            // to save all video types at once for the many to many relationship.
            foreach ($formats as $formatId => $formatName) {
                if (isset($video[$formatName]) && !empty($video[$formatName])) {
                    array_push($saves['Type'], array(
                        'type_name' => $formatName,
                        'url' => $video[$formatName]
                    ));
                }
            }

            if (!$this->Video->saveAll($saves)) {
                throw new Exception('Fehler beim Speichern der Videoformate');
            }
        }

        return $this->saveField('last_update', date('Y-m-d H:i:s'));
    }

    protected function loadVideosViaHttp() {
        switch ($this->data['Provider']['name']) {
            case 'vilea':
                $this->httpVideoData = $this->Vilea->fetch($this->data['Lecture']['code']);
                break;
            case 'mediasite':
                $this->httpVideoData = $this->MediaSite->fetch($this->data['Lecture']['code']);
                break;
            default:
                throw new Exception('Ungültiges Videoportal');
        }
    }

    /**
     * Returns a nested list that we can use for the video list at the
     * right sidebar and the editable menu list within the admin backend.
     *
     * We can't use the build in "threaded" cakephp function
     * because the nested video results have a wrong sorting.
     *
     * In this case we are limited to a tree depth of 2 as required,
     * mysql doesn't support recursive joins, deeper trees
     * need additional joins or another data structure.
     *
     * @param $category_id
     * @param $term_id
     * @return array
     */
    public function findThreaded($category_id, $term_id) {
        $params = array($category_id);

        if ($term_id != null) {
            array_push($params, $term_id);
        }

        $sql = '';
        $sql .= ' SELECT';
        $sql .= '    Category.id, Category.name,';
        $sql .= '    Lecture.id, Lecture.name, Lecture.code, Lecture.parent_id, Lecture.category_id, Lecture.inactive, Lecture.invert_sorting, Lecture.dynamic_view,';
        $sql .= '    Children1.id, Children1.parent_id, Children1.name, Children1.code, Children1.inactive, Children1.invert_sorting, Children1.dynamic_view,';
        $sql .= '    Children2.id, Children2.parent_id, Children2.name, Children2.code, Children2.inactive, Children2.invert_sorting, Children2.dynamic_view';

        $sql .= ' FROM';
        $sql .= '    categories Category';
        $sql .= '    INNER JOIN lectures Lecture ON (Category.id = Lecture.category_id)';
        $sql .= '    LEFT OUTER JOIN lectures Children1 ON (Lecture.id = Children1.parent_id)';
        $sql .= '    LEFT OUTER JOIN lectures Children2 ON (Children1.id = Children2.parent_id)';

        $sql .= ' WHERE';
        $sql .= '    Category.id = ?' . (($term_id != null) ? ' AND Lecture.term_id = ?' : '');
        $sql .= '    AND';
        $sql .= '    Lecture.parent_id IS NULL';

        $sql .= ' ORDER BY';
        $sql .= '    Lecture.ordering ASC,';
        $sql .= '    Children1.ordering ASC,';
        $sql .= '    Children2.ordering ASC;';

        $rows = $this->query($sql, $params);

        // We use a hashing method for nesting
        $nestedArray = array();

        foreach ($rows as $row) {
            if (!isset($nestedArray['Category'])) {
                $nestedArray['Category'] = $row['Category'];
            }
            if (!isset($nestedArray['Lecture'][$row['Lecture']['id']])) {
                $nestedArray['Lecture'][$row['Lecture']['id']] = $row['Lecture'];
            }
            if ($row['Children1']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']] = $row['Children1'];
            }
            if ($row['Children2']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Children2'][$row['Children2']['id']] = $row['Children2'];
            }
        }
        return $nestedArray;
    }

    /**
     * Returns an array of all children ids, which is useful to query subtrees.
     * This tree is limited to the depth of 2, the requirement doesn't need more.
     *
     * @param $parentId
     * @return mixed
     */
    public function findChildIds($parentId) {
        $sql = '';
        $sql .= ' SELECT Lecture.id, Children1.id, Children2.id';
        $sql .= ' FROM lectures Lecture';
        $sql .= '   LEFT OUTER JOIN lectures Children1 ON (Lecture.id = Children1.parent_id)';
        $sql .= '   LEFT OUTER JOIN lectures Children2 ON (Children1.id = Children2.parent_id)';
        $sql .= ' WHERE Lecture.id = ?';

        $rows = $this->query($sql, array($parentId));
        $rows = Hash::flatten($rows);

        $ids = array();
        foreach($rows as $colName => $childId) {
            if ($childId != null) {
                array_push($ids, $childId);
            }
        }
        return $ids;
    }

    /**
     * This query returns all video data down to the tree depth of 2
     * this data is supposed to be used for the final output of the video
     * rows.
     *
     * This query is really expensive but is compensated by Cakephps query caching
     * and sorted indexes in the database for the video_date and ordering.
     *
     * @param $categoryId
     * @param $termId
     * @return array
     */
    public function findVideosGroupedByCategoryAndListing($categoryId, $termId) {
        $params = array($categoryId);

        if ($termId != null) {
            array_push($params, $termId);
        }

        $sql = '';
        $sql .= ' SELECT';
        $sql .= '    Category.id, Category.name,';
        $sql .= '    Lecture.id, Lecture.name, Lecture.code, Lecture.parent_id, Lecture.category_id, Lecture.inactive, Lecture.invert_sorting, Lecture.dynamic_view,';
        $sql .= '    Lecture_Video.id, Lecture_Video.title, Lecture_Video.subtitle, Lecture_Video.speaker, Lecture_Video.location, Lecture_Video.description,';
        $sql .= '    Lecture_Video.thumbnail, Lecture_Video.thumbnail_mime_type, Lecture_Video.video_date,';
        $sql .= '    Lecture_VideoTypes.type_name, Lecture_VideoTypes.url,';

        $sql .= '    Children1.id, Children1.name, Children1.code, Children1.parent_id, Children1.inactive, Children1.invert_sorting, Children1.dynamic_view,';
        $sql .= '    Children1_Video.id, Children1_Video.title, Children1_Video.subtitle, Children1_Video.speaker, Children1_Video.location, Children1_Video.description,';
        $sql .= '    Children1_Video.thumbnail, Children1_Video.thumbnail_mime_type, Children1_Video.video_date,';
        $sql .= '    Children1_VideoTypes.type_name, Children1_VideoTypes.url,';
        
        $sql .= '    Children2.id, Children2.name, Children2.code, Children2.parent_id, Children2.inactive, Children2.invert_sorting, Children2.dynamic_view,';
        $sql .= '    Children2_Video.id, Children2_Video.title, Children2_Video.subtitle, Children2_Video.speaker, Children2_Video.location, Children2_Video.description,';
        $sql .= '    Children2_Video.thumbnail, Children2_Video.thumbnail_mime_type, Children2_Video.video_date,';
        $sql .= '    Children2_VideoTypes.type_name, Children2_VideoTypes.url';
        
        $sql .= ' FROM';
        $sql .= '    categories Category';
        $sql .= '    INNER JOIN lectures Lecture ON (Category.id = Lecture.category_id)';
        $sql .= '    LEFT OUTER JOIN videos Lecture_Video ON (Lecture.id = Lecture_Video.lecture_id)';
        $sql .= '    LEFT OUTER JOIN videos_types Lecture_VideoTypes ON (Lecture_Video.id = Lecture_VideoTypes.video_id)';
        
        $sql .= '    LEFT OUTER JOIN lectures Children1 ON (Lecture.id = Children1.parent_id)';
        $sql .= '    LEFT OUTER JOIN videos Children1_Video ON (Children1.id = Children1_Video.lecture_id)';
        $sql .= '    LEFT OUTER JOIN videos_types Children1_VideoTypes ON (Children1_Video.id = Children1_VideoTypes.video_id)';
        
        $sql .= '    LEFT OUTER JOIN lectures Children2 ON (Children1.id = Children2.parent_id)';
        $sql .= '    LEFT OUTER JOIN videos Children2_Video ON (Children2.id = Children2_Video.lecture_id)';
        $sql .= '    LEFT OUTER JOIN videos_types Children2_VideoTypes ON (Children2_Video.id = Children2_VideoTypes.video_id)';

        $sql .= ' WHERE';
        $sql .= '    Category.id = ?' . (($termId != null) ? ' AND Lecture.term_id = ?' : '');

        $sql .= ' ORDER BY';
        $sql .= '    Lecture.ordering ASC,';
        $sql .= '    Children1.ordering ASC,';
        $sql .= '    Children2.ordering ASC,';
        $sql .= '    Lecture_Video.video_date DESC,';
        $sql .= '    Children1_Video.video_date DESC,';
        $sql .= '    Children2_Video.video_date DESC';

        $rows = $this->query($sql, $params);

        // We use a hashing method for nesting
        $nestedArray = array();

        foreach ($rows as $row) {
            if (!isset($nestedArray['Category'])) {
                $nestedArray['Category'] = $row['Category'];
            }

            if (!isset($nestedArray['Lecture'][$row['Lecture']['id']])) {
                $nestedArray['Lecture'][$row['Lecture']['id']] = $row['Lecture'];
            }
            if ($row['Lecture_Video']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Video'][$row['Lecture_Video']['id']] = $row['Lecture_Video'];
            }
            if ($row['Lecture_VideoTypes']['type_name'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Video'][$row['Lecture_Video']['id']][$row['Lecture_VideoTypes']['type_name']] = $row['Lecture_VideoTypes'];
            }

            if ($row['Children1']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']] = $row['Children1'];
            }
            if ($row['Children1_Video']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Video'][$row['Children1_Video']['id']] = $row['Children1_Video'];
            }
            if ($row['Children1_VideoTypes']['type_name'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Video'][$row['Children1_Video']['id']][$row['Children1_VideoTypes']['type_name']] = $row['Children1_VideoTypes'];
            }

            if ($row['Children2']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Children2'][$row['Children2']['id']] = $row['Children2'];
            }
            if ($row['Children2_Video']['id'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Children2'][$row['Children2']['id']]['Video'][$row['Children2_Video']['id']] = $row['Children2_Video'];
            }
            if ($row['Children2_VideoTypes']['type_name'] !== null) {
                $nestedArray['Lecture'][$row['Lecture']['id']]['Children1'][$row['Children1']['id']]['Children2'][$row['Children2']['id']]['Video'][$row['Children2_Video']['id']][$row['Children2_VideoTypes']['type_name']] = $row['Children2_VideoTypes'];
            }
        }
        return $nestedArray;
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'inactive' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'dynamic_view' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'invert_sorting' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'ordering' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Term' => array(
            'className' => 'Term',
            'foreignKey' => 'term_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Provider' => array(
            'className' => 'Provider',
            'foreignKey' => 'provider_name',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'lecture_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => array('IF((SELECT invert_sorting FROM lectures WHERE lectures.id = Video.lecture_id) = 1, unix_timestamp(Video.video_date), -unix_timestamp(Video.video_date))'),
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'MediaSite' => array(
            'className' => 'MediaSite',
            'foreignKey' => 'lecture_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Vilea' => array(
            'className' => 'Vilea',
            'foreignKey' => 'lecture_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

}
