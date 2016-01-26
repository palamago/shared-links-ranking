<?php

class TopController extends BaseController {

	public function index($group=false)
	{
		if($group){
			return View::make('site/top/index', array('group' => $group));
		}else{
			return View::make('site/top/home', array('groups' => Group::all()));
		}
	}

}
