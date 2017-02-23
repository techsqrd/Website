<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

function varnish_safe_http_headers() {
    header( 'Cache-Control: no-cache, must-revalidate' );
    session_cache_limiter('');
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
  if( !session_id() )
  {
    session_start();
  }
}
add_action( 'send_headers', 'varnish_safe_http_headers' );
?>