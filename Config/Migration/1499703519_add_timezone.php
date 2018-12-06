<?php
class AddTimezone extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_timezone';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'users' => array(
					'timezone' => array('type' => 'string', 'null' => false, 'default' => 'Asia/Tokyo', 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'twtokensec'),
					'changetime' => array('type' => 'time', 'null' => false, 'default' => '00:00:00', 'after' => 'timezone'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('timezone', 'changetime'),
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
