<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Usuarios',

	'single' => 'usuario',

	'model' => 'User',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'username' => array(
			'title' => 'Usuario'
		)
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'username' => array(
		    'type' => 'text'
		),
		'password' => array(
			'title' => 'Password',
			'type' => 'password'
		)

	)

);