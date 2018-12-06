<?php
class AddTwitterFlag extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_twitter_flag';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'users' => array(
					'twitter_register' => array('type' => 'boolean', 'null' => false, 'default' => null, 'after' => 'email_activation'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('twitter_register'),
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
