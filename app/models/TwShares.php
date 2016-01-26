<?php

class TwShares extends Eloquent {

	protected $table = 'tw_shares';

	public function grupo()
    {
        return $this->belongsTo('Group','id_group');
    }

}
