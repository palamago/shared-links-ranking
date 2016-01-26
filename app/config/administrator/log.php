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
		'id_group' => array(
			'title' => 'Grupo'
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
		),
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		)

	),

	'filters' => array(
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'name' => array(
		    'type' => 'enum',
		    'title' => 'Process',
		    'options' => array(
				'get-links' => 'Links',
		        'get-shares' => 'Shares',
		        'make-history' => 'History',
		        'get-tw-shares' => 'Tw Shares'
		    ), //must be an array
		)

	)

);