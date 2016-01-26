<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class HistoryCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'make-history';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Store top links, once a day';

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

		$group = $this->argument('group');

		$log = new Process();
		$log->name = "make-history";
		$log->status = "running";
		$log->save();

		$today = Carbon::today();

		$topToday = array();

		$newspapers = Newspaper::where('id_group',$group)->select('id')->get();
		$tags = Tag::select('id')->get();

		foreach ($newspapers as $key => $n) {
			$top = $this->getTopLink($today,$n->id,false,$group);
			if($top) {
				$topToday[] = $top;
			}
		}

		foreach ($tags as $key => $t) {
			$top = $this->getTopLink($today,false,$t->id,$group);
			if($top) {
				$topToday[] = $top;
			}
		}

		$topToday = array_unique($topToday);

		//Remove links for today
		History::where('id_group',$group)->where('date','=',$today)->delete();

		//Save history
		foreach ($topToday as $key => $t) {
			$this->info($t->title);
			try{
				$h = new History();
				$h->id_ref = $t->id;
				unset($t->id);
				$h->fill($t->toArray());
				$h->date = $today;
				$h->save();
			} catch(Exception $e) {

			}
		}

		$log->status = "finished";
		$log->save();
		
	}

	private function getTopLink($today, $newspaper_id=false, $tag_id=false, $group_id=false){
		$query = Link::join('stats', 'link.id', '=', 'stats.id_link')
        ->select(DB::raw('sum(stats.dif_total) as total_day')
        	,'link.id as id'
        	,'link.url as url'
        	,'link.id_newspaper as id_newspaper'
        	,'link.id_tag as id_tag'
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
        ->orderBy('total_day','DESC')
        ->where('stats.created_at','>=',$today)
        ->where('stats.created_at','<',$today->tomorrow())
        ->groupBy('link.id');

        //Newspaper
		if($newspaper_id){
			$query->where('link.id_newspaper', $newspaper_id);
		}

		//tag
		if($tag_id){
			$query->where('link.id_tag', $tag_id);
		}

		//tag
		if($group_id){
			$query->where('link.id_group', $group_id);
		}

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
