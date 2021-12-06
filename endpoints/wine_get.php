<?php

function wine_data($post) {
  $post_meta = get_post_meta($post->ID);
  $src = wp_get_attachment_image_src($post_meta['img'][0], 'large')[0];
  $user = get_userdata($post->post_author);

  return [
    'id' => $post->ID,
    'author' => $user->user_login,
    'title' => $post->post_title,
    'date' => $post->post_date,
    'src' => $src,
    'codigo' => $post_meta['codigo'][0],
    'ano' => $post_meta['ano'][0],
    'quantidade' => $post_meta['quantidade'][0],
    'tipo' => $post_meta['tipo'][0],
    'pais' => $post_meta['pais'][0],
    'descricao' => $post_meta['descricao'][0],
    'uva' => $post_meta['uva'][0],
    'amadurecimento' => $post_meta['amadurecimento'][0],
    'regiao' => $post_meta['regiao'][0],
    'classificacao' => $post_meta['classificacao'][0],
    'teor' => $post_meta['teor'][0],
    'potencial' => $post_meta['potencial'][0],
    'temperatura' => $post_meta['temperatura'][0],
    'safra' => $post_meta['safra'][0],
    'decantacao' => $post_meta['decantacao'][0],
    'vinicola' => $post_meta['vinicola'][0],
  ];
}

function api_wine_get($request) {
  $post_id = $request['id'];
  $post = get_post($post_id);

  if (!isset($post) || empty($post_id)) {
    $response = new WP_Error('error', 'Post não encontrado.', ['status' => 404]);
    return rest_ensure_response($response);
  }

  $photo = wine_data($post);
  $photo['acessos'] = (int) $photo['acessos'] + 1;
  update_post_meta($post_id, 'acessos', $photo['acessos']);

  $comments = get_comments([
    'post_id' => $post_id,
    'order' => 'ASC',
  ]);

  $response = [
    'photo' => $photo,
    'comments' => $comments,
  ];

  return rest_ensure_response($response);
}

function register_api_wine_get() {
  register_rest_route('api', '/wine/(?P<id>[0-9]+)', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_wine_get',
  ]);
}
add_action('rest_api_init', 'register_api_wine_get');

function api_wines_get($request) {
  $_user = sanitize_text_field($request['_user']) ?: 0;

  if (!is_numeric($_user)) {
    $user = get_user_by('login', $_user);
    $_user = $user->ID;
  }

  $args = [
    'post_type' => 'post',
    'author' => $_user,
    'posts_per_page' => $_total,
    'paged' => $_page,
  ];

  $query = new WP_Query($args);
  $posts = $query->posts;

  $wines = [];
  if ($posts) {
    foreach ($posts as $post) {
      $wines[] = wine_data($post);
    }
  }

  return rest_ensure_response($wines);
}

function register_api_wines_get() {
  register_rest_route('api', '/wine', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_wines_get',
  ]);
}
add_action('rest_api_init', 'register_api_wines_get');

?>