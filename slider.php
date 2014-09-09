<?php
/**
 * Plugin Name: SlideEASY
 * Plugin URI: http://www.payoda.com
 * Description:  The plugin enables the cropped image so easy, plus the addition of links, captions and other settings.
 * Version: 1.0
 * Author: Payoda
 * Author URI: http://www.payoda.com
 * License: A "Slug" license name e.g. GPL2
 */
 
add_action( 'init', 'create_posttype' );

function create_posttype() {
	register_post_type( 'slidereasy',
		array(
			'labels' => array(
				'name' => __( 'SliderEASY' ),
				'singular_name' => __( 'Slider' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'slidereasy'),
			'supports' => array( 'title','excerpt','thumbnail' ),
		)
	);
	
	$labels = array(
	'name'              => _x( 'Categories', 'taxonomy general name' ),
	'singular_name'     => _x( 'Categories', 'taxonomy singular name' ),
	'search_items'      => __( 'Search Categories' ),
	'all_items'         => __( 'All Categories' ),
	'parent_item'       => __( 'Parent Categories' ),
	'parent_item_colon' => __( 'Parent Categories:' ),
	'edit_item'         => __( 'Edit Categories' ),
	'update_item'       => __( 'Update Categories' ),
	'add_new_item'      => __( 'Add New Categories' ),
	'new_item_name'     => __( 'New Categorie Name' ),
	'menu_name'         => __( 'Categories' ),
);

$args = array(
	'hierarchical'      => true,
	'labels'            => $labels,
	'show_ui'           => true,
	'show_admin_column' => true,
	'query_var'         => true,
	'rewrite'           => array( 'slug' => 'slide_cat' ),
);

register_taxonomy( 'slide_cat', array( 'slidereasy' ), $args );
}

/*Default slider settings*/
 
add_option( 'slide_image_width', 960 );
add_option( 'slide_image_height', 500 );
add_option( 'slide_thumb_image_width', 150 );
add_option( 'slide_thumb_image_height', 80 );
add_option( 'title_show', 1 );
add_option( 'desc_show', 1 );
add_option( 'content_bg', '#3d3d3d' );
add_option( 'title_color', '#e8e8e8' );
add_option( 'desc_color', '#828282' );
add_option( 'arw_type', 'medium' );
add_option( 'arw_show', 1 );
add_option( 'btn_type', 1 );
add_option( 'btn_color', '#545454' );
add_option( 'btn_act_color', '#000000' );
add_option( 'slider_delay', 5000 );
add_option( 'slider_animation', 800 );
add_option( 'slider_effect', 'slide' );

add_action('wp_print_scripts', 'pyda_register_scripts');
add_action('wp_print_styles', 'pyda_register_styles');

add_image_size( 'slide-thumb', get_option('slide_thumb_image_width'), get_option('slide_thumb_image_height'), true );
add_image_size( 'slide-image', get_option('slide_image_width'), get_option('slide_image_height'), true );

function pyda_register_scripts() {
    if (!is_admin()) {
        // register script
        wp_register_script('lightSlider-min', plugins_url('js/jquery.lightSlider.js', __FILE__), array( 'jquery' )); 
        // enqueue
        wp_enqueue_script('lightSlider-min');
             
    }
}
 
function pyda_register_styles() {
    // register style
    wp_register_style('lightslider_styles', plugins_url('css/lightSlider.css', __FILE__));
    // enqueue
    wp_enqueue_style('lightslider_styles');

}

function slider_func($atts , $content = null ) { 
	
	extract(shortcode_atts(
		array(
			'cat' => '5',
			'per_page' => '5',
			'width'=>'0',
			'thumb'=>'1',
			'image_content'=>'1',
			'title'=>'1',
			'desc'=>'1'
		), $atts) 
		);
		
		$rand_id = rand(0, 999); 			
	?>	
			<ul class="<?php echo $width;?>" id="imageGallery_<?php echo $cat.'_'.$rand_id;?>" style="margin:0; padding:0;">

			<?php   $args = array( 'post_type' => 'slidereasy', 'posts_per_page' => $per_page, 'slide_cat'=>$cat);
					$loop = new WP_Query( $args );
					$s=0;
					while ( $loop->have_posts() ) : $loop->the_post(); 										
					$thumb_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'slide-thumb');
					$thumb_url  = $thumb_img[0];									
			?>
					
						<li style="list-style:none; " data-thumb="<?php echo $thumb_url;?>">						
						
						<?php
							$img_slide_url=get_post_meta(get_the_ID(),'slider_image_url', true );
																					
							$img_slide_openurl=get_post_meta(get_the_ID(),'slider_image_url_openblank', true );
							
							if($img_slide_openurl!=0){ $target='_blank'; } else { $target=''; }	
							
						    if($img_slide_url!='') { ?>
						
							<a href="<?php echo $img_slide_url;?>" target="<?php echo $target;?>">
								<?php  the_post_thumbnail('slide-image');?>
							</a>
							
						<?php } else {  the_post_thumbnail('slide-image'); } ?>
							
						<?php if(get_option('title_show')==1  && get_option('desc_show')==1 && $image_content!='0' && $width=='0') { ?> 
												
							<div class="imageGalleryContent">
									
									<?php if(get_option('title_show')==1 && $title==1) { ?>
									
										<h1 class="slider_title"><?php the_title(); ?></h1>
										
									<?php } if(get_option('desc_show')==1 && $desc==1) { ?>
									
										<div class="slider_desc">
											<?php the_excerpt(); ?>
										</div>
										
									<?php } ?>
									
								</div>
								
						<?php } ?>
						
						</li>						
													 
			<?php  $s++;  endwhile; ?>		
			
			</ul>

<script>				
jQuery(document).ready(function($) {
	
	$('#imageGallery_<?php echo $cat.'_'.$rand_id;?>').lightSlider({
						
		<?php 
		
			if(get_option('thumb_show')==1 && $width=='0' && $thumb!=0) {   
										
				echo 'gallery:true, thumbWidth:'.get_option('slide_thumb_image_width').','.' thumbMargin:3,';  			  
			} 					
				echo 'maxSlide:1,minSlide:1, currentPagerPosition:"left",';
			
			if(get_option('slider_effect')=='slide_vr' ) {  echo 'vertical:true,'; } 
		
			if(get_option('slider_start')==1) {  echo 'auto: true,'; }
		 
				echo 'pause:'.get_option('slider_delay').',';
				
				echo 'speed: '.get_option('slider_animation').',';
			
			if(get_option('slider_effect')=='fade' && $width=='0') { echo 'mode:"fade",'; }  
		
			if(get_option('arw_show')==1) { echo 'controls:true,'; } else { 'controls:false,'; }  
		
			if($width!='0'){ echo 'slideWidth:'.$width.',maxSlide:3,';} else { 'maxSlide:1,'; } 
		
		?>							
	});
});
	
</script>
			
<style>						
	.csAction > a{ background-image: url('<?php echo plugins_url('img', __FILE__).'/'.get_option('arw_type').'.png';?>'); !important; }
	
	.csPager .active a{ background:<?php echo get_option('btn_act_color');?> !important; }
	
	.csPager a{ background:<?php echo get_option('btn_color');?> !important; }
	
	.slider_title { color:<?php echo get_option('title_color');?> !important; }
	
	.slider_desc, .slider_desc p { color:<?php echo get_option('desc_color');?> !important; }
	
	<?php if(get_option('btn_type')==2) { ?> .csSlideOuter .csPager.cSpg > li a { border-radius:0; }
	
	<?php } if(get_option('btn_type')==2) { ?> 
	
	.imageGalleryContent { background: <?php echo get_option('content_bg');?> !important; }
	
	<?php } ?>
	
</style>

<?php 
}
add_shortcode( 'se_slider', 'slider_func' );

