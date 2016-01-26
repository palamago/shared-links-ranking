<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Tag',

	'single' => 'tag',

	'model' => 'Tag',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'name' => array(
			'title' => 'Tag'
		),
		'id_group' => array(
			'title' => 'Grupo'
		),
		'color' => array(
		    'type' => 'color',
		    'title' => 'Color',
		    'output' => '<span style="background-color:(:value);width:100%;height:100%;" href="javascript:;">(:value)<span/>'
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
		'color' => array(
		    'type' => 'color',
		    'title' => 'Color',
		),
		'name' => array(
			'title' => 'Tag',
			'type' => 'text'
		),
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
		),

	)

);