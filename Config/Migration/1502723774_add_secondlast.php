<?php
class AddSecondlast extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_secondlast';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tasks' => array(
					'secondlastdone' => array('type' => 'date', 'null' => true, 'default' => null, 'after' => 'status'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'tasks' => array('secondlastdone'),
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
