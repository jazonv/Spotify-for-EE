<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Spotify Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Jason Varga
 * @link		http://pixelfear.com
 */

$plugin_info = array(
	'pi_name'		=> 'Spotify',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Jason Varga',
	'pi_author_url'	=> 'http://pixelfear.com',
	'pi_description'=> 'Spotify',
	'pi_usage'		=> Spotify::usage()
);


class Spotify {

	public $uri, $uri_segments, $uri_type, $uri_id;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance(); 

		// entire uri
		$this->uri = $this->EE->TMPL->fetch_param('uri');

		// uri segments
		$this->uri_segments = explode(":", $this->uri);
		$last_segment = end($this->uri_segments);
		$second_last_segment = prev($this->uri_segments);

		if ($last_segment == "starred")
		{
			$this->uri_id = $second_last_segment;
			$this->uri_type = $last_segment;
		}
		else
		{
			$this->uri_id = $last_segment;
			$this->uri_type = $second_last_segment;
		}
	}

	/**
	 * Play Button Widget
	 */
	public function widget()
	{
		$width = ($this->EE->TMPL->fetch_param('width')) ? $this->EE->TMPL->fetch_param('width') : 250;
		$height = ($this->EE->TMPL->fetch_param('height')) ? $this->EE->TMPL->fetch_param('height') : 330;
		$theme = $this->EE->TMPL->fetch_param('theme');
		$show_art = (bool) preg_match('/1|on|yes|y|true/i', $this->EE->TMPL->fetch_param('show_art'));

		// ensure not above max dimensions
		$width = ($width > 640) ? 640 : $width;
		$height = ($height > 720) ? 720 : $height;

		// only allow 'white' or 'black' themes
		$is_allowed_color = preg_match('/white|black/i', $theme);
		$theme = ($is_allowed_color) ? $theme : FALSE;

		// build parameters
		$theme = ($theme) ? 'theme='.$theme : FALSE;
		$coverart = ($show_art) ? 'view=coverart' : FALSE;
		$params = implode('&', array_filter(array($theme, $coverart)));
		$params = (!empty($params)) ? '&'.$params : FALSE;
		$query = '?uri=' . $this->uri . $params;

		// output widget
		$widget = '<iframe src="https://embed.spotify.com/'.$query.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowtransparency="true"></iframe>';
		return $widget;
	}

	/*
	 * Allow for {exp:spotify:play_button}, since that's the actual name of the widget
	 */
	public function play_button()
	{
		return $this->widget();
	}

	/*
	 * Link
	 */
	public function link()
	{
		$url = "http://open.spotify.com/";
		$username = $this->uri_segments[2];

		if ($this->uri_type == "starred") // user's starred playlist?
		{
			$link = $url . 'user/' . $username . '/starred';
		}
		elseif ($this->uri_type == "playlist") // playlist?
		{
			$link = $url . 'user/' . $username . '/' . $this->uri_type . '/' . $this->uri_id;
		}
		else
		{
			$link = $url . $this->uri_type . '/' . $this->uri_id;
		}

		return $link;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
Outputs a Spotify 'Play Button' widget and/or link.
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.spotify.php */
/* Location: /system/expressionengine/third_party/spotify/pi.spotify.php */