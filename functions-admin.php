<?php 

/* Customizer additions */

/**
 * Add logo upload in theme customizer screen.
 *
 * @since 1.0
 */
function compete_themes_customize_register_logo( $wp_customize ) {

	/* Add the layout section. */
	$wp_customize->add_section(
		'ct-upload',
		array(
			'title'      => esc_html__( 'Logo', 'compete_themes_replace_me' ),
			'priority'   => 30,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'logo' setting. */
	$wp_customize->add_setting(
		'logo_upload',
		array(
			'default'           => '',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image',
				array(
					'label'    => esc_html__( 'Upload custom logo.', 'compete_themes_replace_me' ),
					'section'  => 'ct-upload',
					'settings' => 'logo_upload',
			)
		)
	);
}
add_action( 'customize_register', 'compete_themes_customize_register_logo' );

/* creates array used to get social media site names */
function compete_themes_customizer_social_media_array() {

	// store social site names in array
	$social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram');
	
	return $social_sites;
}

/* add settings to create various social media text areas */
function compete_themes_add_social_sites_customizer($wp_customize) {

    /* create custom control for url input so http:// is automatically added */
    class compete_themes_url_input_control extends WP_Customize_Control {
        public $type = 'url';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="url" <?php $this->link(); ?> value="<?php echo esc_url_raw( $this->value() ); ?>" />
            </label>
        <?php
        }
    }
	$wp_customize->add_section( 'compete_themes_social_settings', array(
			'title'          => 'Social Media Icons',
			'priority'       => 35,
            'description'    => 'remember to add "http://" before each URL'
	) );
		
	$social_sites = compete_themes_customizer_social_media_array();
	$priority = 5;
	
	foreach($social_sites as $social_site) {

		$wp_customize->add_setting( "$social_site", array(
                'type'              => 'theme_mod',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
		) );

        $wp_customize->add_control(
            new compete_themes_url_input_control(
                $wp_customize, $social_site, array(
                    'label'   => __( "$social_site url:", 'portfolio' ),
                    'section' => 'compete_themes_social_settings',
                    'priority'=> $priority,
                )
            )
        );
	
		$priority = $priority + 5;
	}
}
add_action('customize_register', 'compete_themes_add_social_sites_customizer');

/* lets users change the colors of the theme */
function compete_themes_custom_colors($wp_customize) {

    /* Add the color section. */
    $wp_customize->add_section(
        'ct-colors',
        array(
            'title'      => esc_html__( 'Colors', 'compete_themes_replace_me' ),
            'priority'   => 60,
            'capability' => 'edit_theme_options'
        )
    );
    /* Add the color setting. */
    $wp_customize->add_setting(
        'compete_themes_primary_color',
        array(
            'default'           => '#e5e5e5',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            //'transport'         => 'postMessage'
        )
    );
    /* add the color picker */
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_color',
            array(
                'label'      => __( 'Primary Color', 'compete_themes_replace_me' ),
                'section'    => 'ct-colors',
                'settings'   => 'compete_themes_primary_color',
            ) )
    );

    /* Add the color setting. */
    $wp_customize->add_setting(
        'compete_themes_secondary_color',
        array(
            'default'           => '#333333',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            //'transport'         => 'postMessage'
        )
    );
    /* add the color picker */
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'secondary_color',
            array(
                'label'      => __( 'Secondary Color', 'compete_themes_replace_me' ),
                'section'    => 'ct-colors',
                'settings'   => 'compete_themes_secondary_color',
            ) )
    );
}
add_action('customize_register', 'compete_themes_custom_colors');

function compete_themes_customize_layout_options( $wp_customize ) {

    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-layout',
        array(
            'title'      => esc_html__( 'Layout', 'compete_themes_replace_me' ),
            'priority'   => 70,
            'capability' => 'edit_theme_options'
        )
    );
    /* Add the color setting. */
    $wp_customize->add_setting(
        'compete_themes_layout_settings',
        array(
            'default'           => 'right',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'compete_themes_sanitize_layout_settings',
            //'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
            'compete_themes_sidebar_layout',
            array(
                'label'          => __( 'Sidebar on left or right?', 'compete_themes_replace_me' ),
                'section'        => 'ct-layout',
                'settings'       => 'compete_themes_layout_settings',
                'type'           => 'radio',
                'choices'        => array(
                    'right'   => 'Right',
                    'left'  => 'Left'
                )
            )
    );
}
add_action( 'customize_register', 'compete_themes_customize_layout_options' );

