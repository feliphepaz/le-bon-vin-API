<?php

// remove_action('rest_api_init', 'create_initial_rest_routes', 99);

$dirbase = get_template_directory();
require_once $dirbase . '/endpoints/wine_post.php';
require_once $dirbase . '/endpoints/wine_delete.php';
require_once $dirbase . '/endpoints/wine_get.php';

function change_api($slug) {
  return 'json';
}
add_filter('rest_url_prefix', 'change_api');

function expire_token() {
    return time() + (60 * 60 * 24);
}

add_action('jwt_auth_expire', 'expire_token');


?>
