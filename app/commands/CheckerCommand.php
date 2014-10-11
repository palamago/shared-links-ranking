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

		//Load
		Link::where('date','>',$filterDate)->chunk(100, function($links)
		{
			foreach ($links as $value) {
				$shares = $this->getSharesCount($value->url);
				
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
		$this->info('.');
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
			$res = $json[0]->share_count;
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
		$feed = FeedReader::read($rss->url);
		$data = array();
		foreach ($feed->get_items() as $key => $value) {

			//Fallback url mala de Clarin
			$url = $value->get_id();
			$url = (filter_var($url, FILTER_VALIDATE_URL) === FALSE)?$value->get_permalink():$url;

			$this->info($url);

			$link = Link::where('url', $url )->get()->first();

			if ( is_null($link) ){
				$date = $value->get_date('Y-m-d H:i:s');
				
				//Fallback Diario Perfil
				if(strpos($date, '1969')==0){
					$date = $value->get_date('');
					$date = explode(' +', $date);
					$date = date_create_from_format('d m Y H:i:s', $date[0].'0');
					if($date){
						$date = $date->format('Y-m-d H:i:s');
					} else {
						$date = new DateTime();
					}
				}

				$data[] = array(
					'url' => $url,
					'title' => $value->get_title(),
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

		if(count($data)){
			Link::insert($data);
		}

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