add_action('admin_menu' , 'settings_slider_menu');

function settings_slider_menu() {
	add_submenu_page('edit.php?post_type=slidereasy', 'Custom Post Type Admin', 'Settings', 'edit_posts', basename(__FILE__), 'custom_function');	
}

function custom_function() {	
?>
<style>
	input[type="text"],select{
	width:250px;
	padding:7px;
	height:32px;
}
.slide_settings td, th {
    padding: 5px 0;
}
</style>
<script>
jQuery(document).ready(function($){
    $('.pyda-color-picker').wpColorPicker();
});
</script>

<div class="section panel">

  <h1>Slider Settings</h1>
	 
	<form method="post" action="options.php" enctype="multipart/form-data">
    
    <?php 
		wp_nonce_field('update-options');
		$upload_dir = wp_upload_dir(); 
	?>
	
    <table width="100%" class="slide_settings" style="text-align: left;">
	
		<tr valign="top">
            <th width="250" scope="row">Slider Image Size:</th> 
            <td>
					Width:<input type="text" style="width:80px;" name="slide_image_width" placeholder="width"  value="<?php echo get_option('slide_image_width');?>">
					Height:<input type="text" style="width:80px;" name="slide_image_height" placeholder="Height"  value="<?php echo get_option('slide_image_height');?>">
            </td>
        </tr>
                
		<tr valign="top">
            <th width="250" scope="row">Slider Thumb Image Size:</th> 
            <td>
					Width:<input type="text" style="width:80px;" name="slide_thumb_image_width" placeholder="width"   value="<?php echo get_option('slide_thumb_image_width');?>">
					Height:<input type="text" style="width:80px;"  name="slide_thumb_image_height" placeholder="Height"   value="<?php echo get_option('slide_thumb_image_height');?>">
            </td>
        </tr>
                 
         <tr valign="top">
            <th width="250" scope="row">Show Title:</th> 
            <td>
               <select name="title_show">
					<option <?php if(get_option('title_show')==1) { echo 'selected';} ?>  value="1">Yes</option>
					<option <?php if(get_option('title_show')==2) { echo 'selected';} ?>  value="2">No</option>
			   </select>
            </td>
        </tr>
                
        <tr valign="top">
            <th width="250" scope="row">Show Description:</th> 
            <td>
               <select name="desc_show">
					<option <?php if(get_option('desc_show')==1) { echo 'selected';} ?>  value="1">Yes</option>
					<option <?php if(get_option('desc_show')==2) { echo 'selected';} ?>  value="2">No</option>
			   </select>
            </td>
        </tr>
                
          <tr valign="top">
            <th width="250" scope="row">Show Thumbnail</th> 
            <td>
               <select name="thumb_show">
					<option <?php if(get_option('thumb_show')==1) { echo 'selected';} ?>  value="1">Yes</option>
					<option <?php if(get_option('thumb_show')==2) { echo 'selected';} ?>  value="2">No</option>
			   </select>
            </td>
        </tr>
        		
		<tr valign="top">
            <th width="250" scope="row">Content Background color:</th> 
            <td>
				<input type="text" value="<?php echo get_option('content_bg'); ?>" name="content_bg" class="pyda-color-picker" data-default-color="#c6c6c6" />
			</td>
        </tr>
                
        <tr valign="top">
            <th width="250" scope="row">Title Color:</th> 
            <td>
				<input type="text" value="<?php echo get_option('title_color'); ?>" name="title_color" class="pyda-color-picker" data-default-color="#c6c6c6" />
			</td>
        </tr>
		
        <tr valign="top">
            <th width="250" scope="row">Description Color:</th> 
            <td>
				<input type="text" value="<?php echo get_option('desc_color'); ?>" name="desc_color" class="pyda-color-picker" data-default-color="#c6c6c6" />
			</td>
        </tr>
		       
		<tr valign="top">
            <th width="250" scope="row">Select Arrow Type:</th>
            <td>
               <select name="arw_type">
					<option <?php if(get_option('arw_type')=='light') { echo 'selected';} ?>  value="light">Light</option>
					<option <?php if(get_option('arw_type')=='medium') { echo 'selected';} ?>  value="medium">Medium</option>
					<option <?php if(get_option('arw_type')=='dark') { echo 'selected';} ?>  value="dark">Dark</option>
			   </select>
            </td>
        </tr>
		
		<tr valign="top">
            <th width="250" scope="row">Show Arrow:</th> 
            <td>
               <select name="arw_show">
					<option <?php if(get_option('arw_show')==1) { echo 'selected';} ?>  value="1">Yes</option>
					<option <?php if(get_option('arw_show')==2) { echo 'selected';} ?>  value="2">No</option>
			   </select>
            </td>
        </tr>               
		
		 <tr valign="top">
            <th width="250" scope="row">Button Type:</th> 
            <td>
               <select name="btn_type">
					<option <?php if(get_option('btn_type')==1) { echo 'selected';} ?>  value="1">Circle</option>
					<option <?php if(get_option('btn_type')==2) { echo 'selected';} ?>  value="2">Square</option>
			   </select>
            </td>
        </tr>      
        		
		<tr valign="top">
            <th width="250" scope="row">Button Color:</th> 
            <td>
				<input type="text" value="<?php echo get_option('btn_color'); ?>" name="btn_color" class="pyda-color-picker" data-default-color="#c6c6c6" />
			</td>
        </tr>
		
		<tr valign="top">
            <th width="250" scope="row">Button Active Color:</th>
            <td>
				<input type="text" value="<?php echo get_option('btn_act_color'); ?>" name="btn_act_color" class="pyda-color-picker" data-default-color="#C41230" />
			</td>
        </tr>		
		
        <tr valign="top">
            <th width="250" scope="row">Slider Delay:</th> 
            <td>
				<input name="slider_delay" type="text" id="slider_delay" value="<?php echo get_option('slider_delay'); ?>" /> milliseconds
			</td>
        </tr>
						
        <tr valign="top">
            <th width="250" scope="row">Animation Duration:</th>
            <td>
				<input name="slider_animation" type="text" id="slider_animation" value="<?php echo get_option('slider_animation'); ?>" /> milliseconds
			</td>
        </tr>
						
        <tr valign="top">
            <th width="250" scope="row">Transition Effect:</th>
            <td>
				<select name="slider_effect" id="slider_effect">
					<option <?php if(get_option('slider_effect')=='fade') { echo 'selected';} ?> value="fade">Fade</option>
					<option <?php if(get_option('slider_effect')=='slide_hr') { echo 'selected';} ?> value="slide_hr">Slide Horizontal</option>
					<option <?php if(get_option('slider_effect')=='slide_vr') { echo 'selected';} ?> value="slide_vr">Slide Vertical</option>
				</select>
			</td>
        </tr>
				
		<tr>
			<th scope="row">Start Automatically:</th>
			<td><input type="checkbox" <?php if(get_option('slider_start')==1) { echo 'checked="checked"';} ?>  value="1" name="slider_start" id="slider_start"></td>
		</tr>
		
    </table>

    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="slide_image_width,slide_image_height,slide_thumb_image_width,slide_thumb_image_height,content_bg,title_show,desc_show,thumb_show,title_color,desc_color,arw_type,arw_show,btn_type,btn_color,btn_act_color,slider_delay,slider_animation,slider_effect,slider_start" />
    <p><input class="button button-primary" type="submit" value="<?php _e('Save Changes') ?>" /></p>
	
</form>
</div>

<?php
}
add_action( 'admin_enqueue_scripts', 'pyda_color_picker');

