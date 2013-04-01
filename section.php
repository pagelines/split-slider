<?php
/*
	Section: Split Slider
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Demo: http://splitslider.ahansson.com
	Version: 1.1
	Description: Split Slider is a fully responsive slider that supports up to 15 slides with your images and custom text.
	Class Name: SplitSlider
	Workswith: main, templates
*/

/**
 * PageLines Split Slider
 *
 * @package PageLines Framework
 * @author Aleksander Hansson
 */



class SplitSlider extends PageLinesSection {

	var $default_limit = 2;

	function section_styles() {

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-slitslider-modernizer-custom', $this->base_url.'/js/modernizr.custom.slitslider.js' );

		wp_enqueue_script( 'jquery-slitslider', $this->base_url.'/js/jquery.slitslider.js' );

		wp_enqueue_script( 'jquery-ba-cond', $this->base_url.'/js/jquery.ba-cond.min.js' );


	}

	function section_head( ) {

		$speed = ( ploption( 'split_slider_speed', $this->oset ) ) ? ploption( 'split_slider_speed', $this->oset ) : '1200';

		if ( ploption( 'split_slider_autoplay', $this->oset ) == 'n' ) {
			$autoplay = 'false';
		} else {
			$autoplay = 'true';
		}

?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
			
				var Page = (function() {

					var $nav = $( '#nav-dots > span' ),
						slitslider = $( '#splitSlider' ).slitslider( {
							// transitions speed
							speed : <?php echo $speed; ?>,
							// maximum possible angle
							maxAngle : 30,
							// maximum possible scale
							maxScale : 2.0,
							// slideshow on / off
							autoplay : <?php echo $autoplay; ?>,
							onBeforeChange : function( slide, pos ) {

								$nav.removeClass( 'nav-dot-current' );
								$nav.eq( pos ).addClass( 'nav-dot-current' );

							}
						} ),

						init = function() {

							initEvents();
							
						},
						initEvents = function() {

							$nav.each( function( i ) {
							
								$( this ).on( 'click', function( event ) {
									
									var $dot = $( this );
									
									if( !slitslider.isActive() ) {

										$nav.removeClass( 'nav-dot-current' );
										$dot.addClass( 'nav-dot-current' );
									
									}
									
									slitslider.jump( i + 1 );
									return false;
								
								} );
								
							} );

						};

						return { init : init };

				})();

				Page.init();

			});
		</script>
	<?php

	}

	function section_template() {

		$slider_height = ( ploption( 'split_slider_height', $this->oset ) ) ? ploption( 'split_slider_height', $this->oset ) : '600';

		printf('<div id="splitSlider" class="sl-slider-wrapper" style="height:%spx;">', $slider_height );
		
			?>	
				<div class="sl-slider">
					<?php

						$slides = ( ploption( 'split_slider_slides', $this->oset ) ) ? ploption( 'split_slider_slides', $this->oset ) : $this->default_limit;

						$nav_dots = '';
						$output = '';
						for ( $i = 1; $i <= $slides; $i++ ) {

							if ($i % 2 == 0) {
								$orientation = 'vertical';
							} else {
								$orientation = 'horizontal';
							}

							$data_slice1_rotation = rand(-30, 30);
							$data_slice2_rotation = rand(-30, 30);
							$data_slice1_scale_num = rand(0, 2);
							$data_slice2_scale_num = rand(0, 2);
							$data_slice1_scale_dec = rand(0, 9);
							$data_slice2_scale_dec = rand(0, 9);

							if ( ploption( 'split_slider_image_'.$i, $this->oset ) || ploption( 'split_slider_content_'.$i, $this->oset ) ) {

								$the_img = ploption( 'split_slider_image_'.$i, $this->oset );

								$the_head_color = ( ploption( 'split_slider_head_color_'.$i, $this->oset ) ) ? ploption( 'split_slider_head_color_'.$i, $this->oset ) : '#ffffff' ;

								$the_head = ( ploption( 'split_slider_head_'.$i, $this->tset ) ) ? sprintf( '<h2 style="color:%s;">%s</h2>', $the_head_color, ploption( 'split_slider_head_'.$i, $this->tset ) ) : '' ;

								$the_text_color = ( ploption( 'split_slider_text_color_'.$i, $this->oset ) ) ? ploption( 'split_slider_text_color_'.$i, $this->oset ) : '#ffffff' ;

								$the_text = ( ploption( 'split_slider_text_'.$i, $this->tset ) ) ? sprintf( '<p style="color:%s;">%s</p>', $the_text_color, ploption( 'split_slider_text_'.$i, $this->tset ) ) : '' ;

								$the_subtext_color = ( ploption( 'split_slider_subtext_color_'.$i, $this->oset ) ) ? ploption( 'split_slider_subtext_color_'.$i, $this->oset ) : '#000' ;

								$the_subtext = ( ploption( 'split_slider_subtext_'.$i, $this->tset ) ) ? sprintf( '<cite style="color:%s;">%s</cite>', $the_subtext_color, ploption( 'split_slider_subtext_'.$i, $this->tset) ) :'' ;

								if ( $the_head || $the_text || $the_subtext ) {
									$text = sprintf( '%s<blockquote>%s%s</blockquote>', $the_head, $the_text, $the_subtext );
								} else {
									$text = '';
								}

								$img = ( $the_img ) ? sprintf( '<div class="bg-img" style="background-image: url(%s);">%s</div>', $the_img, $text ) : '' ;

								if ( $the_img ) {
									$output .= sprintf( '<div class="sl-slide" data-orientation="%s" data-slice1-rotation="%s" data-slice2-rotation="%s" data-slice1-scale="%s.%s" data-slice2-scale="%s.%s">%s</div>', $orientation, $data_slice1_rotation, $data_slice2_rotation, $data_slice1_scale_num, $data_slice1_scale_dec, $data_slice2_scale_num, $data_slice2_scale_dec, $img );
								} else {
									$output .='';
								}

								if ( $output ) {
									
									if ( $i == 1 ) {
										$nav_dots .= '<span class="nav-dot-current"></span>';
									} else {
										$nav_dots .= '<span></span>';
									}
								}
							}
						}

						if ( $output == '' ) {
							$this->do_defaults();
						} else {
							echo $output;
							
							?>
								
								<nav id="nav-dots" class="nav-dots">
									<?php
										echo $nav_dots;
									?>
								</nav>

							<?php
						
						}

					?>
				
				</div>

			</div>

		<?php 		
	}

	function do_defaults() {

		printf( '<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-5" data-slice2-rotation="10" data-slice1-scale="2" data-slice2-scale="1"><div class="sl-slide-inner"><div class="bg-img" style="background-image: url(%s);"></div><h2>%s</h2><blockquote><p>%s</p><cite>%s</cite></blockquote></div></div>',
			$this->base_url.'/img/1.png',
			'Are you a winner?',
			'You were born to win! But to be a winner... You must plan to win, prepare to win and expect to win!',
			'Zig Ziglar'
		);
		printf( '<div class="sl-slide" data-orientation="vertical" data-slice1-rotation="-20" data-slice2-rotation="10" data-slice1-scale="1" data-slice2-scale="1"><div class="sl-slide-inner"><div class="bg-img" style="background-image: url(%s);"></div><h2>%s</h2><blockquote><p>%s</p><cite>%s</cite></blockquote></div></div>',
			$this->base_url.'/img/2.png',
			'Where to start?',
			'The way to get started is to quit talking and begin doing!',
			'Walt Disney'
		);
		printf( '<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="3" data-slice2-rotation="3" data-slice1-scale="1.5" data-slice2-scale="2"><div class="sl-slide-inner"><div class="bg-img" style="background-image: url(%s);"></div><h2>%s</h2><blockquote><p>%s</p><cite>%s</cite></blockquote></div></div>',
			$this->base_url.'/img/3.png',
			'Following the path?',
			'Do not go where the path may lead; go instead where there is no path and leave a trail.',
			'Ralph Waldo Emerson'
		);
		printf( '<div class="sl-slide" data-orientation="vertical" data-slice1-rotation="30" data-slice2-rotation="10" data-slice1-scale="2" data-slice2-scale="1"><div class="sl-slide-inner"><div class="bg-img" style="background-image: url(%s);"></div><h2>%s</h2><blockquote><p>%s</p><cite>%s</cite></blockquote></div></div>',
			$this->base_url.'/img/4.png',
			'Learn, live, hope!',
			'Learn from yesterday, live for today, hope for tomorrow. The important thing is not to stop questioning.',
			'Albert Einstein'
		);

		?>
			<nav id="nav-dots" class="nav-dots">
				<span class="nav-dot-current"></span>
				<span></span>
				<span></span>
				<span></span>
			</nav>
		<?php
	
	}

	function section_optionator( $settings ) {
		
		$settings = wp_parse_args( $settings, $this->optionator_default );

		$array = array();

		$array['split_slider_slides'] = array(
			'type'    => 'count_select',
			'count_start' => 2,
			'count_number'  => 15,
			'default'  => '2',
			'inputlabel'  => __( 'Number of Images to Configure', 'SplitSlider' ),
			'title'   => __( 'Number of images', 'SplitSlider' ),
			'shortexp'   => __( 'Enter the number of Split Slider slides. <strong>Minimum is 2</strong>', 'SplitSlider' ),
			'exp'    => __( "This number will be used to generate slides and option setup. For best results, please use images with the same dimensions!", 'SplitSlider' ),
		);

		$array['split_slider_autoplay']  = array(
			'default'       => 'y',
			'type'           => 'select',
			'selectvalues'     => array(
				'y' => array( 'name' => __( 'Yes'   , 'SplitSlider' )),
				'n' => array( 'name' => __( 'No'   , 'SplitSlider' ))
			),
			'inputlabel'  =>  __('Autoplay Split Slider? (Default is "Yes")', 'SplitSlider'),
			'title'      => __( 'Autoplay', 'pagelines' ),
			'shortexp'      => __( 'Do you want Split Slider to autoplay?', 'SplitSlider' )
		);

		$array['split_slider_speed']  = array(
			'default'       => '',
			'type'           => 'text',
			'inputlabel'  =>  __('Speed of animation? (Default is "1200")', 'SplitSlider'),
			'title'      => __( 'Speed', 'SplitSlider' ),
			'shortexp'      => __( 'How fast should Split Slider animate?', 'SplitSlider' )
		);

		$array['split_slider_height']  = array(
			'default'       => '400',
			'type'           => 'text',
			'inputlabel'  =>  __('Height of Split Slider? (Default is "400")', 'SplitSlider'),
			'title'      => __( 'Height', 'SplitSlider' ),
			'shortexp'      => __( 'Input the height of the Split Slider', 'SplitSlider' )
		);

		global $post_ID;

		$oset = array( 'post_id' => $post_ID, 'clone_id' => $settings['clone_id'], 'type' => $settings['type'] );

		$slides = ( ploption( 'split_slider_slides', $oset ) ) ? ploption( 'split_slider_slides', $oset ) : $this->default_limit;

		for ( $i = 1; $i <= $slides; $i++ ) {

			$array['split_slider_slide_'.$i] = array(
				'type'    => 'multi_option',
				'selectvalues' => array(

					'split_slider_image_'.$i  => array(
						'inputlabel'  => __( 'Slide Image', 'SplitSlider' ),
						'type'   => 'image_upload',
						'title'   => __( 'Slide Image ', 'SplitSlider' ) . $i,
						'shortexp'   => __( 'Upload an image...', 'SplitSlider' )

					),
					'split_slider_head_'.$i  => array(
						'inputlabel' => __( 'Slide Heading', 'SplitSlider' ),
						'type'   => 'text',
						'title'   => __( 'Slide Heading ', 'SplitSlider' ) . $i,
						'shortexp'   => __( 'Add a heading text...', 'SplitSlider' )
					),
					'split_slider_head_color_'.$i  => array(
						'inputlabel' => __( 'Color', 'SplitSlider' ),
						'type'   => 'colorpicker'
					),
					'split_slider_text_'.$i  => array(
						'inputlabel' => __( 'Slide Text', 'SplitSlider' ),
						'type'   => 'text',
						'title'   => __( 'Slide Text ', 'SplitSlider' ) . $i,
						'shortexp'   => __( 'Add a text...', 'SplitSlider' )
					),
					'split_slider_text_color_'.$i  => array(
						'inputlabel' => __( 'Color', 'SplitSlider' ),
						'type'   => 'colorpicker'
					),
					'split_slider_subtext_'.$i  => array(
						'inputlabel' => __( 'Slide Subtext', 'SplitSlider' ),
						'type'   => 'text',
						'title'   => __( 'Slide Subtext ', 'SplitSlider' ) . $i,
						'shortexp'   => __( 'Add a subtext...', 'SplitSlider' )
					),
					'split_slider_subtext_color_'.$i  => array(
						'inputlabel' => __( 'Color', 'SplitSlider' ),
						'type'   => 'colorpicker'
					),
				),
				'title'   => __( 'Split Slider Slide ', 'SplitSlider' ) . $i,
				'shortexp'   => __( 'Setup options for slide number ', 'SplitSlider' ) . $i,
				'exp'   => __( 'For best results all images in the slider should have the same dimensions.', 'SplitSlider' )
			);

		}

		$metatab_settings = array(
			'id'   => 'split_slider_options',
			'name'   => __( 'Split Slider', 'SplitSlider' ),
			'icon'   => $this->icon,
			'clone_id' => $settings['clone_id'],
			'active' => $settings['active']
		);

		register_metatab( $metatab_settings, $array );

	}

}
