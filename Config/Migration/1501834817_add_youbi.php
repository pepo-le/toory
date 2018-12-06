<?php
class AddYoubi extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_youbi';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tasks' => array(
					'day0' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'sunday', 'after' => 'start'),
					'day1' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'monday', 'after' => 'day0'),
					'day2' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'tuesday', 'after' => 'day1'),
					'day3' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'wednesday', 'after' => 'day2'),
					'day4' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'thursday', 'after' => 'day3'),
					'day5' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'friday', 'after' => 'day4'),
					'day6' => array('type' => 'boolean', 'null' => false, 'default' => null, 'comment' => 'saturday', 'after' => 'day5'),
					'lastdone' => array('type' => 'date', 'null' => true, 'default' => null, 'after' => 'status'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'tasks' => array('day0', 'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'lastdone'),
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
