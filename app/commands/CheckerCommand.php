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
				
				$stat = new Stats();
				$stat->id_link 		= $value->id;
				$stat->facebook 	= ($shares['facebook']!=null)?$shares['facebook']:$value->facebook;
				$stat->twitter 		= ($shares['twitter']!=null)?$shares['twitter']:$value->twitter;
				$stat->linkedin 	= ($shares['linkedin']!=null)?$shares['linkedin']:$value->linkedin;
				$stat->googleplus 	= ($shares['googleplus']!=null)?$shares['googleplus']:$value->googleplus;
				$stat->total 		= $stat->facebook + $stat->twitter + $stat->linkedin + $stat->googleplus;
				$stat->save();

				$value->facebook 	= $stat->facebook;
				$value->twitter 	= $stat->twitter;
				$value->linkedin 	= $stat->linkedin;
				$value->googleplus 	= $stat->googleplus;
				$value->total 		= $stat->total;
				$value->save();

			}
		});

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
			$res = $json[0]->share_count + $json[0]->like_count;
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
			$res = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
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

				//REMOVE THIS!
	//			$url = (strpos($url,"www.canchallena.com")>-1)?str_replace("www.canchallena.com", "canchallena.lanacion.com.ar", $url):$url;

				$link = Link::where('url', $url )->get()->first();

				if ( is_null($link) ){

					$this->info($url);

					$final_url = $this->getFinalURL($url);

					$date = $value->get_date('Y-m-d H:i:s');

					/*$this->info($value->get_date(''));
					$this->info($value->get_date('Y-m-d H:i:s'));*/

					//Fallback bad dates
					if(strpos($date, '1969')==0){
						$date = new DateTime();
					}

					$data[] = array(
						'url' => $url,
						'final_url' => $final_url,
						'title' => html_entity_decode($value->get_title()),
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
				}

			}

		} catch (Exception $e) {
			$this->info($rss->url);
			$this->info($e->getMessage());
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
	    $out = curl_exec($ch);

	    // line endings is the wonkiest piece of this whole thing
	    $out = str_replace("\r", "", $out);

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
				//$this->info('redirects!');
				if($url == $target || $target == $orig){
					return $target;
				} else {
					return $this->getFinalURL($target,$url);
				}
	            break;
	        }   
	    }

	    return $url;
		
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
