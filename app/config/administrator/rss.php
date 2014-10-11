<?php

/**
 * Actors model config
 */

return array(

	'title' => 'RSS',

	'single' => 'RSS',

	'model' => 'Rss',

	/**
	 * The display columns
	 */
	'columns' => array(
		'id',
		'id_newspaper' => array(
			'title' => 'Medio',
			'relationship' => 'newspaper',
			'select' => '(:table).name'
		),
		'url' => array(
			'title' => 'Url'
		),
		'id_tag' => array(
			'title' => 'Category',
			'relationship' => 'tag',
			'select' => '(:table).name'
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
		'tag' => array(
			'title' => 'Categoy',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'newspaper' => array(
			'title' => 'Medio',
			'type' => 'relationship',
			'name_field' => 'name'
		),
		'url' => array(
			'title' => 'Url',
			'type' => 'text'
		)

	)

);