<?php

/**
 * Actors model config
 */

return array(

	'title' => 'History',

	'single' => 'History',

	'model' => 'History',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'date' => array(
			'title' => 'Fecha'
		),
		'title' => array(
			'title' => 'Titulo'
		),
		'final_url' => array(
			'title' => 'FinalUrl',
			'output' => '<a href="(:value)" target="_blank">Link</a>'
		),
		'id_newspaper' => array(
			'title' => 'Newspaper',
			'relationship' => 'newspaper',
			'select' => '(:table).name',
		),
		'id_tag' => array(
			'title' => 'Tag',
			'relationship' => 'tag',
			'select' => '(:table).name',
		),		
		'image' => array(
			'title' => 'Img',
			'output' => '<img src="(:value)" width="50" />'
		),
		'created_at' => array(
			'title' => 'Creado'
		),
		'updated_at' => array(
			'title' => 'Modificado'
		),
		'total_day' => array(
			'title' => 'Total Day'
		)

	),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'id',
		'title' => array(
			'title' => 'Titulo',
			'type' => 'text'
		),
		'url' => array(
			'title' => 'Url',
			'type' => 'text'
		),
		'date' => array(
			'title' => 'Fecha',
			'type' => 'datetime'
		),
		'tag' => array(
			'title' => 'Tag',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'newspaper' => array(
			'title' => 'Newspaper',
			'type' => 'relationship',
			'name_field' => 'name'
		)

	),

	'filters' => array(
	    'date' => array(
	        'date' => 'Date',
			'type' => 'date'
	    ),
		'tag' => array(
			'title' => 'Tag',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'newspaper' => array(
			'title' => 'Newspaper',
			'type' => 'relationship',
			'name_field' => 'name'
		)
	),


);