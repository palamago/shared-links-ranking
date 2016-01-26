<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LinksCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get-links';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieve links from RSS';

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

		try {

			$group = $this->argument('group');

			$log = new Process();
			$log->name = "get-links";
			$log->status = "running";
			$log->id_group = $group;
			$log->save();

			//Check new links
			Rss::whereHas('newspaper', function($q) use ($group)
            {
                $q->where('id_group', $group);

            })->chunk(100, function($rss)
			{
				foreach ($rss as $value) {
					$this->loadRss($value);
					$value->touch();
				}
			});

			//Remove old links
			$filterDate = new DateTime('now');
			$filterDate->sub(new DateInterval('P1D'));

			Link::where('id_group',$group)->where('date','<',$filterDate)->where('updated_at','<',$filterDate)->delete();

			$log->status = "finished";
			$log->save();

		} catch (Exception $e) {
			$this->error($e->getMessage());
			$this->error($e->getTraceAsString());	
		}
	}

	private function loadRss($rss){
		try{
			
			$feed = FeedReader::read($rss->url);
			$data = array();

			foreach ($feed->get_items() as $key => $value) {

				//Fallback bad url
				$url = $value->get_permalink();
				$url = (filter_var($url, FILTER_VALIDATE_URL) === FALSE)?$value->get_id():$url;
				$url = (filter_var($url, FILTER_VALIDATE_URL) === FALSE)?$value->get_link():$url;

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
						'id_group' => $rss->newspaper->id_group,
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

			$this->error($rss->url);
			$this->error($e->getTraceAsString());

		}

		if(count($data)){
			Link::insert($data);
			//Trigger db on each insert add url on tw_share table
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
				if($target == null || strpos($target, 'login')>-1 || strpos($target, 'detection')>-1){
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
			array('group', InputArgument::REQUIRED, 'Grupo para filtrar')
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
