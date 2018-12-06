<?php
class AddColor extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_color';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tasks' => array(
					'color' => array('type' => 'string', 'null' => false, 'default' => '#000000', 'length' => 7, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'title'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'tasks' => array('color'),
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
