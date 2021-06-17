<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', 'lalita_background_setup' );
/**
 * Overwrite parent theme background defaults and registers support for WordPress features.
 *
 */
function lalita_background_setup() {
	add_theme_support( "custom-background",
		array(
			'default-color' 		 => '111111',
			'default-image'          => get_stylesheet_directory_uri().'/img/shudra-bg.gif',
			'default-repeat'         => 'repeat',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-attachment'     => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		)
	);
}

/**
 * Overwrite theme URL
 *
 */
function lalita_theme_uri_link() {
	return 'https://wpkoi.com/shudra-wpkoi-wordpress-theme/';
}

/**
 * Overwrite parent theme's blog header function
 *
 */
add_action( 'lalita_after_header', 'lalita_blog_header_image', 11 );
function lalita_blog_header_image() {

	if ( ( is_front_page() && is_home() ) || ( is_home() ) ) { 
		$blog_header_image 			=  lalita_get_setting( 'blog_header_image' ); 
		$blog_header_title 			=  lalita_get_setting( 'blog_header_title' ); 
		$blog_header_text 			=  lalita_get_setting( 'blog_header_text' ); 
		$blog_header_button_text 	=  lalita_get_setting( 'blog_header_button_text' ); 
		$blog_header_button_url 	=  lalita_get_setting( 'blog_header_button_url' ); 
		if ( $blog_header_image != '' ) { ?>
		<div class="page-header-image grid-parent page-header-blog" style="background-image: url('<?php echo esc_url($blog_header_image); ?>') !important;">
        	<div class="page-header-noiseoverlay"></div>
        	<div class="page-header-blog-inner">
                <div class="page-header-blog-content-h grid-container">
                    <div class="page-header-blog-content">
                    <?php if ( $blog_header_title != '' ) { ?>
                        <div class="page-header-blog-text">
                            <?php if ( $blog_header_title != '' ) { ?>
                            <h2><?php echo wp_kses_post( $blog_header_title ); ?></h2>
                            <div class="clearfix"></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="page-header-blog-content page-header-blog-content-b">
                	<?php if ( $blog_header_text != '' ) { ?>
                	<div class="page-header-blog-text">
						<?php if ( $blog_header_title != '' ) { ?>
                        <p><?php echo wp_kses_post( $blog_header_text ); ?></p>
                        <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="page-header-blog-button">
                        <?php if ( $blog_header_button_text != '' ) { ?>
                        <a class="read-more button" href="<?php echo esc_url( $blog_header_button_url ); ?>"><?php echo esc_html( $blog_header_button_text ); ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'shudra_remove_parent_dynamic_css' ) ) {
	add_action( 'init', 'shudra_remove_parent_dynamic_css' );
	/**
	 * The dynamic styles of the parent theme added inline to the parent stylesheet.
	 * For the customizer functions it is better to enqueue after the child theme stylesheet.
	 */
	function shudra_remove_parent_dynamic_css() {
		remove_action( 'wp_enqueue_scripts', 'lalita_enqueue_dynamic_css', 50 );
	}
}

if ( ! function_exists( 'shudra_enqueue_parent_dynamic_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'shudra_enqueue_parent_dynamic_css', 50 );
	/**
	 * Enqueue this CSS after the child stylesheet, not after the parent stylesheet.
	 *
	 */
	function shudra_enqueue_parent_dynamic_css() {
		$css = lalita_base_css() . lalita_font_css() . lalita_advanced_css() . lalita_spacing_css() . lalita_no_cache_dynamic_css();

		// escaped secure before in parent theme
		wp_add_inline_style( 'lalita-child', $css );
	}
}

// Extra cutomizer functions
if ( ! function_exists( 'shudra_customize_register' ) ) {
	add_action( 'customize_register', 'shudra_customize_register' );
	function shudra_customize_register( $wp_customize ) {
				
		// Add Shudra customizer section
		$wp_customize->add_section(
			'shudra_layout_effects',
			array(
				'title' => __( 'Shudra Effects', 'shudra' ),
				'priority' => 1,
				'panel' => 'lalita_layout_panel'
			)
		);
		
		
		// Navigation effect
		$wp_customize->add_setting(
			'shudra_settings[nav_effect]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'shudra_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'shudra_settings[nav_effect]',
			array(
				'type' => 'select',
				'label' => __( 'Shudra navigation effect', 'shudra' ),
				'choices' => array(
					'enable' => __( 'Enable', 'shudra' ),
					'disable' => __( 'Disable', 'shudra' )
				),
				'settings' => 'shudra_settings[nav_effect]',
				'section' => 'shudra_layout_effects',
				'priority' => 1
			)
		);
		
		// Blog image effect
		$wp_customize->add_setting(
			'shudra_settings[img_effect]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'shudra_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'shudra_settings[img_effect]',
			array(
				'type' => 'select',
				'label' => __( 'Blog image effect', 'shudra' ),
				'choices' => array(
					'enable' => __( 'Enable', 'shudra' ),
					'disable' => __( 'Disable', 'shudra' )
				),
				'settings' => 'shudra_settings[img_effect]',
				'section' => 'shudra_layout_effects',
				'priority' => 2
			)
		);
		
		// Nicescroll
		$wp_customize->add_setting(
			'shudra_settings[nicescroll]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'shudra_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'shudra_settings[nicescroll]',
			array(
				'type' => 'select',
				'label' => __( 'Scrollbar style', 'shudra' ),
				'choices' => array(
					'enable' => __( 'Enable', 'shudra' ),
					'disable' => __( 'Disable', 'shudra' )
				),
				'settings' => 'shudra_settings[nicescroll]',
				'section' => 'shudra_layout_effects',
				'priority' => 20
			)
		);
		
		// Cursor
		$wp_customize->add_setting(
			'shudra_settings[cursor]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'shudra_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'shudra_settings[cursor]',
			array(
				'type' => 'select',
				'label' => __( 'Cursor image', 'shudra' ),
				'choices' => array(
					'enable' => __( 'Enable', 'shudra' ),
					'disable' => __( 'Disable', 'shudra' )
				),
				'settings' => 'shudra_settings[cursor]',
				'section' => 'shudra_layout_effects',
				'priority' => 20
			)
		);
		
		// Shudra button
		$wp_customize->add_setting(
			'shudra_settings[shudra_button]',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'shudra_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'shudra_settings[shudra_button]',
			array(
				'type' => 'select',
				'label' => __( 'Shudra button', 'shudra' ),
				'choices' => array(
					'enable' => __( 'Enable', 'shudra' ),
					'disable' => __( 'Disable', 'shudra' )
				),
				'settings' => 'shudra_settings[shudra_button]',
				'section' => 'shudra_layout_effects',
				'priority' => 21
			)
		);
		
	}
}

if ( ! function_exists( 'shudra_sanitize_choices' ) ) {
	/**
	 * Sanitize choices.
	 *
	 */
	function shudra_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

if ( ! function_exists( 'shudra_body_classes' ) ) {
	add_filter( 'body_class', 'shudra_body_classes' );
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 */
	function shudra_body_classes( $classes ) {
		// Get Customizer settings
		$shudra_settings = get_option( 'shudra_settings' );
		
		$img_effect  = 'enable';
		$nicescroll  = 'enable';
		$cursor		 = 'enable';
		$shudra_button = 'enable';
		if ( isset( $shudra_settings['img_effect'] ) ) {
			$img_effect = $shudra_settings['img_effect'];
		}
		if ( isset( $shudra_settings['nicescroll'] ) ) {
			$nicescroll = $shudra_settings['nicescroll'];
		}
		if ( isset( $shudra_settings['cursor'] ) ) {
			$cursor = $shudra_settings['cursor'];
		}
		if ( isset( $shudra_settings['shudra_button'] ) ) {
			$shudra_button = $shudra_settings['shudra_button'];
		}
		
		// Blog image function
		if ( $img_effect != 'disable' ) {
			$classes[] = 'shudra-img-effect';
		}
		
		
		// Scrollbar style function
		if ( $nicescroll != 'disable' ) {
			$classes[] = 'shudra-scrollbar-style';
		}
		
		// Mouse style function
		if ( $cursor != 'disable' ) {
			$classes[] = 'shudra-cursor-style';
		}
		
		// Logo effect
		if ( $shudra_button != 'disable' ) {
			$classes[] = 'shudra-button';
		}
		
		return $classes;
	}
}


if ( ! function_exists( 'shudra_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'shudra_scripts' );
	/**
	 * Enqueue script
	 */
	function shudra_scripts() {
		
		$shudra_settings = get_option( 'shudra_settings' );
		
		$nav_effect  = 'enable';
		$cursor		 = 'enable';
		if ( isset( $shudra_settings['nav_effect'] ) ) {
			$nav_effect = $shudra_settings['nav_effect'];
		}
		if ( isset( $shudra_settings['cursor'] ) ) {
			$cursor = $shudra_settings['cursor'];
		}
		
		if ( $cursor != 'disable' ) {
			wp_enqueue_style( 'shudra-magic-mouse', esc_url( get_stylesheet_directory_uri() ) . "/css/magic-mouse.min.css", false, LALITA_VERSION, 'all' );
			wp_enqueue_script( 'shudra-magic-mouse', esc_url( get_stylesheet_directory_uri() ) . "/js/magic-mouse.min.js", array( 'jquery'), LALITA_VERSION, true );
		}
		
		if ( $nav_effect != 'disable' ) {
			wp_enqueue_style( 'shudra-splitting-css', esc_url( get_stylesheet_directory_uri() ) . "/css/splitting.min.css", false, LALITA_VERSION, 'all' );
			wp_enqueue_script( 'shudra-splitting-js', esc_url( get_stylesheet_directory_uri() ) . "/js/splitting.min.js", array( 'jquery'), LALITA_VERSION, true );
		}
		
	}
}

// Overwrite parent theme function for post images
add_action( 'lalita_after_entry_header', 'lalita_post_image' );
/**
 * Prints the Post Image to post excerpts
 */
function lalita_post_image() {
	// If there's no featured image, return.
	if ( ! has_post_thumbnail() ) {
		return;
	}
	
	$shudra_settings = get_option( 'shudra_settings' );
		
	$img_effect  = 'enable';
	if ( isset( $shudra_settings['img_effect'] ) ) {
		$img_effect = $shudra_settings['img_effect'];
	}

	// If we're not on any single post/page or the 404 template, we must be showing excerpts.
	if ( ! is_singular() && ! is_404() ) {
		echo '<div class="post-image"><a href="' . esc_url( get_permalink() ) . '">';
		if ( $img_effect != 'disable' ) {
			echo '<div class="shudra-img-effect-holder">';
		}
		echo get_the_post_thumbnail(
			get_the_ID(),
			apply_filters( 'lalita_pageheader_default_size', 'full' ),
			array(
				'itemprop' => 'image',
			)
		);
		if ( $img_effect != 'disable' ) {
			echo '<div class="shudra-img-effect-layer"></div></div>';
		}
		echo '</a>
			</div>';
	}
}