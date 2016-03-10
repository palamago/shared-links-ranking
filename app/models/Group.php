<?php

class Group extends Eloquent {

	protected $table = 'group';

	protected $primaryKey = 'slug';

	public function newspapers()
    {
        return $this->hasMany('Newspaper','id_group','slug');
    }

}
