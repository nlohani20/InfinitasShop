<?php
/* ShopBranchesSpotlight Fixture generated on: 2010-08-17 14:08:49 : 1282055209 */
class ShopBranchesSpotlightFixture extends CakeTestFixture {
	public $name = 'ShopBranchesSpotlight';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'branch_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'spotlight_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 6,
			'branch_id' => 2,
			'spotlight_id' => 3
		),
		array(
			'id' => 7,
			'branch_id' => 3,
			'spotlight_id' => 3
		),
	);
}
?>