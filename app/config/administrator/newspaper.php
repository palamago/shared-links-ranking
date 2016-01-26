<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Medio',

	'single' => 'medio',

	'model' => 'Newspaper',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'logo' => array(
			'title' => 'Logo',
			'output' => '<img src="(:value)" height="50" />',
		),
		'name' => array(
			'title' => 'Nombre'
		),
		'id_group' => array(
			'title' => 'Grupo'
		),
		'url' => array(
			'title' => 'Url'
		),
		'created_at' => array(
			'title' => 'Creado'
		),
		'updated_at' => array(
			'title' => 'Modificado'
		)
	),

	'filters' => array(
	    'id',
	    'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),
	    'name' => array(
	        'title' => 'Name',
	    )
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'name' => array(
			'title' => 'Nombre',
			'type' => 'text'
		),
		'logo' => array(
	        'title' => 'Logo (cuadrado)',
			'type' => 'text'
		),
		'url' => array(
			'title' => 'Url',
			'type' => 'text'
		),
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),

	)

);