<?php

class dk_Speakup_Import{
	var $importfilepath='';
	function scripts(){			
		wp_enqueue_script('speakupimport', plugins_url('/speakup-importer.js', __FILE__),false,false,true);
		wp_localize_script('speakupimport', 'speakupimport', array(
			'import_button' => __('import signatures','speakupimport'),
		));
	}
	function menu(){
		add_submenu_page('dk_speakup', __('Import', 'speakupimport' ), __('Import', 'speakupimport' ), 'manage_options', 'speakup-import', array( 'dk_Speakup_Import', 'page')); 
  	}
	/*
	 * Loads original Speakup plugin
	 */ 
	function load(){
		$path = dirname(plugin_dir_path( __FILE__ )).'/speakup-email-petitions/';	
		include_once( $path.'includes/class.speakup.php' );
		include_once( $path.'includes/class.signature.php' );
		include_once( $path.'includes/class.petition.php' );
	}
	/*
	 * Checks for bad caracters 
	 */
	function parsestr($str){
		$validstr=str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZéèêÉÈÊàâÀÂôÔùûÙÛçÇ0123456789 -_\'"&,.;:!?%+=@');
		$array=str_split($str);
		$str='';
		foreach($array as $c){
			if(in_array($c,$validstr)){
				$str.=$c;
			}
		}
		return $str;
	}
        function unquote($str){
            if(substr($str,0,1)=='"' && substr($str,-1)=='"'){
                $str = substr($str,1,-1);
            }
            return $str;
        }
	/*
	 * Displays the main page and make import
	 */ 
	function page(){ ?>
		<div class="wrap" id="dk-speakup">

		<div id="icon-dk-speakup" class="icon32"><br /></div>
		<h2><?php _e( 'Importer', 'speakupimport' ); ?></h2>
		<?php
		self::load();
		if(isset($_GET['pid']) && is_numeric($_GET['pid'])){
			$pid=$_GET['pid'];
			
			if(isset($_FILES['csv'])){
				if(substr($_FILES['csv']['name'],-4)==='.csv'){
					$importfilepath='../wp-content/uploads/speakupimport_'.get_current_blog_id().'_'.basename($_FILES['csv']['tmp_name']);
					if(move_uploaded_file($_FILES['csv']['tmp_name'], $importfilepath)){
						if(false!==$signcsv=self::parseCSV($importfilepath)){
							global $wpdb, $db_signatures;
								
							// Empty list
							if(isset($_POST['erase'])){
								$sql = "
									DELETE
									FROM $db_signatures
									WHERE `petitions_id` = '$pid'
								";
								$wpdb->query( $sql );
							}
							
								
							foreach($signcsv as $signe){
								$datas = array();
								$datas['first_name']        = isset($signe[0])?utf8_encode(self::unquote($signe[0])):'';
								$datas['last_name']         = isset($signe[1])?self::unquote($signe[1]):'';
								$datas['email']             = isset($signe[2])?self::unquote($signe[2]):'';
								$datas['street_address']    = isset($signe[3])?self::unquote($signe[3]):'';
								$datas['city']              = isset($signe[4])?self::unquote($signe[4]):'';
								$datas['state']             = isset($signe[5])?self::unquote($signe[5]):'';
								$datas['postcode']          = isset($signe[6])?self::unquote($signe[6]):'';
								$datas['country']           = isset($signe[7])?self::unquote($signe[7]):'';
								$datas['custom_field']      = isset($signe[8])?utf8_encode(self::unquote($signe[8])):'';
								$datas['date']              = isset($signe[9])?self::unquote($signe[9]):'';
								$datas['is_confirmed']      = isset($signe[10]) && ($signe[10]=='0' || $signe[10]==__( 'unconfirmed', 'dk_speakup' ))?0:1;
								//$datas->petitions_title   = $signe[11];
								$datas['petitions_id']      = $pid; //12
								$datas['optin']             = isset($signe[13]) && ($signe[13]=='0' || $signe[13]==__( 'no', 'dk_speakup' ))?0:1;
								$datas['custom_message']    = isset($signe[14])?self::unquote($signe[14]):'';
								$datas['language']          = isset($signe[15])?self::unquote($signe[15]):'';
								
								
								$the_signature = new dk_speakup_Signature();
								if ( $the_signature->has_unique_email( $datas['email'], $pid ) ) {
									$wpdb->insert( $db_signatures, $datas );
								}
							}
							 ?><script>document.location='admin.php?page=dk_speakup_signatures&action=petition&pid=<?=$pid?>';</script><?php // */
						}
						unlink($importfilepath);
					}
					else{
						?><div class="updated"><p><strong><?php _e('Error while uploading file !', 'speakupimport' ) ?></strong></p></div><?php
					}
				}			
				else{
					?><div class="updated"><p><strong><?php _e('This is not a CSV file !', 'speakupimport' ) ?></strong></p></div><?php
				}
			}
			$the_signatures = new dk_speakup_Signature();		
			$signatures = $the_signatures->all($pid);
			printf(__('Actually %s signatures in this petition','speakupimport'),count($signatures));
			include_once( dirname( __FILE__ ) . '/form.php' );
		}
		
		else{
			$the_petitions  = new dk_speakup_Petition();
			$petitions_list = $the_petitions->quicklist();
			echo'<h3>'.__( 'Select petition', 'dk_speakup' ).'</h3>';
			foreach( $petitions_list as $petition ) : ?>
				<p><a href="admin.php?page=speakup-import&pid=<?php echo $petition->id; ?>"><?php echo stripslashes( $petition->title ); ?></a></p>
			<?php endforeach;

		}
		?>
		</div>
		<?php
	}
	/*
	 * Checks if csv file us valid
	 */
	function parsecsv($file){
		if (($handle = fopen($file, "r")) !== FALSE) {
			$row=0;
			$datas=array();
    		while (($data = fgetcsv($handle, 1000, "\t",'"')) !== FALSE) {
    			$nb = count($data);
	        	if($nb>3 && (!isset($_POST['notfirst']) || $row>0)){
	        		foreach($data as $k=>$v){
	        			$data[$k]=self::parsestr($v);
	        		}
    				$datas[]=$data;
    			}
			if($nb<3){ // check number of columns
	        		?><div class="updated"><p><strong><?php _e('Error while parsing file, bad number of columns at line', 'speakupimport' ) ?> <?=$row?></strong></p></div><?php
	        		//echo'<pre>';print_r($data);echo'</pre>';
                                //return false;
	        	}
		        $row++;
		    }
		    fclose($handle);
			return $datas;
		}
		?><div class="updated"><p><strong><?php _e('Error while parsing file, cannot open document', 'speakupimport' ) ?></strong></p></div><?php		
		return false;
	}
}