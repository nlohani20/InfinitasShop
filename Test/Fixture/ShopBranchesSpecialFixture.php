<?php
/* ShopBranchesSpecial Fixture generated on: 2010-08-17 14:08:47 : 1282055207 */
class ShopBranchesSpecialFixture extends CakeTestFixture {
	public $name = 'ShopBranchesSpecial';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'branch_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'special_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 4,
			'branch_id' => 3,
			'special_id' => 3
		),
		array(
			'id' => 2,
			'branch_id' => 2,
			'special_id' => 3
		),
	);
}
?>