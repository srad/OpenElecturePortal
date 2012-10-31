<?php
App::uses('AppModel', 'Model');
/**
 * VideosType Model
 *
 * @property Video $Video
 * @property Type $Type
 */
class VideosType extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'video_id'  => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'type_name' => array(
            'numeric' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'url'       => array(
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
        'Video' => array(
            'className'  => 'Video',
            'foreignKey' => 'video_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        ),
        'Type'  => array(
            'className'  => 'Type',
            'foreignKey' => 'type_name',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        )
    );
}
