<?php
class TwiOauth extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'twi_oauth';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'users' => array(
					'username' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'twtoken' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'email'),
					'twtokensec' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'twtoken'),
					'indexes' => array(
						'username' => array('column' => 'username', 'unique' => 1),
					),
				),
			),
			'drop_field' => array(
				'users' => array('username'),
			),
			'alter_field' => array(
				'users' => array(
					'email' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('username', 'twtoken', 'twtokensec', 'indexes' => array('username')),
			),
			'create_field' => array(
				'users' => array(
					'username' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'alter_field' => array(
				'users' => array(
					'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
