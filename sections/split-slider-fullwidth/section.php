<?php
/*
	Section: Split Slider Full Width
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Demo: http://splitslider.ahansson.com
	Description: Split Slider is a fully responsive slider that supports up to 15 slides with your images and custom text.
	Class Name: SplitSlider
	V3: true
	Filter: full-width, slider
*/

class SplitSlider extends PageLinesSection {

	function section_styles( ) {

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-slitslider-modernizer-custom', $this->base_url.'/js/modernizr.custom.slitslider.js' );

		wp_enqueue_script( 'jquery-slitslider', $this->base_url.'/js/jquery.slitslider.js' );

		wp_enqueue_script( 'jquery-ba-cond', $this->base_url.'/js/jquery.ba-cond.min.js' );

	}

	function section_head( ) {

		$clone_id = $this->get_the_id();

		$prefix = ($clone_id) ? '-clone-'.$clone_id : '';

		$speed = ( $this->opt( 'split_slider_speed' ) ) ? $this->opt( 'split_slider_speed' ) : '1200';

		if ( $this->opt( 'split_slider_autoplay' ) == 'n' ) {
			$autoplay = 'false';
		} else {
			$autoplay = 'true';
		}

		?>
			<script type="text/javascript">
				jQuery(document).ready(function(){

					var Page = (function() {

						var nav = jQuery( '#nav-dots<?php echo $prefix; ?> > span' ),
							slitslider = jQuery( '#splitSlider<?php echo $prefix; ?>' ).slitslider( {
								// transitions speed
								speed : <?php echo $speed; ?>,
								// maximum possible angle
								maxAngle : 30,
								// maximum possible scale
								maxScale : 2.0,
								// slideshow on / off
								autoplay : <?php echo $autoplay; ?>,
								onBeforeChange : function( slide, pos ) {

									nav.removeClass( 'nav-dot-current' );
									nav.eq( pos ).addClass( 'nav-dot-current' );

								}
							} ),

							init = function() {

								initEvents();

							},
							initEvents = function() {

								nav.each( function( i ) {

									jQuery( this ).on( 'click', function( event ) {

										var dot = jQuery( this );

										if( !slitslider.isActive() ) {

											nav.removeClass( 'nav-dot-current' );
											dot.addClass( 'nav-dot-current' );

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

		$clone_id = $this->get_the_id();

		$prefix = ($clone_id) ? '-clone-'.$clone_id : '';

		$slider_height = ( $this->opt( 'split_slider_height' ) ) ? $this->opt( 'split_slider_height' ) : '600';

		$split_slider_array = $this->opt('split_slider_array');

		$format_upgrade_mapping = array(
			'image'	=> 'split_slider_image_%s',
			'head'	=> 'split_slider_head_%s',
			'head_clor'	=> 'split_slider_head_color_%s',
			'text'	=> 'split_slider_text_%s',
			'text_color'	=> 'split_slider_text_color_%s',
			'subtext'	=> 'split_slider_subtext_%s',
			'subtext_color'	=> 'split_slider_subtext_color_%s'
		);

		$split_slider_array = $this->upgrade_to_array_format( 'split_slider_array', $split_slider_array, $format_upgrade_mapping, $this->opt('split_slider_slides'));

		// must come after upgrade
		if( !$split_slider_array || $split_slider_array == 'false' || !is_array($split_slider_array) ){
			$better_iboxes_array = array( array(), array(), array() );
		}

		printf('<div id="splitSlider%s" class="sl-slider-wrapper" style="height:%spx;">',$prefix, $slider_height );

			?>
				<div class="sl-slider">
					<?php

						$nav_dots = '';
						$output = '';

						$i = 1;

						if( is_array($split_slider_array) ){

							$boxes = count( $split_slider_array );

							foreach( $split_slider_array as $slide ){

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

								if ( pl_array_get( 'image', $slide ) ) {

									$the_img = pl_array_get( 'image', $slide );

									$the_head_color = ( pl_array_get( 'head_color', $slide ) ) ? pl_array_get( 'head_color', $slide ) : '#ffffff' ;

									$the_head = ( pl_array_get( 'head', $slide ) ) ? sprintf( '<h2 data-sync="split_slider_array_item%s_head" style="color:%s;">%s</h2>', $i, $the_head_color, pl_array_get( 'head', $slide ) ) : '' ;

									$the_text_color = ( pl_array_get( 'text_color', $slide ) ) ? pl_hashify( pl_array_get( 'text_color', $slide ) ) : '#ffffff' ;

									$the_text = ( pl_array_get( 'text', $slide ) ) ? sprintf( '<p data-sync="split_slider_array_item%s_text" style="color:%s;">%s</p>', $i, $the_text_color, pl_array_get( 'text', $slide ) ) : '' ;

									$the_subtext_color = ( pl_array_get( 'subtext_color', $slide ) ) ? pl_hashify( pl_array_get( 'subtext_color', $slide ) ) : '#000' ;

									$the_subtext = ( pl_array_get( 'subtext', $slide ) ) ? sprintf( '<cite data-sync="split_slider_array_item%s_subtext" style="color:%s;">%s</cite>', $i, $the_subtext_color, pl_array_get( 'subtext', $slide )) :'' ;

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

								$i++;

							}
						}

						if ( $output == '' ) {
							$this->do_defaults();
						} else {
							echo $output;

								printf('<nav id="nav-dots%s" class="nav-dots">', $prefix);
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

		$clone_id = $this->get_the_id();

		$prefix = ($clone_id) ? '-clone-'.$clone_id : '';

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
			<nav id="nav-dots<?php echo $prefix; ?>" class="nav-dots">
				<span class="nav-dot-current"></span>
				<span></span>
				<span></span>
				<span></span>
			</nav>
		<?php

	}

	function section_opts(){

		$options = array();

		$how_to_use = __( '
		<strong>Read the instructions below before asking for additional help:</strong>
		</br></br>
		<strong>1.</strong> In the frontend editor, drag the Split Slider section to a template of your choice.
		</br></br>
		<strong>2.</strong> Edit settings for Split Slider.
		</br></br>
		<strong>3.</strong> When you are done, hit "Publish" to see changes.
		</br></br>
		<div class="row zmb">
				<div class="span6 tac zmb">
					<a class="btn btn-info" href="http://forum.pagelines.com/71-products-by-aleksander-hansson/" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-ambulance"></i>          Forum</a>
				</div>
				<div class="span6 tac zmb">
					<a class="btn btn-info" href="http://betterdms.com" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-align-justify"></i>          Better DMS</a>
				</div>
			</div>
			<div class="row zmb" style="margin-top:4px;">
				<div class="span12 tac zmb">
					<a class="btn btn-success" href="http://shop.ahansson.com" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-shopping-cart" ></i>          My Shop</a>
				</div>
			</div>
		', 'split-slider' );

		$options[] = array(
			'key' => 'split_slider_help',
			'type'     => 'template',
			'template'      => do_shortcode( $how_to_use ),
			'title' =>__( 'How to use:', 'split-slider' ) ,
		);

		$options[] = array(
			'key' => 'split_slider_settings',
			'type'  => 'multi',
			'title'  => __( 'Settings', 'split-slider' ),
			'opts' => array(
				array(
					'key' => 'split_slider_autoplay',
					'default'    => 'y',
					'type'      => 'select',
					'opts'   => array(
						'y' => array( 'name' => __( 'Yes'  , 'split-slider' )),
						'n' => array( 'name' => __( 'No'  , 'split-slider' ))
					),
					'label' => __('Autoplay Split Slider? (Default is "Yes")', 'split-slider'),
					'help'   => __( 'Do you want Split Slider to autoplay?', 'split-slider' )
				),

				array(
					'key' => 'split_slider_speed',
					'default'    => '',
					'type'      => 'text',
					'label' => __('Speed of animation? (Default is "1200")', 'split-slider'),
					'help'   => __( 'How fast should Split Slider animate?', 'split-slider' )
				),

				array(
					'key' => 'split_slider_height',
					'default'    => '400',
					'type'      => 'text',
					'label' => __('Height of Split Slider? (Default is "400")', 'split-slider'),
					'help'   => __( 'Input the height of the Split Slider', 'split-slider' )
				),
			),

		);

		$options[] = array(
			'key'		=> 'split_slider_array',
	    	'type'		=> 'accordion',
			'title'		=> __('Split Slider Setup', 'better-iboxes'),
			'post_type'	=> __('Slide', 'better-iboxes'),
			'opts'	=> array(
				array(
					'key' => 'image',
					'label' => __( 'Slide Image', 'split-slider' ),
					'type'  => 'image_upload',
					'help'  => __( 'Upload an image...', 'split-slider' )
				),
				array(
					'key' => 'head',
					'label' => __( 'Slide Heading', 'split-slider' ),
					'type'  => 'text',
				),
				array(
					'key' => 'head_color',
					'label' => __( 'Color', 'split-slider' ),
					'type'  => 'color',
					'help'  => __( 'Setup Slide Heading', 'split-slider' )
				),
				array(
					'key' => 'text',
					'label' => __( 'Slide Text', 'split-slider' ),
					'type'  => 'text',
					'title'  => __( 'Slide Text ', 'split-slider' ),
				),
				array(
					'key' => 'text_color',
					'label' => __( 'Color', 'split-slider' ),
					'type'  => 'color',
					'help'  => __( 'Setup Slide Text', 'split-slider' )

				),
				array(
					'key' => 'subtext',
					'label' => __( 'Slide Subtext', 'split-slider' ),
					'type'  => 'text',
					'title'  => __( 'Slide Subtext ', 'split-slider' ),
				),
				array(
					'key' => 'subtext_color',
					'label' => __( 'Color', 'split-slider' ),
					'type'  => 'color',
					'help'  => __( 'Setup Slide Subtext', 'split-slider' )
				),
			)
		);

		return $options;
	}

}
