<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SharesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get-shares';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieve shares counts for links';

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
		try{
			
			$log = new Process();
			$log->name = "get-shares";
			$log->status = "running";
			$log->save();

			$filterDate = new DateTime('now');
			$filterDate->sub(new DateInterval('P1D'));

			//Load shares
			Link::where('date','>',$filterDate)->chunk(100, function($links)
			{
				foreach ($links as $value) {
					$shares = $this->getSharesCount($value);
					
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
			
			$log->status = "finished";
			$log->save();
		
		} catch (Exception $e) {
			$this->info($e->getMessage());			
		}

	}

	private function getSharesCount($link){
		$r = array(
			'facebook' 		=> $this->getSharesFacebook($link->final_url),
			'twitter' 		=> $this->getSharesTwitter($link->id),
			'linkedin' 		=> 0,//$this->getSharesLinkedin($url),
			'googleplus' 	=> 0//$this->getSharesGooglePlus($url)
			);

		return $r;
	}

	/*count*/
	private function getSharesTwitter($id_link){
		$res = null;
		try {
		/*	$string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$url);
			$json = json_decode($string);
			$res = $json->count;*/
			$tw_share = TwShares::where('id_link',$id_link)->first();
			if($tw_share){
				$res = $tw_share->counts;
			} else {
				$res = 0;
			}
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
