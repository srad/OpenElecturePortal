<?php
App::uses('VideosType', 'Model');

/**
 * VideosType Test Case
 *
 */
class VideosTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.videos_type',
		'app.video',
		'app.listing',
		'app.category',
		'app.term',
		'app.provider',
		'app.type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VideosType = ClassRegistry::init('VideosType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VideosType);

		parent::tearDown();
	}

}
