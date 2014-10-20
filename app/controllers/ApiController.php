<?php

class ApiController extends BaseController {

	public function getNewspapers(){
		return Response::json(Newspaper::select('id','name','logo')->orderBy('name', 'ASC')->get());
	}

	public function getTags(){
		return Response::json(Tag::select('id','name','color')->orderBy('name', 'ASC')->get());
	}

	public function getTopNews(){
		$hs 			= (Input::get('hs','')!='')?Input::get('hs'):'3';
		$hs 			= (in_array($hs, [3,6,12,24])?$hs:'3');
		$newspaper_id 	= (Input::get('newspaper','')!='')?Input::get('newspaper'):false;
		$tag_id 		= (Input::get('tag','')!='')?Input::get('tag'):false;

		//Time
		$filterDate = new DateTime('now');
		$filterDate->sub(new DateInterval('PT'.$hs.'H'));

		$query = Link::with('newspaper')->with('tag')
            ->join('stats', 'link.id', '=', 'stats.id_link')
            ->select('*',DB::raw('sum(stats.dif_total) as diff'))
            ->orderBy('diff','DESC')
            ->where('stats.created_at','>',$filterDate)
            ->groupBy('link.id');
		
		//Newspaper
		if($newspaper_id){
			$query->where('link.id_newspaper', $newspaper_id);
		}

		//tag
		if($tag_id){
			$query->where('link.id_tag', $tag_id);
		}

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