function pyda_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Extra field's for image URL and link

function slider_image_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$slider_image_url = isset($custom['slider_image_url']) ?  $custom['slider_image_url'][0] : '';
	$slider_image_url_openblank = isset($custom['slider_image_url_openblank']) ?  $custom['slider_image_url_openblank'][0] : '0';
	?>
	<label><?php _e('Image URL', 'cpt-bootstrap-carousel'); ?>:</label>
	<input name="slider_image_url" value="<?php echo $slider_image_url; ?>" /> <br />
	<small><em><?php _e('(optional - leave blank for no link)', 'cutom-sliders'); ?></em></small><br /><br />
	<label><input type="checkbox" name="slider_image_url_openblank" <?php if($slider_image_url_openblank == 1){ echo ' checked="checked"'; } ?> value="1" /> <?php _e('Open link in new window?', 'cutom-sliders'); ?></label>
	<?php
}

function slider_admin_init_custpost(){
	add_meta_box("slider_image_url", "Image Link URL", "slider_image_url", "slider", "side", "low");
}

add_action("add_meta_boxes", "slider_admin_init_custpost");


//insert and update meta values

function slider_mb_save_details(){
	
	global $post;
	
	if (isset($_POST["slider_image_url"])) {
		
		$openblank = 0;
		
		if(isset($_POST["slider_image_url_openblank"]) && $_POST["slider_image_url_openblank"] == '1'){
			
			$openblank = 1;
		}
		
		update_post_meta($post->ID, "slider_image_url", esc_url($_POST["slider_image_url"]));
		
		update_post_meta($post->ID, "slider_image_url_openblank", $openblank);
	}
}

