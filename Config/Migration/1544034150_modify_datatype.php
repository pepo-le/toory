<?php
class ModifyDatatype extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'modify_datatype';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'histories' => array(
					'done_todo' => array('type' => 'smallinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
					'done_routine' => array('type' => 'smallinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
					'total_routine' => array('type' => 'smallinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
				),
				'lockouts' => array(
					'count' => array('type' => 'tinyinteger', 'null' => false, 'default' => null, 'length' => 2, 'unsigned' => false),
				),
				'tasks' => array(
					'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'histories' => array(
					'done_todo' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'done_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'total_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
				),
				'lockouts' => array(
					'count' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 2, 'unsigned' => false),
				),
				'tasks' => array(
					'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
