<?php

/**
 *
 * @link              https://risbl.co
 * @since             0.01
 *
 * @wordpress-plugin
 * Plugin Name:       Multilangual Crelly Slider for Sydney Pro
 * Plugin URI:        https://risbl.co
 * Description:       Multiple instances of Crelly Slider alias as a translation with Polylang plugin
 * Version:           0.01
 * Author:            Kharis Sulistiyono
 * Author URI:        https://risbl.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sydney
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// This is for Sydney Pro only. Plese do not edit these lines.
$theme  = wp_get_theme();
$parent = wp_get_theme()->parent();
if ( ($theme != 'Sydney Pro' ) && ($parent != 'Sydney Pro') ) {
  return;
}

/**
 * Get language
 */
function sydney_pro_pll_get_language_name( $field = 'slug', $slug ) {
	return ( $lang = PLL()->model->get_language( $slug ) ) && isset( $lang->$field ) ? $lang->$field : false;
}


/**
 * Register new customizer controls under Crelly Slider panel
 */
add_action( 'customize_register', 'sydney_pro_child__customize_register' );
function sydney_pro_child__customize_register( $wp_customize ) {

  //___Crelly Slider for Polylang___//

  $language = array();
  $language = pll_languages_list(array(
    'hide_empty' => 0,
    'fields' => 'slug'
  ));

  foreach($language as $lang) {
    $wp_customize->add_setting(
        'rev_alias_'.$lang,
        array(
            'default' => '',
            'sanitize_callback' => 'sydney_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'rev_alias_'.$lang,
        array(
            'label'     => __( 'Crelly Slider Alias for '.sydney_pro_pll_get_language_name('name', $lang), 'sydney' ),
            'section'   => 'sydney_rev',
            'type'      => 'text',
            'priority'  => 12
        )
    );
  }

}


/**
 * Register Crelly Slider alias
 */
function sydney_pro_child_register_crelly_slider_alias() {

  $alias = get_theme_mod('rev_alias');

  $language = array();
  $language = pll_languages_list(array(
    'hide_empty' => 0,
    'fields' => 'slug'
  ));

  foreach($language as $lang) {
    if( pll_current_language('slug') == $lang ) {
      $alias = get_theme_mod('rev_alias_'.$lang);
    }
  }

  if ($alias && function_exists('crellySlider')) {
     crellySlider($alias);
  }

}


/**
 * Override default slider template
 */
function sydney_pro_child_sydney_slider_template() {

   if ( (get_theme_mod('front_header_type','slider') == 'slider' && is_front_page()) || (get_theme_mod('site_header_type') == 'slider' && !is_front_page()) ) {

   //Get the slider options
   $speed      = get_theme_mod('slider_speed', '4000');
   $text_slide = get_theme_mod('textslider_slide', 0);
   $button     = sydney_slider_button();
   $mobile_slider = get_theme_mod('mobile_slider', 'responsive');

   //Slider text
   if ( !function_exists('pll_register_string') ) {
       $titles = array(
           'slider_title_1' => get_theme_mod('slider_title_1', 'Welcome to Sydney'),
           'slider_title_2' => get_theme_mod('slider_title_2', 'Ready to begin your journey?'),
           'slider_title_3' => get_theme_mod('slider_title_3'),
           'slider_title_4' => get_theme_mod('slider_title_4'),
           'slider_title_5' => get_theme_mod('slider_title_5'),
       );
       $subtitles = array(
           'slider_subtitle_1' => get_theme_mod('slider_subtitle_1', 'Feel free to look around'),
           'slider_subtitle_2' => get_theme_mod('slider_subtitle_2', 'Feel free to look around'),
           'slider_subtitle_3' => get_theme_mod('slider_subtitle_3'),
           'slider_subtitle_4' => get_theme_mod('slider_subtitle_4'),
           'slider_subtitle_5' => get_theme_mod('slider_subtitle_5'),
       );
   } else {
       $titles = array(
           'slider_title_1' => pll__( get_theme_mod('slider_title_1', 'Welcome to Sydney') ),
           'slider_title_2' => pll__( get_theme_mod('slider_title_2', 'Ready to begin your journey?') ),
           'slider_title_3' => pll__( get_theme_mod('slider_title_3') ),
           'slider_title_4' => pll__( get_theme_mod('slider_title_4') ),
           'slider_title_5' => pll__( get_theme_mod('slider_title_5') ),
       );
       $subtitles = array(
           'slider_subtitle_1' => pll__( get_theme_mod('slider_subtitle_1', 'Feel free to look around') ),
           'slider_subtitle_2' => pll__( get_theme_mod('slider_subtitle_2', 'Feel free to look around') ),
           'slider_subtitle_3' => pll__( get_theme_mod('slider_subtitle_3') ),
           'slider_subtitle_4' => pll__( get_theme_mod('slider_subtitle_4') ),
           'slider_subtitle_5' => pll__( get_theme_mod('slider_subtitle_5') ),
       );
   }
   $images = array(
           'slider_image_1' => get_theme_mod('slider_image_1', get_stylesheet_directory_uri() . '/images/1.jpg'),
           'slider_image_2' => get_theme_mod('slider_image_2', get_stylesheet_directory_uri() . '/images/2.jpg'),
           'slider_image_3' => get_theme_mod('slider_image_3'),
           'slider_image_4' => get_theme_mod('slider_image_4'),
           'slider_image_5' => get_theme_mod('slider_image_5'),
   );
   ?>

   <div id="slideshow" class="header-slider" data-speed="<?php echo esc_attr($speed); ?>" data-mobileslider="<?php echo esc_attr($mobile_slider); ?>">
       <div class="slides-container">
           <?php $c = 1; ?>
           <?php foreach ( $images as $image ) {
               if ( $image ) {
                   ?>
                   <div class="slide-item slide-item-<?php echo $c; ?>" style="background-image:url('<?php echo esc_url( $image ); ?>');">
                       <img class="mobile-slide preserve" src="<?php echo esc_url( $image ); ?>"/>
                       <div class="slide-inner">
                           <div class="contain animated fadeInRightBig text-slider">
                           <h2 class="maintitle"><?php echo esc_html( $titles['slider_title_' . $c] ); ?></h2>
                           <p class="subtitle"><?php echo esc_html( $subtitles['slider_subtitle_' . $c] ); ?></p>
                           </div>
                           <?php echo $button; ?>
                       </div>
                   </div>
                   <?php
               }
               $c++;
           }
           ?>
       </div>
       <?php if ( $text_slide ) : ?>
           <?php echo sydney_stop_text(); ?>
       <?php endif; ?>
   </div>

   <?php
   } elseif ( (get_theme_mod('front_header_type','slider') == 'crelly' && is_front_page()) || (get_theme_mod('site_header_type') == 'crelly' && !is_front_page()) ) {
       sydney_pro_child_register_crelly_slider_alias();
   } elseif ( (get_theme_mod('front_header_type','slider') == 'video' && is_front_page()) || (get_theme_mod('site_header_type') == 'video' && !is_front_page()) ) {
       $mp4    = get_theme_mod('video_mp4');
       $webm   = get_theme_mod('video_webm');
       $ogv    = get_theme_mod('video_ogv');
       $poster = get_theme_mod('video_poster');

   ?>
   <div class="video-container">
       <?php echo do_shortcode('[video autoplay="on" poster="' . esc_url($poster) . '" loop="on" mp4="' . esc_url($mp4) . '" webm="' . esc_url($webm) . '" ogv="' . esc_url($ogv) . '"]'); ?>
   </div>
   <?php
   } elseif ( (get_theme_mod('front_header_type','slider') == 'shortcode' && is_front_page()) || (get_theme_mod('site_header_type') == 'shortcode' && !is_front_page()) ) {
       $shortcode = get_theme_mod('header_shortcode');
       echo '<div class="shortcode-area">';
           echo do_shortcode(wp_kses_post($shortcode));
       echo '</div>';
   }
}
