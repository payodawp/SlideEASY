=== SlideEASY===
Contributors: Payoda
Donate link: 
Tags: slider, responsive, jquery slider, lightslider
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.0

A responsive slider for integrating into themes via a simple shortcode.

== Description ==

The *SliderEASY* plugin allows you to create slides that consist of linked (to any url) images and titles. The slider would then take those slides and present them as a jQuery-powered slideshow - at a chosen location within your theme, page, or post. In whatever order you want them.


The main purpose of the *SliderEASY* is to serve as an effective addition to **responsive WordPress themes**, as it would automatically adjust to its container. This would work out of the box - there is no need for additional CSS or JavaScript tweaks from your theme.

== Installation ==

1. Upload the plugin folder `responsive-slider` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the "Plugins" menu in WordPress. A new item **"SliderEASY"** would appear in the admin menu. 
1. Go to **SliderEASY -> Settings** and configure the slider options.
1. Go to **SliderEASY -> Add New Slide** and create a few slides.
1. Place `<?php echo do_shortcode( '[se_slider cat="name of the category its is mandatory"]' ); ?>` in your template - wherever you want it displayed. Alternatively you can use `[se_slider cat="name of the category"]` into a post or a page - just like any other shortcode.

1. Additional attributes 
	per_page="no of images to show"
	image_content="1" (1- show, 0-hide)
	thumb="1" (1- show, 0-hide)
	title="1"
	desc="1"
	width="0" (if we set the value. it will automatically convert to carousel slider)

1. That's it. Your site should now display the slider at the chosen location.

== Frequently Asked Questions ==

= But the slider is not responsive! =

The slider addapts to it's container width. If you are using a theme with non-responsive layout, the slider won't behave 'responsively' as well.

= Would the Responsive Slider work in my theme? =

The plugin has been tested with more than 20 popular WordPress themes. It should work in yours too.

= Does my theme need to be 'responsive' in order to use this plugin? =

No, not at all. You can use it with any theme.

= Can I change the way it looks? =

Sure, you can easily override the slider CSS in your theme. The easiest approach is to use a tool like Firebug to find the snippet you need to override. Then copy it to your theme CSS file (usually `style.css`) and edit it there, using a CSS selector with higher priority.

= Can I create more than one slider? =
yes, you can create based on category.

== Changelog ==
= 1.0 =
* Initial release.
