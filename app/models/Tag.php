<?php

class Tag extends Eloquent {

	protected $table = 'tags';

	public function grupo()
    {
        return $this->belongsTo('Group','id_group');
    }

}
