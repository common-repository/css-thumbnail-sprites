<?php /*

**************************************************************************

Plugin Name:	CSS Thumbnail Sprites
Plugin URI:		http://www.wetell.co.nz
Description:	Allows for the creating of CSS sprites of user defined sizes from uploaded images with various image effects.
Version:		1.0.0
Text Domain:	thumbnail-sprites
Author:			We Tell
Author URI:		https://www.wetell.co.nz

**************************************************************************

Copyright (C) 2012 wetell

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

if( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'put_version_require' );
	function put_version_require() {
		if( current_user_can( 'manage_options' ) )
			echo '<div class="error"><p>The CSS Thumbnail Sprites plugin requires at least PHP 5.3</p></div>';
	}
	return;
}

require_once('class-wt-options.php');
require_once('class-wt-img-effects.php');

class Wt_ThumbnailSprite {
	
	private $admin_hook;
    private $textDom 		= 'thumbnail-sprites';
    private $version    	= '1.0.0';
    private $filterSelAr	= array();
	private $defaultOptions	= array();
	public static $reservedSizes = array('thumbnail' => 'thumbnail', 'medium' => 'medium', 'large' => 'large');
	

	public function __construct() {
		if (!is_admin()) {return;}
        add_action('admin_init', array( $this, 'register_plug_settings' ) );
        add_action('admin_menu',  array( $this, 'add_admin_menu' ));
        
        //get options from options class instance
        $optsRef = new WtTSpriteOptions();
        $optsRef->setTextDomain($this->textDom);        
       	$this->defaultOptions = $optsRef->get_ui_default_opts();        
        $this->filterSelAr = apply_filters( 'css-sprites-filter-list',  $optsRef->get_effect_opts());
    }
    
    
    public function register_plug_settings() {
    	global $plugin_page;
    	register_setting( 'wt_sprite_plug_options', 'wt_sprite_options', array( $this,'wt_sprite_validate_options') );
    	register_uninstall_hook(__FILE__, 'Wt_ThumbnailSprite::delete_wt_sprite_options');
    	
		$initSettings = get_option( 'wt_sprite_options', false );
    	
    	if($initSettings != false || !isset($initSettings) ) {
        		foreach($initSettings as $item) {
        			if($item){
        				add_image_size($item['key'], (0+$item['width']), (0+$item['height']), true);
        			}
        		}
        		
        	add_filter('wp_generate_attachment_metadata', array( &$this, 'generate_sprite'), 1, 2);	
        }
        
        if( 'thumbnail-sprites' == $plugin_page ) {
        	load_plugin_textdomain( $this->textDom, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }    
            
    }
   

    public function add_admin_menu() {
    	$this->admin_hook = add_media_page(__('CSS Thumbnail Sprites Options', $this->textDom), __( 'Thumbnail Sprites', $this->textDom), 'manage_options', 'thumbnail-sprites', array($this, 'get_data'));
    	add_action( "load-{$this->admin_hook}", array( $this, 'wt_sprite_queue_scripts' ));	
		add_action( "load-{$this->admin_hook}", array( $this, 'wt_sprite_queue_styles' ));
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", array( $this,'create_settings_link') );
    }
    
    
    public function wt_sprite_queue_scripts() {
    	wp_enqueue_script( 'jquery' );
    	wp_enqueue_script( 'jquery-ui-core' );
    	wp_enqueue_script( 'farbtastic' );
    	wp_enqueue_script( 'jquery-ui-slider', plugins_url( 'js/jquery.ui.slider.min.js', __FILE__ ), array( 'jquery-ui-core' ), '1.8.16' );
		wp_register_script('css-sprites-js',  plugins_url( 'js/wt-css-sprites.js', __FILE__ ), array('jquery','farbtastic','jquery-ui-core'));
    	wp_enqueue_script( 'css-sprites-js' );
    }
    
    public function wt_sprite_queue_styles(){
    	wp_enqueue_style( 'jquery-ui-tsprites', plugins_url( 'js/custom-theme/jquery-ui-1.8.16.custom.css', __FILE__ ), array(), '1.8.16' );
    	wp_enqueue_style( 'farbtastic' );
    	wp_register_style( 'css-sprites-css', plugins_url( 'css/wt-css-sprites.css', __FILE__ ));
		wp_enqueue_style( 'css-sprites-css' );
    }
    
    /*
     Settings link in admin section
    */
   	public function create_settings_link($links) { 
  		$settings_link = '<a href="upload.php?page=thumbnail-sprites">'.__('Settings', $this->textDom).'</a>'; 
  		array_unshift($links, $settings_link); 
  		return $links; 
	}
	

 
	/*
     * Draw basic UI and access any existing data saved by user
     * @return
     */
    public function get_data(){
		echo '<div class="wrap thumbnail-sprites">';
		settings_errors( 'wt_sprite_options' );

		$plug_name = 'wt_sprite_options';
		$data = get_option( 'wt_sprite_options' );
		  
		screen_icon(); 
		echo "<h2>". __( ' CSS Thumbnail Sprites Options', $this->textDom ) . "</h2>";
		echo '<p>'.__('Set your sprite attributes below.').' </p>';
			
		echo '<form action="options.php" method="post">';		
		settings_fields( 'wt_sprite_plug_options' );
				
		echo '<div id="css-items" class="form-table">';
		  $c = 0;
		    if (count($data) > 0){
		        foreach((array)$data as $p ){
		            if (isset($p['key']) || isset($p['width'])|| isset($p['height']) || isset($p['jpg_qual']) || isset($p['png_qual']) || isset($p['sprite_filter']) || isset($p['img_pos'])){
		                echo $this->render_form($c,$p);
		                $c = $c +1;
		            }
		        }
		
		    }
		    echo '</div>';
		
		    ?>
			<div class="pad">&nbsp;</div>
		   	<input type="button" class="button button-highlighted add" name="add" id="add" value="<?php _e('Add New CSS Sprite', $this->textDom); ?>" />
		   	
		  	<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Sprite Options', $this->textDom ); ?>" />
			</p>
			
		</form>
		</div>
			<script type="text/javascript">
				//also uses methods from external js file
				jQuery(document).ready(function($) {
					var count = <?php echo $c; ?>;						
					$(".add").click(function() {
		                  count++;
		                  $('#css-items').append('<?php echo implode('',explode("\n",$this->render_form('count'))); ?>'.replace(/count/g, count));
		                   //setUpSliders();
		                   ///setUpColourPickers();
		                   wt_css.config_colour();
		                   wt_css.config_sliders();
		                   $(".slider-"+count).not('.img-qual').hide();
		                   $(".colour-"+count).hide();
		                   return false;
		             });
				});
			</script>
		
		<?php
	}

	/*
     * Draw sprite attribute form.
     * @param 		integer		$count	Integer representing array counter
     * @param 		string		$p		String representing array key of sprite form element options
     * @return  	string 		$str	String containing sprite options form elements
     */     
	public function render_form($cnt, $p = null){
		$plug_name = 'wt_sprite_options';
		    
		if ($p === null){
		    $a = $b = $c = $d = $e = $f = $g = $h = $i = $pxVal = $cRedVal = $cGreenVal = $cBlueVal = $scatterXVal = $scatterYVal = $greyColVal = '';		    
		}else{
		    $a = $p['key'];
		    $b = $p['width'];
		    $c = $p['height'];
		    $d = $p['jpg_qual'];
		    $e = $p['png_qual'];
		    $f = $p['sprite_filter'];
		    $g = $p['img_pos'];
		    $h = $p['bright_val'];
		    $i = $p['cont_val'];
		    $pxVal = $p['pix_val'];
		    $cRedVal = $p['cred_val'];
		    $cGreenVal = $p['cgreen_val'];
		    $cBlueVal = $p['cblue_val'];
		    $scatterXVal = $p['scatter_x_val'];
		    $scatterYVal = $p['scatter_y_val'];
		    $greyColVal = $p['grey_col_val'];
		}
		
		$str ='<div class="sprite-table"><table>';
		foreach($this->defaultOptions as $value) {			
			switch ( $value['type'] ) {
				case 'title':
					$str .= '<tr valign="top"><th scope="row"></th>';
					$str .= '<td class="title"><h3>'.$value['name'].'</h3></td>';
					$str .= '</tr>' ;
					break;
				case 'text':
					$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			        $str .= '<tr valign="top"><th scope="row">'. $value['name'].'</th>';		
					$str .= '<td>';
					$str .= '<input id="'.$name.']" class="regular-text" type="text" placeholder="'.$value['placeholder'].'" name="'.$name.'" required="required" value="'.$a.'" />';
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
			        break;
			    case 'number':
					$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			        $str .= '<tr valign="top"><th scope="row">'. $value['name'].'</th>';		
					$str .= '<td>';
					$str .= '<input id="'.$name.'" class="regular-number" type="number" min="'.$value['min'].'" max="'.$value['max'].'" name="'.$name.'" value="'.$b.'" />';
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
					
					$str .= '<input type="range" name="points" min="1" max="10" />';
			        break;
			    case 'numbers':
			        $str .= '<tr valign="top"><th scope="row">'.$value['name'].'</th>';		
					$str .= '<td>';
					foreach($value['options'] as $item) {
						$name = $plug_name.'['.$cnt.']['.$item['id'].']';
						$item['id'] == 'width' ? $val = $b: $val= $c;
						$str .= '<input id="'.$name.'" class="regular-number" type="number" min="'.$item['min'].'" max="'.$item['max'].'" name="'.$name.'" value="'.$val.'" />';
						$str .= '<label class="description" for="'.$name.'">'.$item['label'].'</label>';
					}
					$str .= '</td></tr>';
					
				    break;			        
			    case 'slider':
			    	$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			    	
			    	if($value['id'] === 'jpg_qual'){
			    		if($d==''){
			    			$d = $value['default'];
			    		}
			    		$val = $d;
			    	}
			    	
			    	if($value['id'] === 'png_qual'){
			    		if($e==''){
			    			$e = $value['default'];
			    		}
			    		$val = $e;
			    	}
			    	
			    	if($value['id'] === 'bright_val'){
			    		if($h ==''){
			    			$h= $value['default'];
			    		}
			    		$val = $h;
			    	}
			    	
			    	if($value['id'] === 'cont_val'){
			    		if($i ===''){
			    			$i = $value['default'];
			    		}
			    		$val = $i;
			    	}
			    	
			    	if($value['id'] === 'pix_val'){
			    		if($pxVal ===''){
			    			$pxVal = $value['default'];
			    		}
			    		$val = $pxVal;
			    	}
			    	
			    	if($value['id'] === 'cred_val'){
			    		if($cRedVal ===''){
			    			$cRedVal = $value['default'];
			    		}
			    		$val = $cRedVal;
			    	}
			    	
			    	if($value['id'] === 'cgreen_val'){
			    		if($cGreenVal ===''){
			    			$cGreenVal = $value['default'];
			    		}
			    		$val = $cGreenVal;
			    	}
			    	
			    	if($value['id'] === 'cblue_val'){
			    		if($cBlueVal ===''){
			    			$cBlueVal = $value['default'];
			    		}
			    		$val = $cBlueVal;
			    	}
			    	if($value['id'] === 'scatter_x_val'){
			    		if($scatterXVal === ''){
			    			$scatterXVal = $value['default'];
			    		}
			    		$val = $scatterXVal;
			    	}			    				    				    	
			    	if($value['id'] === 'scatter_y_val'){
			    		if($scatterYVal ===''){
			    			$scatterYVal = $value['default'];
			    		}
			    		$val = $scatterYVal;
			    	}			    	
			        $str .= '<tr valign="top" class="slider-'.$cnt.' '.$value['class'].'"><th scope="row">'.$value['name'].'</th>';
					$str .=	'<td>';
					$str .=	'<div id="slider_'.$name.'" class="slider" data-value="'.$val.'" data-min="'.$value['min'].'" data-max="'.$value['max'].'">'.$val.'</div>';		
					$str .=	'<input type="hidden" name="'.$name.'"  id="'.$name.'" value="'.$val.'"  />';
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
			        break;
			    case 'select':
			    	$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			        $str .= '<tr valign="top"><th scope="row">'.$value['name'].'</th>';
					$str .=	'<td>';
			
					$str .= '<select id="select_'.$name.'" name="'.$name.'" class="select">';
					foreach($this->filterSelAr as $item) {
						$selected = ($f == $item['value']) ? 'selected="selected"' : '';
						$str .= '<option value="'.$item['value'].'" '.$selected.'>'.$item['label'].'</option>';
					}
					
					$str .=	'</select>';
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
			        break;
			   case 'checkbox':
			   		if($g==''){
			    		$g = $value['default'];
			    		$checked = "";
			    	}else{
			    		$checked = "checked=\"checked\"";
			    	}
 		
			    	$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			        $str .= '<tr valign="top"><th scope="row">'.$value['name'].'</th>';
					$str .=	'<td>';
					$str .=	'<input type="checkbox" name="'.$name.'" id="'.$name.'"  value="true" '.$checked.' />	';				    
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
			        break; 
			   case 'colour-picker':			    	
			    	if($value['id'] === 'grey_col_val'){
			    		if($greyColVal ==''){
			    			$greyColVal = $value['default'];
			    		}
			    		$val = $greyColVal;
			    	}
			    	
			    	$name = $plug_name.'['.$cnt.']['.$value['id'].']';
			        $str .= '<tr valign="top" class="colour-'.$cnt.' '.$value['class'].'"><th scope="row">'.$value['name'].'</th>';
					$str .=	'<td>';					
					$str .= '<div class="color-picker" style="position: relative;">';
					$str .= '<div style="position: absolute; z-index:10000;" class="colorpicker"></div>';
					
					$str .= '<input id="color" class="colour-input small-text" type="text" name="'.$name.'" value="'.$val.'" />';			
					$str .= '<label class="description" for="'.$name.'">'.$value['label'].'</label>';
					$str .= '</td></tr>';
			        break;                   
			}
			
		}
		
		$str .='<tr valign="top"><th scope="row"><td><span class="remove button-secondary red-border">'.__('Remove Sprite', $this->textDom).'</span></td></tr>';
		$str .='</table></div>';
		echo $str;
	}
                	
	
	/*
     * Validate options. If invalid option is passed in, method will alert user via settings errors and nothing will save.
     * @param 		array		$input	Array of form options.
     * @return  	array 		$valid	Array containing sanitsed options.
     */
	public function wt_sprite_validate_options($input){
		$valid = array();
		$initSettings = get_option( 'wt_sprite_options', false );
		
		if ($_POST['option_page'] != 'wt_sprite_plug_options') {
			return;
		}
		
		if (empty($_POST) && !check_admin_referer( 'wt_sprite_plug_options-options' ) ) {
   			add_settings_error('wt_sprite_options', 'badadmin', __('<h3><strong>Smack your hands!</strong></h3> - You don\'t appear to be an admin! Nothing was saved.', $this->textDom), 'error');
   			return $input;
		}

		foreach($input as &$item) {	
			$validData = array();
			//sanitize  results		
			//first check if size chosen is reserved
			if (isset(self::$reservedSizes[$item['key']])) {
				add_settings_error('wt_sprite_options', 'key', __('Oops - The name you have set for your key is reserved by Wordpress. Please enter a new key.', $this->textDom), 'error');
            	break;
        	}
			
			if( $item['key'] !=''){
				$validData['key'] = sanitize_title( $item['key']);
			}else{
				add_settings_error('wt_sprite_options', 'key', __('Oops - Key is empty or malformed.', $this->textDom), 'error');
			}
			
			$validData['width'] = absint($item['width']);
			if ($validData['width'] == 0) {
            	add_settings_error('wt_sprite_options', 'height', __('Oops - Height needs to be higher than 0', $this->textDom), 'error');          
            }
			
			$validData['height'] = absint($item['height']);
            if ($validData['height'] == 0) {
            	add_settings_error('wt_sprite_options', 'width', __('Oops - Width needs to be higher than 0', $this->textDom), 'error');               	
            }
            
            $validData['jpg_qual'] 		= absint($item['jpg_qual']);
            $validData['png_qual'] 		= absint($item['png_qual']);
            $validData['sprite_filter'] = $item['sprite_filter'];
            $validData['img_pos'] 		= $item['img_pos'];
            $validData['bright_val'] 	= intval($item['bright_val']);
            $validData['cont_val'] 		= intval($item['cont_val']);
            $validData['cred_val'] 		= intval($item['cred_val']);
            $validData['cgreen_val'] 	= intval($item['cgreen_val']);
            $validData['cblue_val'] 	= intval($item['cblue_val']);
            $validData['pix_val'] 		= absint($item['pix_val']);
            $validData['scatter_x_val'] = absint($item['scatter_x_val']);
            $validData['scatter_y_val'] = absint($item['scatter_y_val']);
            $validData['grey_col_val'] 	= $item['grey_col_val'];
            array_push($valid,$validData);
			
		}
		
		if(get_settings_errors('wt_sprite_options')){
			if($initSettings != false) {
				return $initSettings;
			}else{
				return $input;
			}
		}
		
		
		//handle a save of empty options
		if(empty($valid)){
			add_settings_error('wt_sprite_options', 'badadmin', __('Nothing was saved. Looks like you just pressed save without configuring any sprite options', $this->textDom), 'error');
   			return $input;
		}
		
		//everything validates - return new options data and notify user
		add_settings_error('wt_sprite_options', 'updated', __('<em>Success!</em> CSS Sprite Parameters updated!', $this->textDom), 'updated');
		return $valid;
	}	

	/*
     * Delete options when plugin removed.
     * @return
     */	
	 public static function delete_wt_sprite_options() {
	 	if ( __FILE__ != WP_UNINSTALL_PLUGIN ){
            return;
        }
		delete_option('wt_sprite_options');
	}
	
	/*
     * Generate the sprite image for each sprite created in the admin interface.
     * @param 		array		$meta	Array of attachment metadata
     * @param	  	integer 	$id		Integer representing current attachment ID
     * @return		array		$meta	Array of updated attachment metadata
     */	
	public function generate_sprite($meta, $id) {

		if(!isset($meta['sizes'])) {
			return $meta;
		}	
		
		global $_wp_additional_image_sizes;
		
		$file = wp_upload_dir();		
		$path = $file['basedir'];
		
		if(isset($path)){		
			$file = trailingslashit($file['basedir'].'/').$meta['file'];
		}else{
			$file = trailingslashit($file['path']).$meta['file'];
		}
		
		$path_parts = pathinfo($file);
		list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
		
		$spritesList = get_option( 'wt_sprite_options', false );
		
		if(!$spritesList) {
			return $meta;
		}
		
		foreach($spritesList as $sprite) {	
			$image = wp_load_image($file);
			$spriteFileName = trailingslashit(pathinfo($file, PATHINFO_DIRNAME)).$meta['sizes'][$sprite['key']]['file'];
			$targWidth = $_wp_additional_image_sizes[$sprite['key']]['width'];
			$targHeight = $_wp_additional_image_sizes[$sprite['key']]['height'];
			
			$dims = image_resize_dimensions($orig_w, $orig_h, $targWidth, $targHeight*.5, true);
	
			$output_image = imagecreatetruecolor($targWidth, $targHeight); 
			$temp_image = imagecreatetruecolor($targWidth, $targHeight * .5); 
			
			list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;
							
			//image effect position
			//top
			if(isset($sprite['img_pos'])) {
				imagecopyresampled( $temp_image, $image, $dst_x, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				imagecopyresampled( $output_image, $image, $dst_x, $targHeight * .5, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				WtImageEffects::do_image_effect($temp_image, $sprite);
				imagecopyresampled( $output_image, $temp_image, $dst_x, $dst_y, $src_x, 0, $dst_w, $dst_h, $targWidth, $targHeight*.5);			
			}else{
				//bottom
				imagecopyresampled( $temp_image, $image, $dst_x, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				imagecopyresampled( $output_image, $image, $dst_x, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				WtImageEffects::do_image_effect($temp_image, $sprite);
				imagecopyresampled( $output_image, $temp_image, $dst_x, $targHeight * .5, $src_x, 0, $dst_w, $dst_h, $targWidth, $targHeight*.5);
			}
			
			switch ($orig_type) {
				case IMAGETYPE_GIF:
					imagegif($output_image, $spriteFileName );
					break;
				case IMAGETYPE_PNG:
					imagepng( $output_image, $spriteFileName, $sprite['png_qual'] );
					break;
				case IMAGETYPE_JPEG:
					imagejpeg($output_image, $spriteFileName, $sprite['jpg_qual']);
					break;
			}
		
			imagedestroy($output_image);
			imagedestroy($image);
			imagedestroy($temp_image);
		}
						
		wp_update_attachment_metadata($id, $meta);
		return $meta;
	}
	
}//end class

$wt_css_sprite = new Wt_ThumbnailSprite();

?>