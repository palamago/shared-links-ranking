<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'data-checker';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update links and counters';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$log = new Process();
		$log->status = "running";
		$log->save();

		$filterDate = new DateTime('now');
		$filterDate->sub(new DateInterval('P1D'));

		//Check new links
		Rss::chunk(100, function($rss)
		{
			foreach ($rss as $value) {
				$this->loadRss($value);
				$value->touch();
			}
		});

		//Load shares
		Link::where('date','>',$filterDate)->chunk(100, function($links)
		{
			foreach ($links as $value) {
				$shares = $this->getSharesCount($value->final_url);
				
				$ref = Stats::where('id_link',$value->id)->orderBy('created_at', 'DESC')->first();

				if(!$ref) {
					$ref = new stdClass();
					$ref->total = 0;
				}

				$stat = new Stats();
				$stat->id_link 		= $value->id;
				$stat->facebook 	= ($shares['facebook']!=null)?$shares['facebook']:$value->facebook;
				$stat->twitter 		= ($shares['twitter']!=null)?$shares['twitter']:$value->twitter;
				$stat->linkedin 	= ($shares['linkedin']!=null)?$shares['linkedin']:$value->linkedin;
				$stat->googleplus 	= ($shares['googleplus']!=null)?$shares['googleplus']:$value->googleplus;
				$stat->total 		= $stat->facebook + $stat->twitter + $stat->linkedin + $stat->googleplus;
				$stat->dif_total	= $stat->total - $ref->total; 
				$stat->save();

				$value->facebook 	= $stat->facebook;
				$value->twitter 	= $stat->twitter;
				$value->linkedin 	= $stat->linkedin;
				$value->googleplus 	= $stat->googleplus;
				$value->total 		= $stat->total;
				$value->save();

			}
		});
		
		//Remove old links
		Link::where('date','<',$filterDate)->where('updated_at','<',$filterDate)->delete();

		$log->status = "finished";
		$log->save();
		
	}

	private function getSharesCount($url){
		$r = array(
			'facebook' 		=> $this->getSharesFacebook($url),
			'twitter' 		=> $this->getSharesTwitter($url),
			'linkedin' 		=> $this->getSharesLinkedin($url),
			'googleplus' 	=> $this->getSharesGooglePlus($url)
			);

		return $r;
	}

	/*count*/
	private function getSharesTwitter($url){
		$res = null;
		try {
			$string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$url);
			$json = json_decode($string);
			$res = $json->count;
		} catch (Exception $e) {
			$this->info($url);
			$this->info($e->getMessage());			
		}
		return $res;
	}

	/*
    "share_count": 30,
    "like_count": 8,
    "comment_count": 17,
    "total_count": 55,
    "click_count": 0,
    "comments_fbid": null,
    "commentsbox_count": 0
	*/
	private function getSharesFacebook($url){
		$res = null;
		try {
			$string = file_get_contents('https://api.facebook.com/method/links.getStats?urls='.$url.'&format=json');
			$json = json_decode($string);
			if(isset($json) && isset($json[0])){
				$res = (int)$json[0]->share_count + (int)$json[0]->like_count;
			}
		} catch (Exception $e) {
			$this->info($url);
			$this->info($e->getMessage());
		}
		return $res;
	}

	/*
	"count": 10738,
  	"fCnt": "10K",
  	"fCntPlusOne": "10K"
	*/
	private function getSharesLinkedin($url){
		$res = null;
		try {
			$string = file_get_contents('http://www.linkedin.com/countserv/count/share?url='.$url.'&format=json');
			$json = json_decode($string);
			$res = $json->count;
			
		} catch (Exception $e) {
			$this->info($url);
			$this->info($e->getMessage());
		}
		return $res;
	}

	private function getSharesGooglePlus($url){
		$res = null;
		try {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			$curl_results = curl_exec ($curl);
			curl_close ($curl);
			$json = json_decode($curl_results, true);
			//if(isset($json[0]) && isset($json[0]['result'])){
				$res = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
			//} else {
			//	$res = null;
			//}
		} catch (Exception $e) {
			$this->info($url);
			$this->info($e->getMessage());
		}
		return $res;
	}

	private function loadRss($rss){
		try{
			
			$feed = FeedReader::read($rss->url);
			$data = array();
			foreach ($feed->get_items() as $key => $value) {

				//Fallback bad url
				$url = $value->get_id();
				$url = (filter_var($url, FILTER_VALIDATE_URL) === FALSE)?$value->get_permalink():$url;

				$link = Link::where('url', $url )->get()->first();

				if ( is_null($link) ){

					$htmlParts = $this->getFinalURL($url);

					$final_url = $htmlParts['final_url'];

					$this->info($final_url);

					if(strpos($final_url, "/")==0){
						$orig = parse_url($url);
						$final_url = $orig['scheme']. '://' . $orig['host'] . $final_url;
						$this->info($final_url);
					}

					//'Y-m-d H:i:s'
					$date = strtotime($value->get_date());
					
					//Fallback bad dates
					if(strpos($date, '1969')==0){
						$date = new DateTime();
					}else{
						$date = date('Y-m-d H:i:s',$date);
					}

					$image = $this->getImage($value,$htmlParts['og_image']);

					$data[] = array(
						'url' => $url,
						'final_url' => $final_url,
						'title' => html_entity_decode($value->get_title()),
						'image' => $image,
						'id_rss' => $rss->id,
						'id_tag' => $rss->id_tag,
						'id_newspaper' => $rss->id_newspaper,
						'date' => $date,
						'facebook' => 0,
						'twitter' => 0,
						'linkedin' => 0,
						'googleplus' => 0,
						'updated_at' => date('Y-m-d H:i:s'),
						'created_at' => date('Y-m-d H:i:s')
						);
				} else {
					$link->touch();
				}

			}

		} catch (Exception $e) {
			$this->info($rss->url);
			$this->info($e->getTraceAsString());

		}

		if(count($data)){
			Link::insert($data);
		}

	}

	private function getFinalURL($url,$orig=null) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    curl_setopt($ch, CURLOPT_URL, $url);
	    $rawHtml = curl_exec($ch);

	    // line endings is the wonkiest piece of this whole thing
	    $out = str_replace("\r", "", $rawHtml);

	    // only look at the headers
	    $headers_end = strpos($out, "\n\n");
	    if( $headers_end !== false ) { 
	        $out = substr($out, 0, $headers_end);
	    }   

	    $headers = explode("\n", $out);
	    foreach($headers as $header) {
	        if( substr($header, 0, 10) == "Location: " ) { 
	            $target = substr($header, 10);
				//$this->info($url." redirects to ".$target."");
				//$this->info( ($target == null) );
				if($target == null){
					return array(
						'final_url'	=>	$url,
						'og_image'	=>	$this->getOgImage($rawHtml)
						);
				}
				if( ($url == $target || $target == $orig) ){
					return array(
						'final_url' =>	$target,
						'og_image'	=>	$this->getOgImage($rawHtml)
						);
				} else {
					return $this->getFinalURL($target,$url);
				}
	            break;
	        }   
	    }

	    return array(
			'final_url'	=>	$url,
			'og_image'	=>	$this->getOgImage($rawHtml)
			);
		
	}

	private function getOgImage($html){
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
	    $ogImg = false;
		foreach( $doc->getElementsByTagName('meta') as $meta ) { 
			if($meta->getAttribute('property') == "og:image"){
				$ogImg = $meta->getAttribute('content');
			}
		}
		return $ogImg;
	}

	private function getImage($item,$ogImage) {
		$image = $ogImage;

		//If no og:image get rss image
		if(!$image){
			if(count($item->get_enclosures())>0 ){
				if (isset($item->get_enclosures()[0]->thumbnails) && count($item->get_enclosures()[0]->thumbnails)>0){
					$image = $item->get_enclosures()[0]->thumbnails[0]; 
				} else if (isset($item->get_enclosures()[0]->link) ) {
					$image = $item->get_enclosures()[0]->link;
				}
			}
		}

		return $image;
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
