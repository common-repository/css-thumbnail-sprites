=== Plugin Name ===
Contributors: we_tell
Donate link: https://wetell.co.nz
Tags: featured image, sprite, css, thumbnail, image effects
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple plugin for creating sliding door CSS sprites out of uploaded images with a range of image effects.

== Description ==

The plugin creates CSS sprites from the original uploaded image, resizes to the defined dimensions and then applies the selected filter.

You can have has many different sprites as you like. Larger images (over 1000px * 1000px) will depending on your chosen filter, take longer to process.

Supports jpg, png and gif images.

There is also a [live example](http://dalesattler.net) which demonstrates a live example of the plugin in use.

== Installation ==

1. Upload `css-sprites` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. The plugin creates a menu item within the Media menu group
1. Set image sprite identifier Set the unique identifier that you will later use to reference your image within your wordpress site. Wordpress will register this as a custom image size.
Access image like so eg: `wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'image-identifier');`
1. Set sprite dimensions Specifies the sprite image size dimensions in pixels.
1. Set sprite jpg quality Set the level of jpg compression on any jpg images you upload. 100 is best quality. Default is 95.
1. Set sprite png quality Set the level of png compression for any png images you upload. 0 is best quality.
1. Set image effect on sprite Choose the image effect to apply to your CSS sprite. Depending on what option you select, further configuration options may display.
1. Set image effect position Controls the position of the image effect on the sprite. Select for the image effect to appear at the top of the sprite. Deselect for it to appear at the bottom.
1. Save Sprite Options Saves options. All options are validated.

== Frequently Asked Questions ==

= How do I access the sprite using php? =

Each sprite is registered as a custom image size. So to access that image you simply need to specify the image identifier you used when registering your sprite; Access image like so eg: 
	'wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'image-identifier');`

= How do I animate the sprite? =

You can use either JavaScript or CSS to animate your sprite.

To begin you will need to set the containing block element of your sprite to the width of your sprite and 1/2 of its height. Something like the following;

CSS:

	.css-thumb {
		width: 200px;
		height: 150px;
		overflow: hidden;
		position: relative;
	}
	
	.css-thumb img {
		position: absolute;
		top: 0;
		left: 0;
	}

Once you have your sprites within a CSS container, you need to animate the sprite, or simply change it's position. The actual positions and dimensions will depend on what you have set within the plugin.

Position change with CSS:

	.css-thumb img:hover {
		top: -150px;
	}

Animating with Javascript (jQuery):

	$('#container .css-thumb').on({
		mouseenter: function(event) {
			$('img', this).animate({'margin-top': -($(this).height()) },200);
	
		},
		mouseleave: function () {
			$('img', this).animate({'margin-top': '0' }, 500);
		}
	});

= Can I have multiple sprite sizes and effects? =

Yes, as many as you want.

== Screenshots ==

1. Over view of the interface. **(1)** Set the unique identifier that you will later use to reference your image within your wordpress site. WordPress will register this as a custom image size. **(2)** Set sprite dimensions Specifies the sprite image size dimensions in pixels. **(3)** Set sprite jpg quality Set the level of jpg compression on any jpg images you upload. 100 is best quality. Default is 95. **(4)** Set sprite png quality Set the level of png compression for any png images you upload. 0 is best quality. **(5)** Set image effect on sprite Choose the image effect to apply to your CSS sprite. Depending on what option you select, further configuration options may display. **(6)** Set image effect position Controls the position of the image effect on the sprite. Select for the image effect to appear at the top of the sprite. Deselect for it to appear at the bottom. **(7)** Remove Sprite Will remove sprite data for the selected sprite. Only actually removes content when options are saved. **(8)** Add new CSS Sprite to plugin sprite list **(9)** Save Sprite Options Saves options. All options are validated.
2. Greyscale. Applies greyscale filter to image. No configurable options.
3. Greyscale Except Red. Applies greyscale filter to image but leaves the RED channel untouched. No configurable options.
4. Greyscale Except Green. Applies greyscale filter to image but leaves the GREEN channel untouched. No configurable options.
5. Greyscale Except Blue. Applies greyscale filter to image but leaves the BLUE channel untouched. No configurable options.
6. Greyscale Except. Applies greyscale filter to image but leaves the YELLOW channel untouched. No configurable options.
7. Greyscale Colourise. Colourises a greyscale image. Set image colourisation via the colour select box, or manually enter a HTML hex colour.
8. Greyscale Photo Negative. First applies a greyscale filter and then a photo negative filter to an image. No configurable options.
9. Photo Negative. Applies a photo negative filter to an image. No configurable options.
10. Contrast. Applies a contrast filter to an image.Set contrast amount using slider. Range(-100 to 100). Default amount is: -10.
11. Colourise. Applies a colourise filter to an image. Set colourisation options via the three sliders. Value range is -255 to 255. 0 represents no change. *Tip: Use arrow keys for small adjustments.*
12. Sepia. Applies a quick sepia filter to an image. No configurable options.
13. Brightness. Applies a brightness filter to an image. Adjust brightness values using sliders. Value range is -255 to 255. 0 represents no change. *Tip: Use arrow keys for small adjustments.*.
14. Pixellate. Applies a pixellation filter to an image. Adjust pixel block size value using slider. Value range is 0 to 100. 0 represents no change. *Tip: Use arrow keys for small adjustments.*
15. Selective Blur. Applies a selective blur filter to an image. No configurable options.
16. Scatter. Applies a pixel scatter filter to an image. Adjust scatter X and Y values using sliders. Value range is 0 to 100. 0 represents no change. *Tip: Use arrow keys for small adjustments.*
17. Flea. Applies a Flea (random OR) filter to an image. No configurable options.

== Changelog ==

= 1.0 =
* Initial release

== Known Issues ==

Image upload silently fails. Occasionally an image upload will silently fail. If this occurs, try to upload the image again. This seems to be a Safari issue.

