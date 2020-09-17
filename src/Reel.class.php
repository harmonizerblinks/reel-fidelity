<?php

class Reel{

	private $playlist;
	private $slides = array();
	private $inflatedSlides;
	private $inflatedSounds;
	private $inflatedTOC;
	private $sounds = array();
	private $options = array();
	private $styles = array();
	private $toc;


	public function __construct(){
		$this->_log();
	}

	private function _log(){
		file_put_contents(
			dirname(__DIR__).'/log/log.txt'
			, sprintf("Time: %s \n\rIP: %s \n\rUser Agent: %s \n\rURL: %s \n\r\n\r"
				, date('Y-m-d H:m:s', time())
				, $_SERVER['REMOTE_ADDR']
				, $_SERVER['HTTP_USER_AGENT']
				, $_SERVER['REQUEST_URI'] )
			, FILE_APPEND
			);
	}

	public function setPlaylist($playlist){
		$this->playlist = $playlist;
		$this->build();
	}

	private function build(){

		if(empty($this->playlist)){
			throw new Exception('Trying to build slides without setting a playlist is illegal.');
		}

		$this->playlist = preg_replace("/\r\n/", '', $this->playlist);
		if(!Utils::isJson($this->playlist)){
			throw new Exception("Playlist is not a valid json. Check out <a href='http://www.jsonlint.com' target='_blank'>www.jsonlint.com</a> to validate your json.");
		}

		$pl = json_decode($this->playlist);
		$this->slides = (!empty($pl->slides)) ? $pl->slides : null;
		$this->sounds = (!empty($pl->sounds)) ? $pl->sounds : null;
		$this->options = (!empty($pl->options)) ? $pl->options : null;
		$this->styles = (!empty($pl->styles)) ? $pl->styles : null;
	}

	public function getOptions(){
		return $this->options;
	}

	public function renderOptions(){
		$opts = '';
		$opts .= 'var RATES_API_REFRESH_INTERVAL = ' . AppConfig::RATES_API_REFRESH_INTERVAL . ";\n\r\t";
		$opts .= 'var ASSETS_SALT = ' . AppConfig::ASSETS_SALT.";\n\r\t";
		$opts .= 'var DISABLE_INTERACTION = ' .  ((!empty($this->options->disable_interaction)) ? 'true' : 'false') . ";\n\r\t";
		$opts .= 'var SHOW_RATES = ' .  ((!empty($this->options->show_rates)) ? 'true' : 'false'). ";\n\r\t";
		$opts .= 'var SHOW_SOCIAL_NETWORK_FEEDS = ' . $this->options->show_social_network_feeds.";\n\r\t";
		echo $opts;
	}

	public function renderSlides(){
		$this->inflateSlides();
		return $this->inflatedSlides;

	}

	public function renderSounds(){
		if(!empty($this->options->disable_sound)){
			return;
		}
		$this->inflateSounds();
		return $this->inflatedSounds;
	}


	private function inflateSlides(){

		if(empty($this->slides)){
			return;
		}

		$str = '';

		foreach($this->slides as $key => $slide){

			if(empty($slide->url) && empty($slide->content)){
				continue;
			}

			$class = '';
			$id = '';
			$data_slide_content = '';
			$init_script = '';
			$slide_content = '';
			// $timer='';

			if($key == 0){
				$class = 'class="first-slide"';
				$init_script = sprintf('<script type="text/javascript">showSlideContent($(".first-slide"), "%s?assets_salt=%s")</script>', $slide->url, AppConfig::ASSETS_SALT );
			}
			if(!empty($slide->url)){
				$data_slide_content = sprintf('data-slide-content = "%s?assets_salt=%s"', $slide->url, AppConfig::ASSETS_SALT);
			}elseif(!empty($slide->content)){
				$slide_content = $slide->content;
			}

			$id = (!empty($slide->id)) ? 'data-slide-id="'.$slide->id.'"' : '';

			// $timer = (!empty($slide->timer)) ? 'data-slide-timer="'.$slide->timer.'"' : '';

			$str .= sprintf("\n\r\t\t\t<div %s %s %s>%s%s</div> <!-- %d -->", $class, $data_slide_content, $id,  $slide_content, $init_script, $key);

		}
		$this->inflatedSlides = $str;

	}
	private function inflateSounds(){

		if(empty($this->sounds)){
			return;
		}

		$str = '';

		$main = $alt = array();

		foreach ($this->sounds as $key => $sound) {
			$main[] = $sound->main;
			$alt[] = $sound->alt;

		}

		$str = sprintf('var sound_playlist = %s;', json_encode($main));
		$str .= sprintf('var sound_playlist_alt = %s;', json_encode($alt));

		$this->inflatedSounds = $str;

	}
	public function inflateTOC(){
		//Build TOC
		$this->inflatedTOC = $this->buildMenu($this->slides);

		return true;
	}
	public function renderTOC(){
		$this->inflateTOC();
		echo $this->inflatedTOC;
	}

	public function renderStyles(){
		if(!empty($this->styles->content)){
			printf('<style>%s</style>', $this->styles->content);
		}
		if(!empty($this->styles->url)){
			printf('<link href="%s" rel="stylesheet" />', $this->styles->url);
		}
	}

	private function buildMenu($slides){
		$menu = '';
		$row_limit = 4;

		# remove slides with no title
		foreach ($slides as $key => $slide) {
			if(!$slide->title){
				unset($slides[$key]);
			}
		}

		# chunk the slides into groups
		$slide_chunks = array_chunk($slides, $row_limit, true);
		unset($slides);

		foreach($slide_chunks as $slides){
			$menu .= '<div class="col-md-3 col-sm-3">
			<ul> ';
			foreach ($slides as $key => $slide) {
				if(!$slide->title){
					continue;
				}
				$menu .= sprintf('<li><a href="javascript:void()" data-slide-index="%s" data-slide-id="%s" >%s</a></li>', $key, $slide->id, $slide->title );
			}
			$menu .= '	</ul>
			</div>';

		}
		return $menu;

	}

}

?>
