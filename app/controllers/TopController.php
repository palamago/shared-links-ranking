<?php

class TopController extends BaseController {

	public function getTop($filter='today')
	{
		return View::make('site/top/index');
	}

}
