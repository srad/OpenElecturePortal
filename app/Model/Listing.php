<?php
App::uses('AppModel', 'Model');
/**
 * Listing Model
 *
 * @property Category $Category
 * @property Term $Term
 * @property Video $Video
 * @property MediaSite $MediaSite
 * @property Vilea $Vilea
 */
class Listing extends AppModel {

    const MINUTES_UPDATE_CYCLE = 360;

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    public $actsAs = array('Tree');

    private $httpVideoData;

    /**
     * Determines based on the 'last_update' database field, if an
     * update is required by using the interval set in
     * @const {MINUTES_UPDATE_CYCLE}.
     *
     * @return bool
     */
    public function isUpdateRequired() {
        if ($this->data['Listing']['last_update'] === null) {
            $update = true;
        }
        else {
            $elapsedMinutesSinceLastUpdate = floor((time() - strtotime($this->data['Listing']['last_update'])) / 60);
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
        return $this->data['Listing']['code'] !== null && $this->data['Listing']['code'] !== '';
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
            $data = $this->Video->find('first', array('recursive' => -1, 'fields' => array('Video.id'),'conditions' => array('Video.video_id' => $videoId)));

            if ($data) {
                $this->Video->id = $data['Video']['id'];
                $this->Video->deleteAllVideoTypes();
            }
            else {
                $this->Video->create();

                $thumbnail = $this->getImageFromUrl($video['thumbnail']);

                $this->Video->set(array(
                    'Video' => array(
                        'listing_id' => $this->id,
                        'title' => $video['title'],
                        'thumbnail' => $thumbnail['image-data'],
                        'thumbnail_mime_type' => $thumbnail['mime-type'],
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
                if ($video[$formatName] != null && $video[$formatName] != '') {
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

    private function getImageFromUrl($url) {
        $image = file_get_contents($url);

        $image_info = getimagesize($url);
        $mimeType = $image_info["mime"];

        return array(
            'image-data' => $image,
            'mime-type'  => $mimeType
        );
    }

    protected function loadVideosViaHttp() {
        switch ($this->data['Provider']['name']) {
            case 'vilea':
                $this->httpVideoData = $this->Vilea->fetch($this->data['Listing']['code']);
                break;
            case 'mediasite':
                $this->httpVideoData = $this->MediaSite->fetch($this->data['Listing']['code']);
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
     * In this case we are limited to a tree depth of 2,
     * mysql doesn't support recursive joins, deeper trees
     * need additional joins or another data structure.
     *
     * @param $category_id
     * @param $term_id
     * @return array
     */
    public function findThreaded($category_id, $term_id) {
        $sql = <<<EOD
        SELECT
            Category.id, Category.name,
            Listing.id, Listing.name, Listing.code, Listing.parent_id, Listing.category_id, Listing.inactive, Listing.invert_sorting, Listing.dynamic_view,
            Children1.id, Children1.parent_id, Children1.name, Children1.code, Children1.parent_id, Children1.inactive, Children1.invert_sorting, Children1.dynamic_view,
            Children2.id, Children2.parent_id, Children2.name, Children2.code, Children2.parent_id, Children2.inactive, Children2.invert_sorting, Children2.dynamic_view
        FROM
            categories Category
            INNER JOIN listings Listing ON (Category.id = Listing.category_id)
            LEFT OUTER JOIN listings Children1 ON (Listing.id = Children1.parent_id)
            LEFT OUTER JOIN listings Children2 ON (Children1.id = Children2.parent_id)
        WHERE
            Category.id = ? AND Listing.term_id = ?
            AND
            Listing.parent_id IS NULL
        ORDER BY
            Listing.ordering ASC,
            Children1.ordering ASC,
            Children2.ordering ASC;
EOD;

        $rows = $this->query($sql, array($category_id, $term_id));

        // We use a hashing method for nesting
        $nestedArray = array();

        foreach ($rows as $row) {
            if (!isset($nestedArray['Category'])) {
                $nestedArray['Category'] = $row['Category'];
            }
            if (!isset($nestedArray['Listing'][$row['Listing']['id']])) {
                $nestedArray['Listing'][$row['Listing']['id']] = $row['Listing'];
            }
            if ($row['Children1']['id'] !== null) {
                $nestedArray['Listing'][$row['Listing']['id']]['Children1'][$row['Children1']['id']] = $row['Children1'];
            }
            if ($row['Children2']['id'] !== null) {
                $nestedArray['Listing'][$row['Listing']['id']]['Children1'][$row['Children1']['id']]['Children2'][$row['Children2']['id']] = $row['Children2'];
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
            'foreignKey' => 'provider_id',
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
            'foreignKey' => 'listing_id',
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
        'MediaSite' => array(
            'className' => 'MediaSite',
            'foreignKey' => 'listing_id',
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
            'foreignKey' => 'listing_id',
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
