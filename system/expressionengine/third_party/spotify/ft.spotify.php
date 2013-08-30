<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spotify_ft extends EE_Fieldtype {

	var $info = array(
		'name'      => 'Spotify',
		'version'   => '1.0'
	);

	// --------------------------------------------------------------------

	/**
	* Display Field on Publish
	*
	* @access  public
	* @param   existing data
	* @return  field html
	*
	*/
	function display_field($data)
	{

		$this->theme_url	= defined( 'URL_THIRD_THEMES' )
					? URL_THIRD_THEMES . 'spotify'
					: $this->EE->config->item('theme_folder_url') . 'third_party/spotify';

		$this->EE->cp->add_to_head('<link href="'.$this->theme_url.'/css/spotify.css" rel="stylesheet" />');
		$this->EE->cp->add_to_foot('<script src="'.$this->theme_url.'/js/spotify.js"></script>');

		$field = form_input($this->field_name, $data, 'class="spotify-input" placeholder="Spotify URI"');
		$response = '<div class="spotify-response"></div>';

		$html = '<div class="spotify-field">' . $field . $response . '</div>';

		return $html;
	}

	// --------------------------------------------------------------------

	/**
	* Replace tag
	*
	* @access  public
	* @param   field contents
	* @return  replacement text
	*
	*/
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		return $data;
	}

	/**
	* Install Fieldtype
	*
	* @access  public
	* @return  default global settings
	*
	*/
	function install()
	{
		return array();
	}
}

/* End of file ft.spotify.php */
/* Location: ./system/expressionengine/third_party/spotify/ft.spotify.php */