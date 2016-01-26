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
		'id_group' => array(
			'title' => 'Grupo'
		),
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
			'title' => 'Img',
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
		'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
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
	    'grupo' => array(
			'title' => 'Grupo',
			'type' => 'relationship',
			'name_field' => 'name'
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