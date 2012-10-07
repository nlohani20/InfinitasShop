<?php
/**
 * ShopOptionFixture
 *
 */
class ShopOptionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'option-size',
			'name' => 'option-size',
			'slug' => 'option-size',
			'option_count' => 3,
			'created' => '2012-10-05 09:59:11',
			'modified' => '2012-10-05 09:59:11'
		),
		array(
			'id' => 'option-colour',
			'name' => 'option-colour',
			'slug' => 'option-colour',
			'option_count' => 2,
			'created' => '2012-10-05 09:59:11',
			'modified' => '2012-10-05 09:59:11'
		),
	);

}
