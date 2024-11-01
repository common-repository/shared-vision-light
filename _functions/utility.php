<?php

function shared_vision_utility_replace_string_contents_to( string $string, string $replace_to, array $whitelist = [] ) :string {
  $chars = str_split($string);

  $response = '';

  foreach ($chars as $char) {
    if ( in_array( $char, $whitelist ) ) {
      $response .= $char;
    } else {
      $response .= $replace_to;
    }
  }

  return $response;
}

/**
 * @depreacated this method is not used anymore.
 * @param $list_hash
 * @return string
 */
function shared_vision_embed_script_src( $list_hash ) :string {
  return SharedVision\API\SharedVision\Request::get_endpoint() . 'frame/' . $list_hash;
}