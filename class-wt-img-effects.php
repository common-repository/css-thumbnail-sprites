<?php

/*************************************************************************
 * This class contains various image filter methods.
 * 
 *
 * @copyright 	wetell.co.nz
 * @author 		Dale Sattler
 * @version 	1
 * @link		https://wetell.co.nz
 *
 
**************************************************************************
Copyright (C) 2012	wetell

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

class WtImageEffects {

    public function __construct() {
        
    }
    
	/**
	* Worker method to split image filter request out to image filter functions.
	*
	* @param  	resource 	$image
	* @param  	array 		$spriteData	Contains image effect constant, and various other values.
	*/    
    public static function do_image_effect($image, $spriteData) {	
		$effectId = $spriteData['sprite_filter'];
		switch ($effectId) {
			case 'IMG_FILTER_GRAYSCALE':
				imagefilter($image, constant($effectId));
				break;
			case 'IMG_FILTER_NEGATE':
				imagefilter($image, constant($effectId));
				break;
			case 'IMG_FILTER_SEPIA':
				imagefilter($image, IMG_FILTER_GRAYSCALE); 
				imagefilter($image, IMG_FILTER_COLORIZE, 90, 60, 40);
				break;
			case 'IMG_FILTER_CONTRAST':
				imagefilter($image, constant($effectId), $spriteData['cont_val']); 		
				break;		
			case 'IMG_FILTER_BRIGHTNESS':
				imagefilter($image, constant($effectId), $spriteData['bright_val']); 
				break;				
			case 'IMG_FILTER_PIXELATE':
				imagefilter($image, constant($effectId), $spriteData['pix_val']); 
				break;
			case 'IMG_FILTER_COLORIZE':
				imagefilter($image, constant($effectId), $spriteData['cred_val'], $spriteData['cgreen_val'], $spriteData['cblue_val']); 
				break;
			case 'IMG_FILTER_SELECTIVE_BLUR':
				imagefilter($image, constant($effectId));
				break;
			case 'IMG_FILTER_GRAYSCALE_RED':
				self::imagecolorfilter_red($image);
				break;
			case 'IMG_FILTER_GRAYSCALE_YELLOW':
				self::imagecolorfilter_yellow($image);
				break;
			case 'IMG_FILTER_GRAYSCALE_BLUE':
				self::imagecolorfilter_blue($image);
				break;
			case 'IMG_FILTER_GRAYSCALE_GREEN':
				self::imagecolorfilter_green($image);
				break;
			case 'IMG_FILTER_GREYSCALE_NEGATE':
				imagefilter($image, IMG_FILTER_NEGATE);
 				imagefilter($image, IMG_FILTER_GRAYSCALE);
 				break;
 			case 'IMG_FILTER_GREYSCALE_COLORIZE' :
 				self::image_colorize($image, self::HexToRGB($spriteData['grey_col_val']));
 				break;  				
 			case 'IMG_FILTER_SCATTER' :
 				self::scatter($image, $spriteData['scatter_x_val'], $spriteData['scatter_y_val']);
 				break;
 			case 'IMG_FILTER_FLEA' :
 				self::flea_filter($image);
 				break;																
		}
	}
	
	
	/**
	* Greyscale an image, leaving the red channel untouched.
	*
	* @param  	resource $image
	* @link		http://www.php.net/manual/en/function.imagefilter.php#102966
	*/
	
	private function imagecolorfilter_red($image){ 
	    $height = imagesy($image); 
	    $width = imagesx($image); 
	    for($x=0; $x<$width; $x++){ 
	        for($y=0; $y<$height; $y++){ 
	            $rgb = ImageColorAt($image, $x, $y); 
	            $r = ($rgb >> 16) & 0xFF; 
	            $g = ($rgb >> 8) & 0xFF; 
	            $b = $rgb & 0xFF; 
	            $c=($r+$g+$b)/3; 
				if($r<$g+30 || $r<$b){$r=$c;$g=$c; $b=$c;}//only red 
				imagesetpixel($image, $x, $y,imagecolorallocate($image, $r,$g,$b)); 
	        } 
	    } 
	}
	
	/**
	* Greyscale an image, leaving the yellow channel untouched.
	*
	* @param  resource $image
	* @link		http://www.php.net/manual/en/function.imagefilter.php#102966	
	*/	
	private function imagecolorfilter_yellow($image){ 
	    $height = imagesy($image); 
	    $width = imagesx($image); 
	    for($x=0; $x<$width; $x++){ 
	        for($y=0; $y<$height; $y++){ 
	            $rgb = ImageColorAt($image, $x, $y); 
	            $r = ($rgb >> 16) & 0xFF; 
	            $g = ($rgb >> 8) & 0xFF; 
	            $b = $rgb & 0xFF; 
	            $c=($r+$g+$b)/3; 	            
				if($r<$g-1 || $r>$g+60 || $b>$g-50){$r=$c;$g=$c; $b=$c;}//only yellow 
				imagesetpixel($image, $x, $y,imagecolorallocate($image, $r,$g,$b)); 
	        } 
	    } 
	} 
	/**
	* Greyscale an image, leaving the blue channel untouched.
	*
	* @param  resource $image
	*/
	private function imagecolorfilter_blue($image){ 
	    $height = imagesy($image); 
	    $width = imagesx($image); 
	    for($x=0; $x<$width; $x++){ 
	        for($y=0; $y<$height; $y++){ 
	            $rgb = ImageColorAt($image, $x, $y); 
	            $r = ($rgb >> 16) & 0xFF; 
	            $g = ($rgb >> 8) & 0xFF; 
	            $b = $rgb & 0xFF; 
	            $c=($r+$g+$b)/3; 	            
				if($b<$r || $b<$g){$r=$c;$g=$c; $b=$c;}//only blue 
				imagesetpixel($image, $x, $y,imagecolorallocate($image, $r,$g,$b)); 
	        } 
	    } 
	}
	/**
	* Greyscale an image, leaving the green channel untouched.
	*
	* @param  resource $image
	*/	
	private function imagecolorfilter_green($image){ 
	    $height = imagesy($image); 
	    $width = imagesx($image); 
	    for($x=0; $x<$width; $x++){ 
	        for($y=0; $y<$height; $y++){ 
	            $rgb = ImageColorAt($image, $x, $y); 
	            $r = ($rgb >> 16) & 0xFF; 
	            $g = ($rgb >> 8) & 0xFF; 
	            $b = $rgb & 0xFF; 
	            $c=($r+$g+$b)/3; 	            
				if($g<$r || $g<$b+20){$r=$c;$g=$c; $b=$c;}
				imagesetpixel($image, $x, $y,imagecolorallocate($image, $r,$g,$b)); 
	        } 
	    } 
	}
	/**
	* Greyscale an image, then colourise it. Similar to a duotone.
	*
	* @param  resource	$image
	* @param  array 	$rgb Array of RGB colour values
	*/	
	private function image_colorize($image, $rgb) {	
	  imagetruecolortopalette($image, true, 256);
		$numColors = imagecolorstotal($image);
	
		for ($x = 0; $x < $numColors; $x++) {
			list($r, $g, $b) = array_values(imagecolorsforindex($image, $x));
			
			$grayscale = ($r + $g + $b) / 3 / 0xff;
			
			imagecolorset($image,$x,
				$grayscale * $rgb['r'],
				$grayscale * $rgb['g'],
				$grayscale * $rgb['b']
			);
		}
	}
	/**
	* Scatter an images pixels across either the X or Y axis
	*
	* @param  resource	$image
	* @param  integer 	$xrange	Distance in pixels to randomly offset each pixel by across x axis
	* @param  integer 	$yrange	Distance in pixels to randomly offset each pixel by across y axis
	*/		
	private function scatter($image, $xrange, $yrange) {
		$imagex = imagesx($image);
		$imagey = imagesy($image);

	    for ($x = 0; $x < $imagex; ++$x) {
	        for ($y = 0; $y < $imagey; ++$y) {
	            $distx = rand(-$xrange, $xrange);
	            $disty = rand(-$yrange, $yrange);
	
	            if ($x + $distx >= $imagex) continue;
	            if ($x + $distx < 0) continue;
	            if ($y + $disty >= $imagey) continue;
	            if ($y + $disty < 0) continue;
	
	            $oldcol = imagecolorat($image, $x, $y);
	            $newcol = imagecolorat($image, $x + $distx, $y + $disty);
	            imagesetpixel($image, $x, $y, $newcol);
	            imagesetpixel($image, $x + $distx, $y + $disty, $oldcol);
	        }
	    }
	
	}
	
	/**
	* Randomly OR image pixels. Produces a grainly slightly degraded effect.
	*
	* @param  resource	$image
	* @link  http://www.devx.com/webdev/Article/37179/1954
	*/	
	private function flea_filter($image) {
		$imagex = imagesx($image);
		$imagey = imagesy($image);
		
		for($j = 0; $j < $imagey; $j++){
	      for($i = 0; $i < $imagex; $i++){
	         $rgb = imagecolorat($image, $i, $j);
	   
	         $new_r = rand(0,255);
	         $new_g = rand(0,255);
	         $new_b = rand(0,255);
	   
	         $new_color = imagecolorallocate($image, $new_r, $new_g, $new_b); 
	         $new_rgb = $rgb|$new_color;
	         imagesetpixel($image, $i, $j, $new_rgb);    
	      }
	   }    
			
	}
	
	/**
	* Convert Hex colour to RGB
	*
	* @param  string 	$hex Hex representation of a HTML colour eg '#00ff00'
	* @param  return 	array of RGB colours
	*/		
	private function HexToRGB($hex) {
		$hex = ereg_replace("#", "", $hex);
		$color = array();
		
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
		
		return $color;
	}
	 

}


?>