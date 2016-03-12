<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TweetCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tweet-top';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tweet top stories, once a hour';

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
		$log->name = "tweet-top";
		$log->status = "running";
		$log->save();

		$groups = Group::all();

		foreach ($groups as $key => $g) {

			$top = $this->getTopLink($g);

			//Update icons
			$this->updateAvatars($g);

			if($top){

				$imgs = array();
				$params = array(
					'consumer_key'        	=> $g->tw_user_key,
					'consumer_secret'     	=> $g->tw_user_secret ,
					'token'        			=> $g->tw_user_token ,
					'secret' 				=> $g->tw_user_token_secret 
				);
				Twitter::reconfig($params);

				//News Image
				$url = $top->image;
				if($url){
					$ext = pathinfo($url, PATHINFO_EXTENSION);
					$img = public_path('temp.'.$ext);
					file_put_contents($img, file_get_contents($url));
					$media = File::get(public_path('temp.'.$ext));
					$uploaded_media_logo = Twitter::uploadMedia(['media' => $media]);
					$imgs[] = $uploaded_media_logo->media_id_string;					
				}

				//Image logo
				$url = $top->newspaper->logo;
				$ext = pathinfo($url, PATHINFO_EXTENSION);
				$img = public_path('temp.'.$ext);
				file_put_contents($img, file_get_contents($url));
				$media = File::get(public_path('temp.'.$ext));
				$uploaded_media_news = Twitter::uploadMedia(['media' => $media]);
				$imgs[] = $uploaded_media_news->media_id_string;

				$link = $_ENV['url'] . '/' . $g->slug. '/#/link/' . $top->id;

				//Send
				Twitter::postTweet(['status' => mb_strimwidth($top->title, 0, 90, "...") .' '. $link, 'format' => 'json', 'media_ids' => $imgs]);
			}

		}

		$log->status = "finished";
		$log->save();
		
	}

	private function updateAvatars($grupo){

		$params = array(
			'consumer_key'        	=> $grupo->tw_user_key,
			'consumer_secret'     	=> $grupo->tw_user_secret ,
			'token'        			=> $grupo->tw_user_token ,
			'secret' 				=> $grupo->tw_user_token_secret 
		);
		Twitter::reconfig($params);

		foreach ($grupo->newspapers as $n) {
			if($n->twitter){
				$data = Twitter::getUserTimeline(['screen_name' => $n->twitter, 'count' => 1, 'format' => 'json']);
				$data = json_decode($data);
				$n->logo = $data[0]->user->profile_image_url;
				$n->save();
			}
		}

	}

	private function getTopLink($group){
		
		$filterDate = new DateTime('now');
		$filterDate->sub(new DateInterval('PT1H'));

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
            ->where('id_group',$group->slug)
            ->groupBy('link.id');

        return $query->first();
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
