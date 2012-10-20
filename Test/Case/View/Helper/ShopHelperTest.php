<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('ShopHelper', 'Shop.View/Helper');

/**
 * ShopHelper Test Case
 *
 */
class ShopHelperTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.routes.route',
		'plugin.themes.theme',
		'plugin.shop.shop_currency'
	);
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Shop = new ShopHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shop);
		CakeSession::destroy();

		parent::tearDown();
	}

/**
 * testEmailLink method
 *
 * @dataProvider emailLinkDataProvider
 */
	public function testEmailLink($data, $expected) {
		$result = $this->Shop->emailLink($data['email'], $data['icon'], $data['config']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief email link data provider
 * 
 * @return array
 */
	public function emailLinkDataProvider() {
		return array(
			'icon' => array(
				array(
					'email' => 'bob@bob.com',
					'icon' => true,
					'config' => array()
				),
				array(
					array('a' => array('href' => 'mailto:bob@bob.com', 'title' => 'bob@bob.com', 'target' => '_blank')),
					array('img' => array('src' => '/emails/img/icon.png', 'width' => 24, 'alt' => 'Email')),
					'/a'
				)
			),
			'no-icon' => array(
				array(
					'email' => 'bob@bob.com',
					'icon' => false,
					'config' => array()
				),
				array(
					array('a' => array('href' => 'mailto:bob@bob.com', 'title' => 'bob@bob.com', 'target' => '_blank')),
					'bob@bob.com',
					'/a'
				)
			),
			'config' => array(
				array(
					'email' => 'bob@bob.com',
					'icon' => true,
					'config' => array(
						'target' => '_self',
						'title' => 'Awesome title'
					)
				),
				array(
					array('a' => array('href' => 'mailto:bob@bob.com', 'title' => 'Awesome title', 'target' => '_self')),
					array('img' => array('src' => '/emails/img/icon.png', 'width' => 24, 'alt' => 'Email')),
					'/a'
				)
			)
		);
	}

/**
 * @brief test time estimate
 *
 * @dataProvider timeEstimateDataProvider
 */
	public function testTimeEstimate($data, $expected) {
		$result = $this->Shop->timeEstimate($data);
		$this->assertEquals($expected, $result);
	}

/**
 * @brief time estimate data porvider
 * 
 * @return array
 */
	public function timeEstimateDataProvider() {
		return array(
			'hours-1' => array(1, '1 hour'),
			'hours-20' => array(20, '20 hours'),
			'hours-25' => array(25, '1 day'),
			'hours-100' => array(100, '4 days'),
			'hours-200' => array(200, '1 week'),
			'hours-400' => array(400, '2 weeks'),
			'hours-1000' => array(1000, '6 weeks'),
			'hours-1500' => array(1500, '2 months'),
		);
	}

/**
 * @brief test admin currency
 *
 * @dataProvider adminCurrencyDataProvider
 */
	public function testAdminCurrency($data, $expected) {
		$result = $this->Shop->adminCurrency($data);
		$this->assertEquals($expected, $result);
	}

/**
 * @brief admin currency data provider
 * 
 * @return array
 */
	public function adminCurrencyDataProvider() {
		return array(
			'penny' => array(.1, '10p'),
			'pound' => array(1, '&#163;1.00'),
			'high' => array(1234.56, '&#163;1,234.56'),
			'neg' => array(-123.999, '(&#163;124.00)')
		);
	}

/**
 * @brief test stock value
 *
 * @dataProvider stockValueDataProvider
 */
	public function testStockValue($data, $expected) {
		$result = $this->Shop->stockValue($data['quantity'], $data['price']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief stock value data provider
 * 
 * @return array
 */
	public function stockValueDataProvider() {
		return array(
			'normal' => array(
				array(
					'quantity' => 10,
					'price' => 10
				),
				array(
					array('div' => array('class' => 'stock-value')),
						array('span' => array('class' => 'quantity')),
							10,
						'/span',
						array('span' => array('class' => 'value')),
							'&#163;100.00',
						'/span',
					'/div'
				)
			),
			'negative-stock' => array(
				array(
					'quantity' => -10,
					'price' => 10
				),
				array(
					array('div' => array('class' => 'stock-value')),
						array('span' => array('class' => 'quantity')),
							-10,
						'/span',
						array('span' => array('class' => 'value')),
							'&#163;0.00',
						'/span',
					'/div'
				)
			),
			'null-price' => array(
				array(
					'quantity' => 10,
					'price' => null
				),
				array(
					array('div' => array('class' => 'stock-value')),
						array('span' => array('class' => 'quantity')),
							10,
						'/span',
						array('span' => array('class' => 'value')),
							'&#163;0.00',
						'/span',
					'/div'
				)
			)
		);
	}

/**
 * @brief test currency
 * 
 * @dataProvider currencyDataProvider
 */
	public function testCurrency($data, $expected) {
		$result = $this->Shop->currency($data['amount'], $data['to']);
		$this->assertEquals($expected, $result);

	}

/**
 * @brief currency data provider 
 * 
 * @return array
 */
	public function currencyDataProvider() {
		return array(
			'default' => array(
				array(
					'amount' => 100.00,
					'to' => null
				),
				'£100.00'
			),
			'gbp' => array(
				array(
					'amount' => 100.00,
					'to' => 'gbp'
				),
				'£100.00'
			),
			'usd' => array(
				array(
					'amount' => 100.00,
					'to' => 'usd'
				),
				'$159.99'
			)
		);
	}

/**
 * @brief test admin status
 * 
 * @dataProvider adminStatusMissingDataProvider
 */
	public function testAdminStatusMissing($data, $expected) {
		$expected = array(
			array('img' => array(
				'src' => '/img/core/icons/status/inactive.png',
				'width' => '20px',
				'class' => 'icon-status',
				'title' => sprintf('Missing data :: Unable to determin the status of the product (Missing %s)', $expected),
				'alt' => 'Off'
			))
		);
		$data = array_merge(array(
			'ShopProduct' => array(),
			'ShopBrand' => array(),
			'ShopProductType' => array(),
			'ShopSupplier' => array()
		), $data);

		$result = $this->Shop->adminStatus($data);
		$this->assertTags($result, $expected);
	}

/**
 * @brief admin status missing data provider
 * 
 * @return array
 */
	public function adminStatusMissingDataProvider() {
		return array(
			'product status' => array(
				array(),
				'product status'
			),
			'product available date' => array(
				array(
					'ShopProduct' => array(
						'active' => null
					),
				),
				'product available date'
			),
			'brand' => array(
				array(
					'ShopProduct' => array(
						'active' => null,
						'available' => null
					),
				),
				'brand'
			),
			'product type' => array(
				array(
					'ShopProduct' => array(
						'active' => null,
						'available' => null
					),
					'ShopBrand' => array(
						'active' => null
					),
				),
				'product type'
			),
			'supplier' => array(
				array(
					'ShopProduct' => array(
						'active' => null,
						'available' => null
					),
					'ShopBrand' => array(
						'active' => null
					),
					'ShopProductType' => array(
						'active' => null
					),
				),
				'supplier'
			),
			'cateogry status' => array(
				array(
					'ShopProduct' => array(
						'active' => null,
						'available' => null
					),
					'ShopBrand' => array(
						'active' => null
					),
					'ShopProductType' => array(
						'active' => null
					),
					'ShopSupplier' => array(
						'active' => null
					)
				),
				'cateogry status'
			)
		);
	}

/**
 * @brief test admin status
 *
 * @dataProvider adminStatusDataProvider
 */
	public function testAdminStatus($data, $expected) {
		$result = $this->Shop->adminStatus($data);
		$this->assertTags($result, $expected);
	}

/**
 * @brief admin status data provider
 * 
 * @return array
 */
	public function adminStatusDataProvider() {
		return array(
			'all-inactive' => array(
				array(
					'ShopProduct' => array(
						'active' => false,
						'available' => '2050-01-01 00:00:00',
						'category_active' => false
					),
					'ShopBrand' => array(
						'active' => false
					),
					'ShopProductType' => array(
						'active' => false
					),
					'ShopSupplier' => array(
						'active' => false
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product is disabled&lt;/li&gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;li &gt;Brand has been disabled&lt;/li&gt;&lt;li &gt;Product type has been disabled&lt;/li&gt;&lt;li &gt;Supplier has been disabled&lt;/li&gt;&lt;li &gt;Not linked to any categories&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'supplier' => array(
				array(
					'ShopProduct' => array(
						'active' => false,
						'available' => '2050-01-01 00:00:00',
						'category_active' => false
					),
					'ShopBrand' => array(
						'active' => false
					),
					'ShopProductType' => array(
						'active' => false
					),
					'ShopSupplier' => array(
						'active' => true
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product is disabled&lt;/li&gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;li &gt;Brand has been disabled&lt;/li&gt;&lt;li &gt;Product type has been disabled&lt;/li&gt;&lt;li &gt;Not linked to any categories&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'product-type' => array(
				array(
					'ShopProduct' => array(
						'active' => false,
						'available' => '2050-01-01 00:00:00',
						'category_active' => false
					),
					'ShopBrand' => array(
						'active' => false
					),
					'ShopProductType' => array(
						'active' => true
					),
					'ShopSupplier' => array(
						'active' => true
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product is disabled&lt;/li&gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;li &gt;Brand has been disabled&lt;/li&gt;&lt;li &gt;Not linked to any categories&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'brand' => array(
				array(
					'ShopProduct' => array(
						'active' => false,
						'available' => '2050-01-01 00:00:00',
						'category_active' => false
					),
					'ShopBrand' => array(
						'active' => true
					),
					'ShopProductType' => array(
						'active' => true
					),
					'ShopSupplier' => array(
						'active' => true
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product is disabled&lt;/li&gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;li &gt;Not linked to any categories&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'product' => array(
				array(
					'ShopProduct' => array(
						'active' => true,
						'available' => '2050-01-01 00:00:00',
						'category_active' => false
					),
					'ShopBrand' => array(
						'active' => true
					),
					'ShopProductType' => array(
						'active' => true
					),
					'ShopSupplier' => array(
						'active' => true
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;li &gt;Not linked to any categories&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'category' => array(
				array(
					'ShopProduct' => array(
						'active' => true,
						'available' => '2050-01-01 00:00:00',
						'category_active' => true
					),
					'ShopBrand' => array(
						'active' => true
					),
					'ShopProductType' => array(
						'active' => true
					),
					'ShopSupplier' => array(
						'active' => true
					),
					'ShopCategory' => array(
						'stuff'
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/inactive.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Disabled :: This product will not be available to customers.&lt;br/&gt;&lt;ul  &gt;&lt;li &gt;Product will be available after Jan 1st 2050, 00:00&lt;/li&gt;&lt;/ul&gt;',
						'alt' => 'Off'
					))
				)
			),
			'all-active' => array(
				array(
					'ShopProduct' => array(
						'active' => true,
						'available' => '2010-01-01 00:00:00',
						'category_active' => true
					),
					'ShopBrand' => array(
						'active' => true
					),
					'ShopProductType' => array(
						'active' => true
					),
					'ShopSupplier' => array(
						'active' => true
					),
					'ShopCategory' => array(
						'stuff'
					)
				),
				array(
					array('img' => array(
						'src' => '/img/core/icons/status/active.png',
						'width' => '20px',
						'class' => 'icon-status',
						'title' => 'Available :: This product is available to customers for purchase',
						'alt' => 'On'
					))
				)
			)
		);
	}

/**
 * @brief test admin price
 * 
 * @dataProvider adminPriceDataProvider
 */
	public function testAdminPrice($data, $expected) {
		$result = $this->Shop->adminPrice($data);
		$this->assertTags($result, $expected);
	}

/**
 * @brief admin price data provider
 * 
 * @return array
 */
	public function adminPriceDataProvider() {
		return array(
			array(
				array(
					'selling' => '12.00000',
					'retail' => '15.00000',
					'cost' => '10.00000',
				),
				array(
					array('div' => array('class' => 'price')),
						array('span' => array(
							'class' => 'cost', 
							'title' => 'Price :: &lt;ul  &gt;&lt;li &gt;Cost: £10.00&lt;/li&gt;&lt;li &gt;Selling: £12.00&lt;/li&gt;&lt;li &gt;Markup: 2 (20)&lt;/li&gt;&lt;li &gt;Retail: £15.00&lt;/li&gt;&lt;/ul&gt;'
						)),
							'£10.00',
						'/span',
						array('span' => array('class' => 'selling')),
							'£12.00',
						'/span',
					'/div'
				)
			)
		);
	}

/**
 * @brief test admin markup
 * 
 * @dataProvider adminMarkupDataProvider
 */
	public function testAdminMarkup($data, $expected) {
		$result = $this->Shop->adminMarkup($data['price'], $data['%']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief admin markup data provider
 * 
 * @return array
 */
	public function adminMarkupDataProvider() {
		return array(
			'normal' => array(
				array(
					'price' => array(
						'selling' => '12.00000',
						'cost' => '10.00000',
					),
					'%' => false
				),
				array(
					array('div' => array('class' => 'markup')),
						array('span' => array('class' => 'amount')),
							'£2.00',
						'/span',
						array('span' => array('class' => 'percentage')),
							'20.00%',
						'/span',
					'/div'
				)
			),
			'negative-markup' => array(
				array(
					'price' => array(
						'selling' => '10.00000',
						'cost' => '12.00000',
					),
					'%' => false
				),
				array(
					array('div' => array('class' => 'markup')),
						array('span' => array('class' => 'amount')),
							'-£2.00',
						'/span',
						array('span' => array('class' => 'percentage')),
							'-16.67%',
						'/span',
					'/div'
				)
			),
			'even' => array(
				array(
					'price' => array(
						'selling' => '10.00000',
						'cost' => '10.00000',
					),
					'%' => false
				),
				array(
					array('div' => array('class' => 'markup')),
						array('span' => array('class' => 'amount')),
							'0',
						'/span',
						array('span' => array('class' => 'percentage')),
							'0.00%',
						'/span',
					'/div'
				)
			)
		);
	}

}
