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
		'name' => array(
			'title' => 'Name'
		),
		'status' => array(
			'title' => 'Status'
		),
		'minutes' => array(
			'title' => 'Minutes'
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
		),
		'name' => array(
			'title' => 'Nombre',
			'type' => 'text'
		)

	)

);