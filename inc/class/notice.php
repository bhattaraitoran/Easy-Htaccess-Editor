<?php
/**
 * @package    	Easy_Htaccess_Editor        
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
class EHE_Notice{

	function notice( $array ){

		echo'<div  class="'.$array['class'].'"><p>'.$array['message'].'</p></div>';
	}
}