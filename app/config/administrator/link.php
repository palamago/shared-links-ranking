<?php

/**
 * Actors model config
 */

return array(

	'title' => 'Links',

	'single' => 'Link',

	'model' => 'Link',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'title' => array(
			'title' => 'Titulo'
		),
		'final_url' => array(
			'title' => 'FinalUrl',
			'output' => '<a href="(:value)" target="_blank">Link</a>'
		),
		'date' => array(
			'title' => 'Fecha'
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
			'title' => 'Rss',
			'output' => '<img src="(:value)" width="50" />'
		),
		'created_at' => array(
			'title' => 'Creado'
		),
		'updated_at' => array(
			'title' => 'Modificado'
		),
		'facebook' => array(
			'title' => 'FB'
		),
		'twitter' => array(
			'title' => 'TW'
		),
		'linkedin' => array(
			'title' => 'LN'
		),
		'googleplus' => array(
			'title' => 'G+'
		),
		'total' => array(
			'title' => 'Total'
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
	    'title' => array(
	        'title' => 'Título',
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