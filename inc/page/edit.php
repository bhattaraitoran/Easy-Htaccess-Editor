<?php
/**
 * @package    	Easy_Htaccess_Editor        
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
if (!defined('ABSPATH')) die('Silence is golden!');


	$EHE_backup_path = EHE_CONTENT_URL.'/htaccess.backup';
	$EHE_orig_path = ABSPATH.'.htaccess';
	$ehe = new EHE_Handle_Htaccess();
	$ehe_notice = new EHE_Notice();
	$ehe_form  = new EHE_Form();

?>
	<div class="wrap">
	<h2 class="ehe-title"><?php _e( 'Easy Htaccess Editor' , 'easy-htaccess-editor' );?></h2>
	
	<?php
	/*
	 * Update Htaccess file
	 */
	if( $ehe_form->validate_request( 'update' ) ){
		
		$EHE_new_content = $ehe_form->sanitize_kses ( 'ht_content' );
		
		$ehe->delete_backup();
		
		if( $ehe->create_backup() ){

			if( $ehe->write_htaccess( $EHE_new_content ) ){

				$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __('Htaccess file has been successfully updated!', 'easy-htaccess-editor') ) );
			?>

				<div class="ehe-form ehe-edit-form" >
   
					<form method="post" action="admin.php?page=easy-htaccess-editor">

						<?php wp_nonce_field('ehe_delete','ehe_delete'); ?>

						<input type="hidden" name="delete_backup" value="delete" />
						
						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Remove Backup', 'easy-htaccess-editor');?>" />
						
						<a class="button button-primary" href="admin.php?page=easy-htaccess-editor-backup"><?php _e('Restore Original','easy-htaccess-editor');?></a></p>
					
					</form>

				</div>
				
				<?php

			}else{

				$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Htaccess file could not be updated!', 'easy-htaccess-editor' ) ) );
				
			}

		}else{

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to create backup of the original file! <code>wp-content</code> folder is not writeable! Change the permissions of this folder and try again.' , 'easy-htaccess-editor') ) );
		}

		unset($EHE_new_content);



	/*
	 * Create a new Htaccess file 
	 */

	}elseif( $ehe_form->validate_request( 'create' ) ){
		
		if( $ehe->write_htaccess( '# Created by Easy Htaccess Editor' ) === false){

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to create new Htaccess file!' , 'easy-htaccess-editor') ) );
			
        }else{

			$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __( 'Htaccess file has been created successfully!' , 'easy-htaccess-editor') ) );
			
		 }



	/*
	 * Clear backup 
	 */

	}elseif( $ehe_form->validate_request( 'delete' ) ){
        
        if( $ehe->delete_backup() === false ){

        	$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to remove backup file! <code>wp-content</code> folder is not writeable, Change the permissions of this folder and try again.' , 'easy-htaccess-editor') ) );

        }else{

	       	$ehe_notice->notice( array( 'class'=>'notice notice-success', 'message'=> __( 'Backup Htaccess file has been successfully removed!' , 'easy-htaccess-editor') ) );
        
        }



	/*
	 * Edit form
	 */
	}else{

		?>
		<div class="notice notice-warning">

			<p><?php _e('<strong>WARNING:</strong> Any error in this file may cause malfunction of your site!', 'easy-htaccess-editor');?></p> 
			
			<p><?php _e('For more information, please visit', 'easy-htaccess-editor');?> 
				<a href="http://httpd.apache.org/docs/current/howto/htaccess.html" target="_blank">
					Apache Tutorial: .htaccess files
				</a> 
				 <?php _e('or','easy-htaccess-editor'); ?> 
				<a href="http://net.tutsplus.com/tutorials/other/the-ultimate-guide-to-htaccess-files/" target="_blank">
				The Ultimate Guide to .htaccess Files
				</a>. 
			</p>

		</div>

		<?php

		if( ! file_exists( $EHE_orig_path ) ){

			$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Htaccess file does not exists!' , 'easy-htaccess-editor') ) );
			$success = false;

		}else{ 

			$success = true;

			if( !is_readable( $EHE_orig_path ) ){

				$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to read Htaccess file!' , 'easy-htaccess-editor') ) );
				$success = false;
			}

			if( $success == true ){

				@chmod( $EHE_orig_path, 0644 );

				$EHE_htaccess_content = @file_get_contents( $EHE_orig_path, false, NULL );

				if( $EHE_htaccess_content === false ){

					$ehe_notice->notice( array( 'class'=>'notice notice-error', 'message'=> __( 'Unable to read Htaccess file!' , 'easy-htaccess-editor') ) );

					$success = false;

				}else{

					$success = true;
				}
			}
		}

		if($success == true){
			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=easy-htaccess-editor">

					<input type="hidden" name="save_htaccess" value="save" />

					<?php wp_nonce_field('ehe_save','ehe_save'); ?>

					<h3 class="ehe-title"><?php _e('Current Htaccess file', 'easy-htaccess-editor');?></h3>

					<textarea name="ht_content" class="ehe-textarea widefat" wrap="off"><?php echo esc_html( $EHE_htaccess_content ) ;?></textarea>
					
					<p class="submit"><input type="submit" class="button button-primary" name="submit" value="<?php _e('Update', 'easy-htaccess-editor');?>" /></p>
				
				</form>
			</div>

			<?php

			unset($EHE_htaccess_content);

		}else{

			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=easy-htaccess-editor">

					<input type="hidden" name="create_htaccess" value="create" />

					<?php wp_nonce_field('ehe_create','ehe_create'); ?>

					<p class="submit"><?php _e('Create new <code>.htaccess</code> file?', 'easy-htaccess-editor');?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Create ', 'easy-htaccess-editor');?>" />
					
					</p>
				
				</form>
			</div>

			<?php
		}

		unset($success);
	}
	?>
	</div>

	<?php

	unset($EHE_orig_path);
	unset($EHE_backup_path);



