<?php

/*************************************************************************
 * This class contains options arrays for CSS Thumbnail Sprites class.
 * 
 *
 * @copyright 	wetell.co.nz
 * @author 		Dale Sattler
 * @version 	1
 * @link		https://wetell.co.nz
 *
 
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

class WtTSpriteOptions {
	
	private $text_domain = '';
	
    public function __construct() {
        
    }
    
    public function setTextDomain($newTextDomain) {
    	$this->text_domain = $newTextDomain;
    }
    
	/**
	* Method contains image filter array definitions
	*
	* @return  	array 		$filterAr	Contains image effect constants and select list option names.
	*/    
    public function get_effect_opts() {	
   		 $filterAr = array(	
						'0' => array(
							'value' =>	'IMG_FILTER_GRAYSCALE',
							'label' =>  __('Greyscale', $this->text_domain)
						),
						'1' => array(
							'value' =>	'IMG_FILTER_GRAYSCALE_RED',
							'label' =>  __('Greyscale except red', $this->text_domain)
						),
						'2' => array(
							'value' =>	'IMG_FILTER_GRAYSCALE_GREEN',
							'label' =>  __('Greyscale except green', $this->text_domain)
						),
						'3' => array(
							'value' =>	'IMG_FILTER_GRAYSCALE_BLUE',
							'label' =>  __('Greyscale except blue', $this->text_domain)
						),
						'4' => array(
							'value' =>	'IMG_FILTER_GRAYSCALE_YELLOW',
							'label' =>  __('Greyscale except yellow', $this->text_domain)
						),
						'5' => array(
							'value' =>	'IMG_FILTER_GREYSCALE_COLORIZE',
							'label' =>  __('Greyscale Colourise', $this->text_domain)
						),
						'6' => array(
							'value' =>	'IMG_FILTER_GREYSCALE_NEGATE',
							'label' =>  __('Greyscale Photo Negative', $this->text_domain)
						),
						'7' => array(
							'value' =>	'IMG_FILTER_NEGATE',
							'label' =>  __('Photo Negative', $this->text_domain)
						),
						'8' => array(
							'value' =>	'IMG_FILTER_CONTRAST',
							'label' =>  __('Contrast', $this->text_domain)
						),
						'9' => array(
							'value' =>	'IMG_FILTER_COLORIZE',
							'label' =>  __('Colourise', $this->text_domain)
						),
						'10' => array(
							'value' =>	'IMG_FILTER_SEPIA',
							'label' =>  __('Sepia', $this->text_domain)
						),
						'11' => array(
							'value' =>	'IMG_FILTER_BRIGHTNESS',
							'label' =>  __('Brightness', $this->text_domain)
						),
						'12' => array(
							'value' =>	'IMG_FILTER_PIXELATE',
							'label' =>  __('Pixelate', $this->text_domain)
						),
						'13' => array(
							'value' =>	'IMG_FILTER_SELECTIVE_BLUR',
							'label' =>  __('Selective Blur', $this->text_domain)
						),
						'14' => array(
							'value' =>	'IMG_FILTER_SCATTER',
							'label' =>  __('Scatter', $this->text_domain)
						),
						'15' => array(
							'value' =>	'IMG_FILTER_FLEA',
							'label' =>  __('Flea', $this->text_domain)
						)
					);
					
				return $filterAr;
		}
		
	  
	/**
	* Method contains plugin default ui options
	*
	* @return  	array 		$defaultOptions	Array containing html element definitions.
	*/    	
	 public function get_ui_default_opts() {	
 			$defaultOptions = 	array(	
				array(	'name'		=> __('Set CSS Sprite Attributes', $this->text_domain),
						'type'		=> 'title'
						),			
				array(	'name'		=> __('Set image sprite identifier', $this->text_domain),
						'label' 	=> __('<br />Registered by wordpress as an image size.', $this->text_domain),
						'id'		=> 'key',
						'placeholder' => 'image-sprite',
						'type'		=> 'text'
						),
				array(  'name'		=> __('Set sprite dimensions', $this->text_domain),
						'label'		=> __('Number (Numeral eg 200)', $this->text_domain),
				        'type'		=> 'numbers',
				        'options' 	=> array(
							        	'0' => array(
											'id' 		=> 'width',
											'min' 		=> '0',
								       		'max' 		=> '10000',
								       		'step' 		=> '1',
								       		'value' 	=> '400',
								            'default' 	=> 400,
								            'label' 	=> __('width(px) ', $this->text_domain)
										),
										'1' => array(
											'id' 		=> 'height',
											'min' 		=> '0',
								       		'max' 		=> '10000',
								       		'step' 		=> '1',
								       		'value' 	=> '400',
								            'default' 	=> 400,
								            'label' 	=> __('height(px)', $this->text_domain)
										)
								)
						),			
			    array(  'name' 		=> __('Set sprite jpg quality', $this->text_domain),
						'label' 	=> __('Range 0 to 100(best). Current amount is: ', $this->text_domain),
			            'id' 		=> 'jpg_qual',
			            'type'		=> 'slider',
			            'class'		=> 'img-qual',
			            'min'		=> 0,
			       		'max'		=> 100,
			       		'default'	=> 95
			       		),
			    array(  'name'		=> __('Set sprite png quality', $this->text_domain),
						'label'		=> __('Range 0(best) to 9. Current amount is: ', $this->text_domain),
			            'id'		=> 'png_qual',
			            'type'		=> 'slider',
			            'class'		=> 'img-qual',
			            'min'		=> 0,
			       		'max'		=> 9,
			       		'default' 	=> 0
			       		),						       		                 		
				array(  'name' 		=> __('Set image effect on sprite', $this->text_domain),
						'label' 	=> '',
				        'id' 		=> 'sprite_filter',
				        'type'		=> 'select',
				        'default' 	=> 'IMG_FILTER_GRAYSCALE'
						),
			    array(  'name'		=> __('Set brightness amount', $this->text_domain),
						'label'		=> __('Range(-255 to 255). Current amount is: ', $this->text_domain),
			            'id'		=> 'bright_val',
			            'type'		=> 'slider',
			            'class'		=> 'img-adjust brightness',						            
			            'min'		=> -255,
			       		'max'		=> 255,
			       		'default'  	=> 50
			       		),
			    array(  'name' 		=> __('Set contrast amount', $this->text_domain),
						'label' 	=> __('Range(-100 to 100). Current amount is: ', $this->text_domain),
			            'id' 		=> 'cont_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust contrast',
			            'min' 		=> -100,
			       		'max' 		=> 100,
			       		'default' 	=> -10
			       		),
			   	array(  'name' 		=> __('Set Colourise RED amount', $this->text_domain),
						'label' 	=> __('Range(-255 to 255). Current amount is: ', $this->text_domain),
			            'id' 		=> 'cred_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust colourise',
			            'min' 		=> -255,
			       		'max' 		=> 255,
			       		'default' 	=> 0
			       		),
			   	array(  'name' 		=> __('Set Colourise GREEN amount', $this->text_domain),
						'label' 	=> __('Range(-255 to 255). Current amount is: ', $this->text_domain),
			            'id' 		=> 'cgreen_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust colourise',
			            'min' 		=> -255,
			       		'max' 		=> 255,
			       		'default' 	=> 0
			       		),
			   	array(  'name' 		=> __('Set Colourise BLUE amount', $this->text_domain),
						'label' 	=> __('Range(-255 to 255). Current amount is: ', $this->text_domain),
			            'id' 		=> 'cblue_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust colourise',
			            'min' 		=> -255,
			       		'max' 		=> 255,
			       		'default' 	=> 0
			       		),
			   	array(  'name' 		=> __('Set Pixel block size', $this->text_domain),
						'label' 	=> __('Range(0 to 100). Current amount is: ', $this->text_domain),
			            'id' 		=> 'pix_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust pixel',
			            'min' 		=> 0,
			       		'max' 		=> 100,
			       		'default' 	=> 10
			       		),
			   	array(  'name' 		=> __('Set Scatter X range', $this->text_domain),
						'label' 	=> __('Range(0 - 100). Current amount is: ', $this->text_domain),
			            'id' 		=> 'scatter_x_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust scatter',
			            'min' 		=> 0,
			       		'max' 		=> 100,
			       		'default' 	=> 5
			       		),
			   	array(  'name' 		=> __('Set Scatter Y range', $this->text_domain),
						'label' 	=> __('Range(0 - 100). Current amount is: ', $this->text_domain),
			            'id' 		=> 'scatter_y_val',
			            'type' 		=> 'slider',
			            'class' 	=> 'img-adjust scatter',
			            'min' 		=> 0,
			       		'max' 		=> 100,
			       		'default' 	=> 5
			       		),
			    array(  'name' 		=> __('Greyscale colour', $this->text_domain),
						'label' 	=> __('Please choose a colour', $this->text_domain),
				        'id' 		=> 'grey_col_val',
				        'type' 		=> 'colour-picker',
				        'class' 	=> 'img-adjust grey-colour',
				        'default' 	=> '#00ff00'
				        ),            							       									       									       									       			   					
				array(  'name' 		=> __('Original image at top of sprite', $this->text_domain),
						'label' 	=> __('Select for top. Unselected state is bottom', $this->text_domain),
			            'id' 		=> 'img_pos',
			            'type' 		=> 'checkbox',
						'default' 	=> true
						)
				);
				
				return $defaultOptions;

	 }

	}
?>