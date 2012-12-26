<?php
App::uses('AppModel', 'Model');
/**
 * Link Model
 *
 */
class Link extends AppModel {

    public function findLinks() {
        $links = Cache::read('links', 'long');

        if (!$links) {
            $links = $this->find('all', array(
                'recursive'  => -1,
                'fields'     => array('Link.id', 'Link.title', 'Link.url')
            ));
            Cache::write('links', $links, 'long');
        }

        return $links;
    }

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'url' => array(
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
}
