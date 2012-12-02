<?php
App::uses('ShopCurrency', 'Shop.Model');

/**
 * ShopCurrency Test Case
 *
 */
class ShopCurrencyTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.shop.shop_currency',
		'plugin.shop.shop_payment_method_api'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShopCurrency = ClassRegistry::init('Shop.ShopCurrency');
		$this->modelClass = $this->ShopCurrency->alias;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShopCurrency);

		parent::tearDown();
	}

/**
 * check there are exceptions thrown when there is no currencies available
 *
 * @expectedException CakeException
 */
	public function testFindCurrencyNoneAvailable() {
		$this->assertTrue((bool)$this->{$this->modelClass}->deleteAll(array('ShopCurrency.id != ' => null)));
		$this->{$this->modelClass}->find('currency');
	}

/**
 * Check that you can set a currency to near 1 without it becoming default
 *
 * @expectedException CakeException
 *
 * @dataProvider nearOneIsNotOneDataProvider
 */
	public function testNearOneIsNotOne($data) {
		$this->{$this->modelClass}->id = 'gbp';
		$this->assertTrue((bool)$this->{$this->modelClass}->saveField('factor', $data));
		$this->{$this->modelClass}->find('currency');
	}

/**
 * near one is not one data provider
 * @return type
 */
	public function nearOneIsNotOneDataProvider() {
		return array(
			array('1.0000001'),
			array('0.9999999')
		);
	}

/**
 * test finding the default + when it changes
 */
	public function testFindCurrencyDefault() {
		$expected = array(
			'ShopCurrency' => array(
				'name' => 'gbp',
				'code' => 'GBP',
				'factor' => 1,
				'whole_symbol' => '£',
				'whole_position' => 0,
				'fraction_symbol' => 'p',
				'fraction_position' => 1,
				'zero' => '0',
				'places' => 2,
				'thousands' => ',',
				'decimals' => '.',
				'negative' => '-',
				'escape' => 1,
			)
		);
		$results = $this->{$this->modelClass}->find('currency');
		$this->assertEquals($expected, $results);

		$this->{$this->modelClass}->id = 'gbp';
		$this->assertTrue((bool)$this->{$this->modelClass}->saveField('factor', '1.2'));

		$this->{$this->modelClass}->id = 'usd';
		$this->assertTrue((bool)$this->{$this->modelClass}->saveField('factor', '1'));
		$expected = array(
			'ShopCurrency' => array(
				'name' => 'usd',
				'code' => 'USD',
				'factor' => '1.00000000',
				'whole_symbol' => '$',
				'whole_position' => false,
				'fraction_symbol' => 'c',
				'fraction_position' => true,
				'zero' => '-',
				'places' => 2,
				'thousands' => ',',
				'decimals' => '.',
				'negative' => '-',
				'escape' => 1,
			)
		);
		$results = $this->{$this->modelClass}->find('currency');
		$this->assertEquals($expected, $results);
	}

/**
 * test find currency specific
 *
 * @dataProvider findCurrencySpecificDataProvider
 */
	public function testFindCurrencySpecific($data, $expected) {
		if (!empty($expected)) {
			$expected = array($this->modelClass => $expected);
		}

		$result = $this->{$this->modelClass}->find('currency', array('currency' => $data));

		if ($result) {
			$res = round($result[$this->modelClass]['factor'], 4);
			$exp = round($expected[$this->modelClass]['factor'], 4);
			$this->assertEquals($exp, $res);
			unset($result[$this->modelClass]['factor'], $expected[$this->modelClass]['factor']);
		}
		$this->assertEquals($expected, $result);
	}

/**
 * find currency specific data provider
 *
 * @return array
 */
	public function findCurrencySpecificDataProvider() {
		return array(
			array(
				false,
				array(
					'name' => 'gbp',
					'code' => 'GBP',
					'factor' => 1,
					'whole_symbol' => '£',
					'whole_position' => 0,
					'fraction_symbol' => 'p',
					'fraction_position' => 1,
					'zero' => '0',
					'places' => 2,
					'thousands' => ',',
					'decimals' => '.',
					'negative' => '-',
					'escape' => 1,
				)
			),
			array(
				'gbp',
				array(
					'name' => 'gbp',
					'code' => 'GBP',
					'factor' => 1,
					'whole_symbol' => '£',
					'whole_position' => 0,
					'fraction_symbol' => 'p',
					'fraction_position' => 1,
					'zero' => '0',
					'places' => 2,
					'thousands' => ',',
					'decimals' => '.',
					'negative' => '-',
					'escape' => 1,
				)
			),
			array(
				'usd',
				array(
					'name' => 'usd',
					'code' => 'USD',
					'factor' => '1.5999',
					'whole_symbol' => '$',
					'whole_position' => 0,
					'fraction_symbol' => 'c',
					'fraction_position' => 1,
					'zero' => '-',
					'places' => 2,
					'thousands' => ',',
					'decimals' => '.',
					'negative' => '-',
					'escape' => 1,
				)
			)
		);
	}

/**
 * test find conversion data
 */
	public function testFindConversionsData() {
		$round = function($data) {
			array_walk($data, function(&$res) {
				$res = round($res, 4);
			});

			return $data;
		};

		$expected = array(
			'GBP' => '1.0000',
			'USD' => '1.5999',
			'EUR' => '1.2425'
		);
		$result = $round($this->{$this->modelClass}->find('conversions'));

		$this->assertEquals($expected, $result);

		$this->assertTrue($this->{$this->modelClass}->deleteAll(array($this->modelClass . '.id' => 'eur')));
		$expected = array(
			'GBP' => '1.0000',
			'USD' => '1.5999'
		);
		$result = $round($this->{$this->modelClass}->find('conversions'));
		$this->assertEquals($expected, $result);
	}

/**
 * test currencies get updated
 */
	public function testUpdate() {
		$now = date('Y-m-d H:i:s');
		App::uses('CakeSession', 'Model/Datasource');
		$before = $this->{$this->modelClass}->find('conversions');
		$this->assertTrue($this->{$this->modelClass}->updateCurrencies());
		$after = $this->{$this->modelClass}->find('conversions');

		$this->assertNotEquals($before, $after);

		$results = $this->{$this->modelClass}->find('list', array(
			'fields' => array(
				$this->modelClass . '.' . $this->{$this->modelClass}->primaryKey,
				$this->modelClass . '.modified',
			),
			'conditions' => array('not' => array(
				$this->modelClass . '.code' => 'GBP'
			))
		));

		foreach ($results as $id => $modified) {
			$this->assertTrue($modified >= $now);
		}
	}

}
