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
		'slug' => array(
			'title' => 'Slug'
		),
		'name' => array(
			'title' => 'Name'
		),
		'logo' => array(
			'title' => 'Logo',
			'output' => '<img src="(:value)" height="50" />',
		)
	),

	'filters' => array(
	    'name' => array(
	        'title' => 'Name',
	    )
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'slug' => array(
			'title' => 'Slug',
			'type' => 'text'
		),
		'name' => array(
			'title' => 'Name',
			'type' => 'text'
		),
		'logo' => array(
	        'title' => 'Logo (cuadrado)',
			'type' => 'text'
		),
		'tw_key' => array(
			'title' => 'Twitter App Key',
			'type' => 'text'
		),
		'tw_secret' => array(
			'title' => 'Twitter App Secret',
			'type' => 'text'
		),
		'tw_user_key' => array(
			'title' => 'Twitter User Key',
			'type' => 'text'
		),
		'tw_user_secret' => array(
			'title' => 'Twitter User Secret',
			'type' => 'text'
		),
		'tw_user_token' => array(
			'title' => 'Twitter User Token',
			'type' => 'text'
		),
		'tw_user_token_secret' => array(
			'title' => 'Twitter User Token Secret',
			'type' => 'text'
		)
	)

);