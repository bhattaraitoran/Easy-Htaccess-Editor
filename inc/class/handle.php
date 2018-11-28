<?php
/**
 * @package    	Easy_Htaccess_Editor        
 * Author:      Toran Bhattarai
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );

Class EHE_Handle_Htaccess{

		/*
		 *Create a htaccess file in the wp-content folder 
		 */
		public function secure_wp_content(){
			
			$ehe_secure_path = ABSPATH.'wp-content/.htaccess';
			$ehe_secure_text = 
			'# Easy Htaccess Editor - Secure backups
			<files htaccess.backup>
			order allow,deny
			deny from all
			</files>';

			if(is_readable(ABSPATH.'wp-content/.htaccess')){

				$ehe_secure_content = @file_get_contents(ABSPATH.'wp-content/.htaccess');

				if($ehe_secure_content !== false){

					if(strpos($ehe_secure_content, 'Secure backups') === false){

						unset($ehe_secure_content);
						$ehe_create_sec = @file_put_contents(ABSPATH.'wp-content/.htaccess', $ehe_secure_text, FILE_APPEND|LOCK_EX);
						
						if($ehe_create_sec !== false){

							unset($ehe_secure_text);
							unset($ehe_create_sec);
							return true;

						}else{

							unset($ehe_secure_text);
							unset($ehe_create_sec);
							return false;
						}

					}else{

						unset($ehe_secure_content);
						return true;
					}

				}else{

					unset($ehe_secure_content);
					return false;
				}

			}else{

				if(file_exists(ABSPATH.'wp-content/.htaccess')){

					return false;

				}else{

					$ehe_create_sec = @file_put_contents(ABSPATH.'wp-content/.htaccess', $ehe_secure_text, LOCK_EX);

					if($ehe_create_sec !== false){

						return true;

					}else{

						return false;
					}
				}
			}
		}


		/*
		 * Create a backup of the original htaccess file 
		 */
		function create_backup(){

			$EHE_backup_path = ABSPATH.'wp-content/htaccess.backup';
			$EHE_orig_path = ABSPATH.'.htaccess';
			@clearstatcache();

			$this->secure_wp_content();

			if(file_exists($EHE_backup_path)){

				$this->delete_backup();

				if(file_exists(ABSPATH.'.htaccess')){

					$htaccess_content_orig = @file_get_contents($EHE_orig_path, false, NULL);
					$htaccess_content_orig = trim($htaccess_content_orig);
					$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
					$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
					@chmod($EHE_backup_path, 0666);
					$EHE_success = @file_put_contents($EHE_backup_path, $htaccess_content_orig, LOCK_EX);

					if($EHE_success === false){

						unset($EHE_backup_path);
						unset($EHE_orig_path);
						unset($htaccess_content_orig);
						unset($EHE_success);
						return false;

					}else{

						unset($EHE_backup_path);
						unset($EHE_orig_path);
						unset($htaccess_content_orig);
						unset($EHE_success);
						return true;

					}
					@chmod($EHE_backup_path, 0644);

				}else{

					unset($EHE_backup_path);
					unset($EHE_orig_path);
					return false;
				}

			}else{

				if(file_exists(ABSPATH.'.htaccess')){

					$htaccess_content_orig = @file_get_contents($EHE_orig_path, false, NULL);
					$htaccess_content_orig = trim($htaccess_content_orig);
					$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
					$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
					@chmod($EHE_backup_path, 0666);
					$EHE_success = @file_put_contents($EHE_backup_path, $htaccess_content_orig, LOCK_EX);

					if($EHE_success === false){

						unset($EHE_backup_path);
						unset($EHE_orig_path);
						unset($htaccess_content_orig);
						unset($EHE_success);
						return false;

					}else{

						unset($EHE_backup_path);
						unset($EHE_orig_path);
						unset($htaccess_content_orig);
						unset($EHE_success);
						return true;

					}

					@chmod($EHE_backup_path, 0644);

				}else{

					unset($EHE_backup_path);
					unset($EHE_orig_path);
					return false;
				}
			}
		}


		/*
		 *Restore backup 
		 */
		function restore_backup(){

			$ehe_backup_path = ABSPATH.'wp-content/htaccess.backup';
			$EHE_orig_path = ABSPATH.'.htaccess';

			@clearstatcache();

			if(!file_exists($ehe_backup_path)){

				unset($ehe_backup_path);
				unset($EHE_orig_path);
				return false;

			}else{

				if(file_exists($EHE_orig_path)){

					if(is_writable($EHE_orig_path)){

						@unlink($EHE_orig_path);

					}else{

						@chmod($EHE_orig_path, 0666);
						@unlink($EHE_orig_path);
					}
				}

				$ehe_htaccess_content_backup = @file_get_contents($ehe_backup_path, false, NULL);

				if( $this->write_htaccess($ehe_htaccess_content_backup) === false){

					unset($ehe_success);
					unset($EHE_orig_path);
					unset($ehe_backup_path);
					return $ehe_htaccess_content_backup;

				}else{

					$this->delete_backup();
					unset($ehe_success);
					unset($ehe_htaccess_content_backup);
					unset($EHE_orig_path);
					unset($ehe_backup_path);
					return true;
				}
			}
		}



		/*
		 * Delete backup file
		 */
		function delete_backup(){

			$ehe_backup_path = ABSPATH.'wp-content/htaccess.backup';

			@clearstatcache();

			if(file_exists($ehe_backup_path)){

				if(is_writable($ehe_backup_path)){

					@unlink($ehe_backup_path);

				}else{

					@chmod($ehe_backup_path, 0666);
					@unlink($ehe_backup_path);
				}

				@clearstatcache();

				if(file_exists($ehe_backup_path)){

					unset($ehe_backup_path);

					return false;

				}else{

					unset($ehe_backup_path);
					return true;
				}

			}else{

				unset($ehe_backup_path);
				return true;
			}
		}



		/* 
		 * Create a new htaccess file 
		 */
		function write_htaccess($EHE_new_content){

			$EHE_orig_path = ABSPATH.'.htaccess';

			@clearstatcache();

			if(file_exists($EHE_orig_path)){

				if(is_writable($EHE_orig_path)){

					@unlink($EHE_orig_path);

				}else{

					@chmod($EHE_orig_path, 0666);
					@unlink($EHE_orig_path);
				}
			}

			$EHE_new_content = trim($EHE_new_content);
			$EHE_new_content = str_replace('\\\\', '\\', $EHE_new_content);
			$EHE_new_content = str_replace('\"', '"', $EHE_new_content);
			$EHE_write_success = @file_put_contents($EHE_orig_path, $EHE_new_content, LOCK_EX);
			@clearstatcache();

			if(!file_exists($EHE_orig_path) && $EHE_write_success === false){

				unset($EHE_orig_path);
				unset($EHE_new_content);
				unset($EHE_write_success);
				return false;

			}else{

				unset($EHE_orig_path);
				unset($EHE_new_content);
				unset($EHE_write_success);
				return true;
			}
		}

}