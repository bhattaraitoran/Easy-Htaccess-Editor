<?php
/**
 * @package    	Easy_Htaccess_Editor        
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class EHE_Form{

	public function validate_request( $form ){

		if( $form == 'update' ){
			
			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'save_htaccess') &&  check_admin_referer( 'ehe_save', 'ehe_save' ) )
				return true;
			return false;

		}else if( $form == 'create' ){

			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'create_htaccess') &&  check_admin_referer( 'ehe_create', 'ehe_create' ) )
				return true;
			return false;

		}else if( $form == 'delete' ){

			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'delete_backup') &&  check_admin_referer( 'ehe_delete', 'ehe_delete' ) )
				return true;
			return false;

		}else if( $form == 'restore' ){

			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'restore_backup') &&  check_admin_referer( 'ehe_restoreb', 'ehe_restoreb' ) )
				return true;
			return false;

		}else if( $form == 'create_backup' ){

			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'create_backup') &&  check_admin_referer( 'ehe_createb', 'ehe_createb' ) )
				return true;
			return false;

		}else if( $form == 'delete_backup' ){

			if( $this->sanitize_text( 'submit') && $this->sanitize_text( 'delete_backup') &&  check_admin_referer( 'ehe_deleteb', 'ehe_deleteb' ) )
				return true;
			return false;
		}
	}


	public function sanitize_text( $key ){

		if( ! empty( $_POST[$key] ) ){

			return sanitize_text_field( $_POST[$key] );
		}
		return false;
	}


	public function sanitize_kses( $key ){

		if( ! empty( $_POST[$key] ) ){

			return wp_kses_post( $_POST[$key] );
		}
		return false;
	}
}