<?php

/**
 * Actors model config
 */

return array(

	'title' => 'TwShares',

	'single' => 'share',

	'model' => 'TwShares',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'id_link' => array(
			'title' => 'Id Link'
		),
		'id_group' => array(
			'title' => 'Grupo'
		),
		'link' => array(
			'title' => 'Link'
		),
		'counts' => array(
			'title' => 'Counts'
		),
		'max_id' => array(
			'title' => 'Max Id'
		),
		'created_at' => array(
			'title' => 'Creado'
		),
		'updated_at' => array(
			'title' => 'Modificado'
		)
	),

	'filters' => array(
	   	'id_link' => array(
	        'title' => 'Id Link',
	    ),
	    'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),
	    'link' => array(
	        'title' => 'Link',
	    )
	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'link' => array(
			'title' => 'Link',
			'type' => 'text'
		),
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),

	)

);