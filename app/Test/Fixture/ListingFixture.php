<?php
/**
 * ListingFixture
 *
 */
class ListingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'last_update' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index'),
		'provider_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'term_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20, 'key' => 'index'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20, 'key' => 'index'),
		'html' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'inactive' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'dynamic_view' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'invert_sorting' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_lists_terms1_idx' => array('column' => 'term_id', 'unique' => 0),
			'fk_lists_categories1_idx' => array('column' => 'category_id', 'unique' => 0),
			'fk_listings_providers1_idx' => array('column' => 'provider_name', 'unique' => 0),
			'fk_listings_listings1_idx' => array('column' => 'parent_id', 'unique' => 0)
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
			'name' => 'Lorem ipsum dolor sit amet',
			'slug' => 'Lorem ipsum dolor sit amet',
			'code' => 'Lorem ipsum dolor sit amet',
			'last_update' => '2012-10-27 13:00:16',
			'category_id' => 1,
			'provider_name' => 'Lorem ipsum dolor sit amet',
			'ordering' => 1,
			'term_id' => 1,
			'parent_id' => 1,
			'html' => 'Lorem ipsum dolor sit amet',
			'inactive' => 1,
			'dynamic_view' => 1,
			'invert_sorting' => 1,
			'created' => '2012-10-27 13:00:16',
			'updated' => '2012-10-27 13:00:16'
		),
	);

}
