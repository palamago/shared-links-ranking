<?php

class Process extends Eloquent {

	protected $table = 'log';

	public function getMinutesAttribute()
	{
		$to_time = strtotime($this->updated_at);
		$from_time = strtotime($this->created_at);
		$diff = abs($to_time - $from_time);
		if($this->status=='running')
			return '...';
		return ($diff==0 && $this->status=='finished')? "0.01 min." :round( $diff / 60,2). " min.";
	}

}
