<?php
class AddLastlogin extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_lastlogin';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'users' => array(
					'lastlogin' => array('type' => 'date', 'null' => true, 'default' => null, 'after' => 'register'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('lastlogin'),
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
