<?php
App::uses('ListingsController', 'Controller');

/**
 * ListingsController Test Case
 *
 */
class ListingsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.listing',
		'app.category',
		'app.term',
		'app.video',
		'app.type',
		'app.videos_type'
	);

}
