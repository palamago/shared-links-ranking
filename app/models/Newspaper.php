<?php

class Newspaper extends Eloquent {

	protected $table = 'newspaper';


	public function rss()
    {
        return $this->hasMany('Rss','id_newspaper');
    }

}
