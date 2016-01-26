<?php

class ApiController extends BaseController {

	public function getRss(){
		return Response::json(Rss::select('id','id_newspaper','id_tag','url')->with('newspaper')->with('tag')->get());
	}

	public function getNewspapers(){
		$group = (Input::get('group','')!='')?Input::get('group'):false;
		$q = Newspaper::select('id','name','logo','id_group')->orderBy('name', 'ASC');
		if($group){
			$q->where('id_group',$group);
		}
		return Response::json($q->get());
	}

	public function getTags(){
		$q = Tag::select('id','name','color')->orderBy('name', 'ASC');
		return Response::json($q->get());
	}

	public function getGroups(){
		return Response::json(Group::select('slug','name','logo')->orderBy('name', 'ASC')->get());
	}

	public function getTopNews(){
		$group 			= (Input::get('group','')!='')?Input::get('group'):false;
		$hs 			= (Input::get('hs','')!='')?Input::get('hs'):'1';
		$hs 			= (in_array($hs, [1,3,6,12,24])?$hs:'1');
		$newspaper_id 	= (Input::get('newspaper','')!='')?Input::get('newspaper'):false;
		$tag_id 		= (Input::get('tag','')!='')?Input::get('tag'):false;

		if(!$group){
			Response::json([]);
		}

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
            	,'link.id_group as id_group'
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
            ->where('link.id_group',$group)
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

	public function getChartsData($id)
	{
		$response = array();

		$data = Stats::select(array('id_link','facebook','twitter','total','dif_total','created_at'))->where('id_link',$id)->get();

		if (count($data)>1) {
			$gap = $data[1]->created_at->timestamp - $data[0]->created_at->timestamp;
			$response[] = array(
				'id' => 0,
				'date' => $data[0]->created_at->timestamp - $gap,
				'name' => 'Facebook',
				'value' => 0
				);

			$response[] = array(
				'id' => 0,
				'date' => $data[0]->created_at->timestamp - $gap,
				'name' => 'Twitter',
				'value' => 0
				);

			$response[] = array(
				'id' => 0,
				'date' => $data[0]->created_at->timestamp - $gap,
				'name' => 'Acumulado',
				'value' => 0
				);

			$response[] = array(
				'id' => 0,
				'date' => $data[0]->created_at->timestamp - $gap,
				'name' => 'Parcial',
				'value' => 0
				);
		}

		foreach ($data as $key => $s) {
			$response[] = array(
				'id' => $s->id_link,
				'date' => $s->created_at->timestamp,
				'name' => 'Facebook',
				'value' => (int)$s->facebook
				);

			$response[] = array(
				'id' => $s->id_link,
				'date' => $s->created_at->timestamp,
				'name' => 'Twitter',
				'value' => (int)$s->twitter
				);

			$response[] = array(
				'id' => $s->id_link,
				'date' => $s->created_at->timestamp,
				'name' => 'Acumulado',
				'value' => (int)$s->total
				);

			$response[] = array(
				'id' => $s->id_link,
				'date' => $s->created_at->timestamp,
				'name' => 'Parcial',
				'value' => (int)$s->dif_total
				);
		}

		return Response::json(array('data'=>$response));
	}

	public function getLinkData($id)
	{
		$response = Link::with('newspaper')->with('tag')->with('stats')->find($id);

		return Response::json($response);
	}

	public function getHistoryNews(){
		$newspaper_id 	= (Input::get('newspaper','')!='')?Input::get('newspaper'):false;
		$tag_id 		= (Input::get('tag','')!='')?Input::get('tag'):false;

		$query = History::with('newspaper')->with('tag')
			->select(DB::raw('MAX(total_day) as total_day')
				,DB::raw('DATE(date) as sdate')
				,'history.id as id'
            	,'history.url as url'
            	,'history.id_newspaper as id_newspaper'
            	,'history.id_tag as id_tag'
            	,'history.final_url as final_url'
            	,'history.title as title'
            	,'history.date as date'
            	,'history.facebook as facebook'
            	,'history.twitter as twitter'
            	,'history.total as total'
            	,'history.googleplus as googleplus'
            	,'history.linkedin as linkedin'
            	,'history.image as image'
            	)
            ->groupBy('sdate')		
            ->orderBy('date','DESC');

		return Response::json($query->get());
	}

}
