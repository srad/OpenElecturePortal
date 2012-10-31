<?php
/**
 * ListFixture
 *
 */
class ListFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index'),
		'term_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'html' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'sorting_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'inactive' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 45),
		'dynamic_view' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'position' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'invert_sorting' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'idx_unique_list_per_term' => array('column' => array('term_id', 'category_id'), 'unique' => 1),
			'fk_lists_terms1_idx' => array('column' => 'term_id', 'unique' => 0),
			'fk_lists_categories1_idx' => array('column' => 'category_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'category_id' => 1,
			'term_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'code' => 'Lorem ipsum dolor sit amet',
			'html' => 'Lorem ipsum dolor sit amet',
			'sorting_id' => 'Lorem ipsum dolor ',
			'inactive' => 1,
			'dynamic_view' => 1,
			'position' => 1,
			'parent_id' => 1,
			'lft' => 1,
			'rght' => 1,
			'invert_sorting' => 1,
			'created' => '2012-10-18 23:30:58',
			'updated' => '2012-10-18 23:30:58'
		),
	);

}
