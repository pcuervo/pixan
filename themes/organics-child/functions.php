<?php
/*Function alt text*/
function add_alt_tags( $content ) {
  preg_match_all( '/<img (.*?)\/>/', $content, $images );
  if ( ! is_null( $images ) ) {
    foreach ( $images[1] as $index => $value ) {
      if ( ! preg_match( '/alt=/', $value ) ) {
        $new_img = str_replace(
          '<img',
          '<img alt="' . esc_attr( get_the_title() ) . '"',
          $images[0][$index] );
        $content = str_replace(
          $images[0][$index],
          $new_img,
          $content );
      }
    }
  }
  return $content;
}
add_filter( 'the_content', 'add_alt_tags', 99999 );

?>