<?php

class ApiController extends BaseController {

	public function getNewspapers(){
		return Response::json(Newspaper::select('id','name','logo')->orderBy('name', 'ASC')->get());
	}

	public function getTags(){
		return Response::json(Tag::select('id','name','color')->orderBy('name', 'ASC')->get());
	}

	public function getTopNews(){
		$hs 			= (Input::get('hs','')!='')?Input::get('hs'):'1';
		$hs 			= (in_array($hs, [1,3,6,12,24])?$hs:'1');
		$newspaper_id 	= (Input::get('newspaper','')!='')?Input::get('newspaper'):false;
		$tag_id 		= (Input::get('tag','')!='')?Input::get('tag'):false;

		//Time
		$filterDate = new DateTime('now');
		$filterDate->sub(new DateInterval('PT'.$hs.'H'));

		$query = Link::with('newspaper')->with('tag')->with('rss')
            ->join('stats', 'link.id', '=', 'stats.id_link')
            ->select(DB::raw('sum(stats.dif_total) as diff')
            	,'link.id as id'
            	,'link.url as url'
            	,'link.id_newspaper as id_newspaper'
            	,'link.id_tag as id_tag'
            	,'link.id_rss as id_rss'
            	,'link.final_url as final_url'
            	,'link.title as title'
            	,'link.date as date'
            	,'link.facebook as facebook'
            	,'link.twitter as twitter'
            	,'link.total as total'
            	,'link.googleplus as googleplus'
            	,'link.linkedin as linkedin'
            	,'link.image as image'
            	)
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

		return Response::json($query->take(10)->get());
	}

	public function getSparklinesData($ids)
	{
		$ids = explode(',',$ids);
		if(count($ids)>10){
			$ids = array_slice($ids, 0, 10);
		}

		$response = array();

		foreach ($ids as $key => $id) {
			$response[$id]['total'] = Stats::where('id_link',$id)->lists('total');
			$response[$id]['dif_total'] = Stats::where('id_link',$id)->lists('dif_total');
		}

		return Response::json(array('data'=>$response));
	}

	public function getLinkData($id)
	{
		$response = Link::with('newspaper')->with('tag')->with('stats')->find($id);

		return Response::json($response);
	}

}
