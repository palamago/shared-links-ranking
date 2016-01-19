<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Grupo',

	'single' => 'group',

	'model' => 'Group',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'name' => array(
			'title' => 'Name'
		),
		'slug' => array(
			'title' => 'Slug'
		),
		'logo' => array(
			'title' => 'Logo',
			'output' => '<img src="(:value)" height="50" />',
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
			'title' => 'Name',
			'type' => 'text'
		),
		'slug' => array(
			'title' => 'Slug',
			'type' => 'text'
		),
		'logo' => array(
	        'title' => 'Logo (cuadrado)',
			'type' => 'text'
		),
		'tw_key1' => array(
			'title' => 'tw_key1',
			'type' => 'text'
		),
		'tw_secret1' => array(
			'title' => 'tw_secret1',
			'type' => 'text'
		),
		'tw_key2' => array(
			'title' => 'tw_key2',
			'type' => 'text'
		),
		'tw_secret2' => array(
			'title' => 'tw_secret2',
			'type' => 'text'
		),
		'tw_key3' => array(
			'title' => 'tw_key3',
			'type' => 'text'
		),
		'tw_secret3' => array(
			'title' => 'tw_secret3',
			'type' => 'text'
		)
	)

);