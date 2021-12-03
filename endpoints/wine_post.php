<?php

function api_wine_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if ($user_id === 0) {
    $response = new WP_Error('error', 'Usuário não possui permissão.', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $nome = sanitize_text_field($request['nome']);
  $codigo = sanitize_text_field($request['codigo']);
  $ano = sanitize_text_field($request['ano']);
  $quantidade = sanitize_text_field($request['quantidade']);
  $tipo = sanitize_text_field($request['tipo']);
  $pais = sanitize_text_field($request['pais']);
  $descricao = sanitize_text_field($request['descricao']);
  $uva = sanitize_text_field($request['uva']);
  $amadurecimento = sanitize_text_field($request['amadurecimento']);
  $regiao = sanitize_text_field($request['regiao']);
  $classificacao = sanitize_text_field($request['classificacao']);
  $teor = sanitize_text_field($request['teor']);
  $potencial = sanitize_text_field($request['potencial']);
  $temperatura = sanitize_text_field($request['temperatura']);
  $safra = sanitize_text_field($request['safra']);
  $decantacao = sanitize_text_field($request['decantacao']);
  $vinicola = sanitize_text_field($request['vinicola']);
  $files = $request->get_file_params();

  if (empty($nome) || empty($codigo) || empty($quantidade) || empty($tipo) || empty($pais) || empty($descricao) || empty($files)) {
    $response = new WP_Error('error', 'Dados incompletos.', ['status' => 422]);
    return rest_ensure_response($response);
  }

  $response = [
    'post_author' => $user_id,
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => $nome,
    'post_content' => $nome,
    'files' => $files,
    'meta_input' => [
      'codigo' => $codigo,
      'ano' => $ano,
      'quantidade' => $quantidade,
      'tipo' => $tipo,
      'pais' => $pais,
      'descricao' => $descricao,
      'uva' => $uva,
      'amadurecimento' => $amadurecimento,
      'regiao' => $regiao,
      'classificacao' => $classificacao,
      'teor' => $teor,
      'potencial' => $potencial,
      'temperatura' => $temperatura,
      'safra' => $safra,
      'decantacao' => $decantacao,
      'vinicola' => $vinicola,
    ],
  ];

  $post_id = wp_insert_post($response);

  require_once ABSPATH . 'wp-admin/includes/image.php';
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';

  $photo_id = media_handle_upload('img', $post_id);
  update_post_meta($post_id, 'img', $photo_id);

  return rest_ensure_response($response);
}

function register_api_wine_post() {
  register_rest_route('api', '/wine', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_wine_post',
  ]);
}
add_action('rest_api_init', 'register_api_wine_post');

?>