<?php

class History extends Eloquent {

	protected $table = 'history';

    protected $guarded = array('date', 'created_at', 'updated_at');

	public function newspaper()
    {
        return $this->belongsTo('Newspaper','id_newspaper');
    }

	public function tag()
    {
        return $this->belongsTo('Tag','id_tag');
    }

}
