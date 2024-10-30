/*	JS
*	Copyright 2012 wetell.co.nz.
*	Version 1
*	This file contains methods for css sprites admin interface
*/

var wt_css = (function($){
	"use strict";
	
	/* PRIVATE INIT METHOD - SET UP EVENT LISTENERS
	-------------------------------------------------------------- */	
	function _init() {
		$('body').on('click', '.remove', function() {
			$(this).parents('.sprite-table').remove();
			return false;
		});
		
		_do_slider_setup();
		_do_colour_pick_setup();
		
		$("select").live('change', function() {
		
			var selText = $("option:selected", this).val();
			var parentEl = $(this).parents('.sprite-table');
			
			switch (selText) {
				case 'IMG_FILTER_GRAYSCALE':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_SELECTIVE_BLUR':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GRAYSCALE_RED':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GRAYSCALE_GREEN':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GRAYSCALE_BLUE':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GRAYSCALE_YELLOW':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GREYSCALE_NEGATE':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_GREYSCALE_COLORIZE':
					$(".img-adjust", parentEl).hide();
					$(".grey-colour", parentEl).show();
					break;
				case 'IMG_FILTER_NEGATE':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_FLEA':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_COLORIZE':
					$(".img-adjust", parentEl).hide();
					$(".colourise", parentEl).show();
					break;
				case 'IMG_FILTER_SEPIA':
					$(".img-adjust", parentEl).hide();
					break;
				case 'IMG_FILTER_CONTRAST':
					$(".img-adjust", parentEl).hide();
					$(".contrast", parentEl).show();
					break;
				case 'IMG_FILTER_PIXELATE':
					$(".img-adjust", parentEl).hide();
					$(".pixel", parentEl).show();
					break;
				case 'IMG_FILTER_BRIGHTNESS':
					$(".img-adjust", parentEl).hide();
					$(".brightness", parentEl).show();
					break;
				case 'IMG_FILTER_SCATTER':
					$(".img-adjust", parentEl).hide();
					$(".scatter", parentEl).show();
					break;
			}
			
		}).trigger('change');
		

	}
	
	/* PRIVATE CONFIG SLIDERS - CALLED WITH EACH NEW SPRITE ADDITION
	-------------------------------------------------------------- */
	function _do_slider_setup() {
		$(".slider").each(function() {
			var value = parseInt($(this).text(), 10);
			if (!isNaN(value)) {
				var target = $(this).next(":hidden");
				var outPut = $(this).nextAll('label');
				var initText = outPut.text();
				$(this).empty().slider({
					value: value,
					min: parseInt($(this).attr('data-min'), 10),
					max: parseInt($(this).attr('data-max'), 10),
					slide: function(event, ui) {
						$(target).val(ui.value);
						$(outPut).text(initText + ui.value);
					}
				});
				$(outPut).text(initText + value);
			}
		});
	}
	
	/* PRIVATE COLOUR PICKER INIT METHOD - CALLED WITH EACH NEW SPRITE ADDITION
	-------------------------------------------------------------- */
	function _do_colour_pick_setup() {
		if ($('.colorpicker').length) {
			$(".colorpicker").each(function() {
				$(this).hide();
				$(this).farbtastic($(this).next('input'));
				if (!$('.color-picker-title', this).length) {
					$(this).prepend('<div class="color-picker-title"><strong>Choose a colour</strong></div>');
				}
			});
			
			$(".colour-input").live('click', function() {
				$('.colorpicker').hide();
				$(this).prev('.colorpicker').fadeIn();
				return false;
			});
		}
		$(document).mousedown(function() {
			if ($('.colorpicker').length) {
				$('.colorpicker').each(function() {
					var display = $(this).css('display');
					if (display === 'block') $(this).fadeOut();
				});
			}
		});
	}
	
	/* PUBLIC ACCESSOR METHODS
	-------------------------------------------------------------- */
	return {
		init:			_init,
		config_sliders: _do_slider_setup,
		config_colour:	_do_colour_pick_setup
	};

})(jQuery);


// Initialise
jQuery(document).ready(function($){
	"use strict";
	wt_css.init();
});