add_action('save_post', 'slider_mb_save_details');


/* Plugin settings link */

function pyda_admin_action_links($links, $file) {
    static $my_plugin;
    if (!$my_plugin) {
       
        $my_plugin = plugin_basename(__FILE__);
    }
    if ($file == $my_plugin) {
		
        $settings_link = '<a href="edit.php?post_type=slider&page=slider.php">Settings</a>';
        
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'pyda_admin_action_links', 10, 2);



// Register slider widget 

class PydaSliderWidget extends WP_Widget {

	function PydaSliderWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Custom Slider Widget' );
	}

	function widget( $args, $instance ) {
		
		extract( $args );
		// these are the widget options
		$title = $instance['title'];
		$slider = $instance['slider'];

		echo $before_widget;

		echo '<h3 class="widget-title">'.$title.'</h3>';
	   
		echo do_shortcode('[se_slider per_page="10" image_content="0" thumb="0" cat="'.$slider.'"]');

		echo $after_widget;
  
	}

	function update( $new_instance, $old_instance ) {
		
		// Save widget options		
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['slider'] = strip_tags($new_instance['slider']);
	  return $instance;
	}

	function form( $instance ) {
		
		// Check values
		if( $instance) {
			  $title = esc_attr($instance['title']);
			  $slider = esc_attr($instance['slider']);
		} else {
			 $title ='';
			 $slider ='';
		}
?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>: 		

			<input type="text" value="<?php echo $title;?>" name="<?php echo $this->get_field_name('title');?>" id="<?php echo $this->get_field_name('title');?>">	
		</p>
	
		<p>
		<label for="<?php echo $this->get_field_id('slider'); ?>"><?php _e('Select Slider', 'wp_widget_plugin'); ?></label>: 		

		 <?php 
			$cat_args = array(
			'orderby'            => 'ID', 
			'name'               => $this->get_field_name('slider'),
			'class'              => 'postform',
			'selected'           =>  $slider  ,
			'taxonomy'           => 'slide_cat',
			'hide_if_empty'      => false,
			); 
		 
		?>	
	<select name="<?php echo $this->get_field_name('slider');?>" id="<?php echo $this->get_field_name('slider');?>">
	  <?php $categories = get_categories($cat_args); 
	  
		  foreach ($categories as $category) {	  
			
			if($slider==$category->name) { $selected='selected="selected"'; }else{  $selected=''; }			  
			$option = '<option value="'.$category->name.'" '.$selected.'>';
			$option .= $category->cat_name;
			$option .= '</option>';
			echo $option;
		  }
	  ?> 
		 
	</select>
	</p>
		
<?php
	}
}

function pyda_register_widgets() {
	
	register_widget( 'PydaSliderWidget' );
}

add_action( 'widgets_init', 'pyda_register_widgets' );

?>
