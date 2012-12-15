<?php
App::uses('AppModel', 'Model');
/**
 * Term Model
 *
 * @property Lecture $Lecture
 */
class Term extends AppModel {

    public $displayField = 'name';

    /**
     * Notice it uses caching. Terms only change once a term.
     */
    public function findTermsList() {
        $terms = Cache::read('terms', 'long');

        if (!$terms) {
            $terms = $this->find('list', array('order' => 'Term.id DESC'));
            Cache::write('terms', $terms, 'long');
        }

        return $terms;
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name'  => array(
            'notempty' => array(
                'rule' => array('notempty'),
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
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Lecture' => array(
            'className'    => 'Lecture',
            'foreignKey'   => 'term_id',
            'dependent'    => false,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => ''
        )
    );
}
