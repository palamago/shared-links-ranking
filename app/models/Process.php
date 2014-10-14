<?php

class Process extends Eloquent {

	protected $table = 'log';

	public function getMinutesAttribute()
	{
		$to_time = strtotime($this->updated_at);
		$from_time = strtotime($this->created_at);
		return round(abs($to_time - $from_time) / 60,2). " min.";
	}

}
