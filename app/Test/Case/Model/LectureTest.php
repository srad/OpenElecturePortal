<?php
App::uses('Lecture', 'Model');

/**
 * Listing Test Case
 *
 */
class ListingTest extends CakeTestCase {

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

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Listing = ClassRegistry::init('Lecture');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Listing);

		parent::tearDown();
	}

}
