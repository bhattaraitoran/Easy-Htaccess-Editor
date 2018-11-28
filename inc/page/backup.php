<?php
/**
 * @package    	Easy_Htaccess_Editor        
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined('ABSPATH' ) ) die( 'Silence is golden!' );

$ehe = new EHE_Handle_Htaccess();
$ehe_notice = new EHE_Notice();
$ehe_form  = new EHE_Form();
?>

	<div class="wrap">

	<h2 class="ehe-title"><?php _e( 'Easy Htaccess Editor' , 'easy-htaccess-editor' );?> - <?php _e('Backup', 'easy-htaccess-editor'); ?></h2>

	<?php

	/*
	 *Restore Backup 
	 */

	if( $ehe_form->validate_request( 'restore' ) ){

		$ehe_restore_result = $ehe->restore_backup();
		
		if($ehe_restore_result === false){

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to restore Htaccess file! Please check file permissions and try again.', 'easy-htaccess-editor') ) );
			
		}elseif( $ehe_restore_result === true ){

			$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __( 'Htaccess file has been successfully restored!', 'easy-htaccess-editor') ) );
			
		}else{

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to restore Htaccess file!', 'easy-htaccess-editor') ) );
			
			echo'<div class="postbox">';
			echo'<p>'.__('Please update your Htaccess file manually with following original content. ','easy-htaccess-editor').':</p>';
			echo'<textarea class="ehe-textarea widefat">'. esc_html( $ehe_restore_result  ) .'</textarea>';
			echo'</div>';
		}




	/* 
	 * Create Backup
	 */

	}elseif( $ehe_form->validate_request( 'create_backup' ) ){
		
		if( $ehe->create_backup() ){

			$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __( 'Backup file was created successfully. The backup file is located in the <code>wp-content</code> folder', 'easy-htaccess-editor') ) );

		}else{

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to create backup! <code>wp-content</code> folder is not writeable! Change the permissions and try again.', 'easy-htaccess-editor') ) );
			
		}




	/*
	 * Delete Backup
	 */

	}elseif( $ehe_form->validate_request( 'delete_backup' ) ){

		if( $ehe->delete_backup() ){

			$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __( 'Backup file has been successfully removed!', 'easy-htaccess-editor') ) );
			
		}else{

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to remove backup file! Please check file permissions and try again.', 'easy-htaccess-editor') ) );
			
		}



	 /*
	  * Backup defaul page
	  */

	}else{

		if( file_exists( ABSPATH.'wp-content/htaccess.backup' ) ){

			?> 
			<div class="postbox">

				<form method="post" action="admin.php?page=easy-htaccess-editor-backup">

					<?php wp_nonce_field('ehe_restoreb','ehe_restoreb'); ?>

					<input type="hidden" name="restore_backup" value="restore" />

					<p class="submit"><?php _e('Do you want to restore the backup file?', 'easy-htaccess-editor'); ?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Restore Backup', 'easy-htaccess-editor'); ?>" />

					</p>
				
				</form>

			</div>

			
			<div class="postbox">

				<form method="post" action="admin.php?page=easy-htaccess-editor-backup">

					<?php wp_nonce_field('ehe_deleteb','ehe_deleteb'); ?>

					<input type="hidden" name="delete_backup" value="delete" />

					<p class="submit"><?php _e('Do you want to delete a backup file?', 'easy-htaccess-editor'); ?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Remove Backup', 'easy-htaccess-editor'); ?>" />

					</p>

				</form>

			</div>

			<?php
			
		}else{
			
			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Backup file not found!', 'easy-htaccess-editor') ) );
			
			?>

			<div class="postbox">

				<form method="post" action="admin.php?page=easy-htaccess-editor-backup">

					<?php wp_nonce_field('ehe_createb','ehe_createb'); ?>

					<input type="hidden" name="create_backup" value="create" />

					<p class="submit"><?php _e('Do you want to create a new backup file?', 'easy-htaccess-editor'); ?> 
					
						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Create New', 'easy-htaccess-editor'); ?>" />
					
					</p>
				
				</form>

		   </div>

			<?php
			
		}
	}
	?>
	
	</div>
	<?php
