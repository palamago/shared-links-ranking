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
			'output' => '<img src="/uploads/logos/resize/(:value)" height="50" />',
		),
		'name' => array(
			'title' => 'Nombre'
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
	        'title' => 'Logo (200 x 200)',
	        'type' => 'image',
	        'naming' => 'random',
	        'location' => 'public/uploads/logos/originals/',
	        'size_limit' => 2,
	        'sizes' => array(
	            array(200, 200, 'crop', 'public/uploads/logos/resize/', 100),
	        )
		),
		'url' => array(
			'title' => 'Url',
			'type' => 'text'
		)

	)

);