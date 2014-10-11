<?php

class Link extends Eloquent {

	protected $table = 'link';

	public function rss()
    {
        return $this->belongsTo('Rss','id_rss');
    }

	public function newspaper()
    {
        return $this->belongsTo('Newspaper','id_newspaper');
    }

	public function tag()
    {
        return $this->belongsTo('Tag','id_tag');
    }

	/*public function stats()
    {
        return $this->hasMany('Stats','id_link');
    }*/

    public function totalCount()
    {
        if($this->total){
            return $this->total;
        }else{
            return $this->twitter+$this->facebook+$this->googleplus+$this->linkedin;
        }
    }


}
