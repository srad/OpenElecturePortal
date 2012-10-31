<?php
App::uses('Provider', 'Model');

/**
 * Provider Test Case
 *
 */
class ProviderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.provider',
		'app.video'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Provider = ClassRegistry::init('Provider');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Provider);

		parent::tearDown();
	}

}
