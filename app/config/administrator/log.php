<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Log',

	'single' => 'log',

	'model' => 'Process',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'status' => array(
			'title' => 'Status'
		),
		'created_at' => array(
			'title' => 'Creado'
		),
		'updated_at' => array(
			'title' => 'Modificado'
		)
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'status' => array(
			'title' => 'Status',
			'type' => 'text'
		)

	)

);