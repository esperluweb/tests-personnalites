<?php
// Déclaration du Custom Post Type "personality_test"
add_action('init', function() {
    $labels = array(
        'name' => 'Tests de personnalité',
        'singular_name' => 'Test de personnalité',
        'add_new' => 'Ajouter un test',
        'add_new_item' => 'Ajouter un nouveau test',
        'edit_item' => 'Modifier le test',
        'new_item' => 'Nouveau test',
        'view_item' => 'Voir le test',
        'search_items' => 'Rechercher un test',
        'not_found' => 'Aucun test trouvé',
        'not_found_in_trash' => 'Aucun test dans la corbeille',
        'all_items' => 'Tous les tests',
        'menu_name' => 'Tests de personnalité',
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-admin-users',
        'supports' => array('title'),
        'has_archive' => false,
    );
    register_post_type('personality_test', $args);
});

// Colonne Shortcode dans l'admin
add_filter('manage_personality_test_posts_columns', function($columns) {
    $columns['tp_shortcode'] = 'Shortcode';
    return $columns;
});
add_action('manage_personality_test_posts_custom_column', function($column, $post_id) {
    if ($column === 'tp_shortcode') {
        echo '<code>[personality_test id="'.$post_id.'"]</code>';
    }
}, 10, 2);
