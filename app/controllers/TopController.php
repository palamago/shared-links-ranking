<?php

class TopController extends BaseController {

	public function getTop($filter='today')
	{

/*$list = FeedReader::read('http://www.clarin.com/rss/deportes/');

foreach ($list->get_items() as $key => $value) {
	$link = Link::where('url', $value->get_permalink())->get()->first();
	$url = $value->get_id();
	$url = (filter_var($url, FILTER_VALIDATE_URL) === FALSE)?$value->get_permalink():$url;
	
	if($link){
		$link['url'] = $url;
		$link->save();
	}

}
die();*/

/*
$date = $list->get_items()[0]->get_date('')

$list = FeedReader::read('http://www.perfil.com/rss/politica.xml');
var_dump( $list->get_items()[0]->get_date('') );
*/

/*		$title = 'Noticias más compartidas';
		$subtitle = $this->completeSubtitle('',$filter);
		$links = $this->completeQuery(new Link(),$filter);
		return $this->completeResponse($links, $title, $subtitle, $filter);*/

		return View::make('site/top/index');
	}


	public function getTopByNewspaper($id,$filter='today')
	{
		$id = is_numeric($id)?$id:0;
		$newspaper = Newspaper::find($id);
		if($newspaper){
			$title = 'Noticias más compartidas en '.$newspaper->name;
			$subtitle = $this->completeSubtitle('',$filter);
			$links = $this->completeQuery(Link::where('id_newspaper', $id),$filter);
			return $this->completeResponse($links, $title, $subtitle, $filter);
		} else {
			App::abort(404);
		}
	}

	public function getTopByTag($id,$filter='today')
	{
		$id = is_numeric($id)?$id:0;
		$tag = Tag::find($id);
		if($tag){
			$title = 'Noticias más compartidas en '.$tag->name;
			$subtitle = $this->completeSubtitle('',$filter);
			$links = $this->completeQuery(Link::where('id_tag', $id),$filter);
			return $this->completeResponse($links, $title, $subtitle, $filter);
		} else {
			App::abort(404);
		}
	}

	private function completeQuery($queryObj,$filter){
		$filterDate = new DateTime('today');
		switch ($filter) {
			case 'today':
				$queryObj = $queryObj->where('date','>',$filterDate);
				break;
			case '3days':
				$filterDate->sub(new DateInterval('P3D'));
				$queryObj = $queryObj->where('date','>',$filterDate);
				break;
			case 'week':
				$filterDate->sub(new DateInterval('P1W'));
				$queryObj = $queryObj->where('date','>',$filterDate);
				break;
			default:
				$queryObj = $queryObj->where('date','>',$filterDate);
				break;	
		}
		return $queryObj->orderBy('total', 'DESC')->take(5)->get();
	}

	private function completeSubtitle($subtitle, $filter){
		switch ($filter) {
			case 'today':
				$subtitle .= 'de hoy';
				break;
			case '3days':
				$subtitle .= 'de los últimos 3 días';
				break;
			case 'week':
				$subtitle .= 'de la última semana';
				break;
			default:
				$subtitle .= 'de hoy';
				break;	
		}
		return $subtitle;
	}

	private function completeResponse($links, $title, $subtitle, $filter){
		$tags = Tag::orderby('name','ASC')->get();
		$newspaper = Newspaper::orderby('name','ASC')->get();

		return View::make('site/top/index', compact('links','title','subtitle','filter','tags','newspaper'));
	}

}
