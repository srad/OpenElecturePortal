<?php
App::uses('AppModel', 'Model');
/**
 * Video Model
 *
 * @property Listing $Listing
 * @property Video $Video
 * @property Type $Type
 */
class Video extends AppModel {

    public function deleteAllVideoTypes() {
        $this->query('DELETE FROM videos_types WHERE videos_types.video_id = ?', array($this->id));
    }

    public function removeNotAnymoreExistentVideoIds($listingId, $videoIds) {
        // Delete ids, which don't exist anymore
        $this->deleteAll(array(
            'Video.listing_id' => $listingId,
            'NOT' => array('Video.video_id' => $videoIds)
        ), true);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'listing_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'video_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
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
        'Listing' => array(
            'className' => 'Listing',
            'foreignKey' => 'listing_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Type' => array(
            'className' => 'Type',
            'joinTable' => 'videos_types',
            'foreignKey' => 'video_id',
            'associationForeignKey' => 'type_name',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

}
