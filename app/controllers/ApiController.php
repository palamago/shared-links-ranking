<?php

class ApiController extends BaseController {

	public function getNewspapers(){
		return Response::json(Newspaper::select('id','name','logo')->get());
	}

	public function getTags(){
		return Response::json(Tag::select('id','name','color')->get());
	}

	public function getTopNews(){
		$query = Link::with('newspaper')->with('tag');
		//$time 			= (Input::get('time','')!='')?Input::get('time'):'today';
		$newspaper_id 	= (Input::get('newspaper','')!='')?Input::get('newspaper'):false;
		$tag_id 		= (Input::get('tag','')!='')?Input::get('tag'):false;

		//Time
		$filterDate = new DateTime('now');
		$filterDate->sub(new DateInterval('P1D'));
		$query->where('date','>',$filterDate);

		//Newspaper
		if($newspaper_id){
			$query->where('id_newspaper', $newspaper_id);
		}

		//tag
		if($tag_id){
			$query->where('id_tag', $tag_id);
		}

		//order
		$query->orderBy('total', 'DESC');
		
		//relations
		$query->with('newspaper');

		return Response::json($query->take(5)->get());
	}

	public function getSparklinesData($ids)
	{
		$title = 'TOP 5 shared news';
		$ids = explode(',',$ids);
		if(count($ids)>5){
			$ids = array_slice($ids, 0, 5);
		}

		$response = array();

		foreach ($ids as $key => $id) {
			$response[$id] = Stats::where('id_link',$id)->orderBy('total', 'ASC')->lists('total');
		}

		return Response::json($response);
	}

}
