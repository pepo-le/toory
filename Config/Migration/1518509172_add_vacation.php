<?php
class AddVacation extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_vacation';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'users' => array(
					'vacation' => array('type' => 'boolean', 'null' => false, 'default' => null, 'after' => 'register'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('vacation'),
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
