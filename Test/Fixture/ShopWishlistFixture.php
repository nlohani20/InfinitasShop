<?php
/* ShopWishlist Fixture generated on: 2010-08-17 14:08:22 : 1282055242 */
class ShopWishlistFixture extends CakeTestFixture {
	public $name = 'ShopWishlist';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'product_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
	);
}
?>