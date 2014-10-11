<?php

class Rss extends Eloquent {

	protected $table = 'rss';

	public function newspaper()
    {
        return $this->belongsTo('Newspaper','id_newspaper');
    }

	public function links()
    {
        return $this->hasMany('Link','id_rss');
    }

    public function tag()
    {
        return $this->belongsTo('Tag','id_tag');
    }

}
