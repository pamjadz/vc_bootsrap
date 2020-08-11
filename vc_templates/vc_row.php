<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$output = '';
$wrapper_attributes = array();
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract($atts);

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_classes = array($el_class, vc_shortcode_custom_css_class( $css ));

//Set type of ROWs
if($full_width == 'stretch_row' || $full_width == 'stretch_row_content_no_spaces'){
	$css_classes[] = 'row no-gutters';
} else {
	$css_classes[] = 'row';
}

//Hide Element
if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'hidden-lg hidden-xs hidden-sm hidden-md';
	} else {
		return;
	}
}

//Set Columns Gap
if (!empty( $atts['gap'] )){
	$css_classes[] = 'col-gap-' . $atts['gap'];
} else {
	$css_classes[] = 'col-gap-0';
}

//Height and Equalment
if (empty($equal_height)) $css_classes[] = 'align-items-start';
if (!empty( $content_placement ) ) $css_classes[] = 'align-content-' . $content_placement;

//Set Video In Background
$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );
if ($has_video_bg) $wrapper_attributes[] = 'data-video-bg="' . esc_attr( $video_bg_url ) . '"';

//Set RTL/LTR mode
if (!empty( $atts['rtl_reverse'] ) && is_rtl() ) $wrapper_attributes[] = 'dir="ltr"';
if (!empty( $atts['rtl_reverse'] ) && !is_rtl() ) $wrapper_attributes[] = 'dir="rtl"';

//Asign id attr
if (!empty( $el_id ) ) $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

//Generate ROW element
$output .= ($full_width == 'stretch_row_content' || $full_width == 'stretch_row_content_no_spaces') ? '<div class="container">' : '';
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= ($full_width == 'stretch_row_content' || $full_width == 'stretch_row_content_no_spaces') ? '</div>' : '';

echo $output;