function compete_themes_sanitize_layout_settings($input){
    $valid = array(
        'right' => 'Right',
        'left' => 'Left'
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/* Custom meta boxes here */

/* creates meta box for image credit above the featured image box */
function compete_themes_image_credit_meta_box( $meta_boxes ) {
    $prefix = 'ct-';
    $meta_boxes[] = array(
		'id'         => 'image-credit-meta-box',
		'title'      => 'Image Credit Link',
		'pages'      => array( 'post', ), // Post type
		'context'    => 'side',
		'priority'   => 'low',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
            array(
				'name' => 'URL:',
				'desc' => '(Optional) Where did you find the featured image?',
				'id'   => $prefix . 'image-credit-link',
				'type' => 'text_medium',
			)
        )
    );

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'compete_themes_image_credit_meta_box' );

/* creates array used to get social media site names */
function compete_themes_create_social_array() {

	$social_sites = array(
		'twitter' => 'twitter_profile',
		'facebook' => 'facebook_profile',
		'googleplus' => 'googleplus_profile',
		'pinterest' => 'pinterest_profile',
		'linkedin' => 'linkedin_profile',
		'youtube' => 'youtube_profile',
		'vimeo' => 'vimeo_profile',
		'tumblr' => 'tumblr_profile',
		'instagram' => 'instagram_profile',
		'flickr' => 'flickr_profile',
		'dribbble' => 'dribbble_profile',
        'RSS' => 'rss_profile'
	);
	
	return $social_sites;
}

/* add the social profile boxes to the user screen. */
function compete_themes_add_social_profile_settings($user) {
	
	$social_sites = compete_themes_create_social_array();
	
	?>	
    <table class="form-table">
        <tr>
            <th><h3>Social Profiles</h3></th>
        </tr>
        <?php
        	foreach($social_sites as $key => $social_site) {
  				?>      	
        		<tr>
					<td>
						<label for="<?php echo $key; ?>-profile"><?php echo ucfirst($key); ?> Profile:</label>
					</td>
					<td>
						<input type='url' id='<?php echo $key; ?>-profile' class='regular-text' name='<?php echo $key; ?>-profile' value='<?php echo esc_url_raw(get_the_author_meta($social_site, $user->ID )); ?>' />
					</td>
					</td>
				</tr>
        	<?php }	?>
    </table>
    <?php
}
add_action( 'show_user_profile', 'compete_themes_add_social_profile_settings' );

function compete_themes_save_social_profiles($user_id) {

	$social_sites = compete_themes_create_social_array();
   	
   	foreach ($social_sites as $key => $social_site) {
		if( isset( $_POST["$key-profile"] ) ){
			update_user_meta( $user_id, $social_site, esc_url_raw( $_POST["$key-profile"] ) );
		}
	}
}

add_action( 'personal_options_update', 'compete_themes_save_social_profiles' );

// adds widget that aside uses to give people access to support
function compete_themes_add_dashboard_widget() {

	wp_add_dashboard_widget(
                 'compete_themes_dashboard_widget',    // Widget slug.
                 'Support Dashboard',   // Title.
                 'compete_themes_widget_contents' 	  // Display function.
        );	
        
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
 	global $wp_meta_boxes;
 	
 	// Get the regular dashboard widgets array 
 	// (which has our new widget already but at the end)
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete our new dashboard widget from the end of the array
 	$example_widget_backup = array( 'compete_themes_dashboard_widget' => $normal_dashboard['compete_themes_dashboard_widget'] );
 	unset( $normal_dashboard['compete_themes_dashboard_widget'] );
 
 	// Merge the two arrays together so our widget is at the beginning
 	$sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'compete_themes_add_dashboard_widget' );

// outputs contents for widget created by aside_add_dashboard_widget
function compete_themes_widget_contents() { ?>

    <p>If you need support <a target='_blank' href='http://competethemes.com/documentation'>visit the documentation</a> or contact support at support@competethemes.com for assistance.</p>
    <p>Please contact us before leaving a review - we can help you!</p>
	
	<?php
} 



?>