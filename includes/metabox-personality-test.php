<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Enqueue du JS de la metabox uniquement sur personality_test
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'post-new.php' || $hook === 'post.php') {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'personality_test') {
            wp_enqueue_script(
                'tp-admin-metabox',
                plugin_dir_url(__DIR__).'includes/admin-test-metabox.js',
                [],
                '1.0.1',
                true
            );
        }
    }
});

// Metabox pour questions/réponses/résultats et sauvegarde
add_action('add_meta_boxes', function() {
    add_meta_box(
        'tp_test_meta',
        'Questions et Résultats',
        'tp_render_test_metabox',
        'personality_test',
        'normal',
        'high'
    );
});

function tp_render_test_metabox($post) {
    $raw = get_post_meta($post->ID, '_tp_test_data', true);
    $data = stripslashes($raw);
    $json_ok = false;
    if ($data) {
        $try = json_decode($data, true);
        if (is_array($try) && isset($try['questions']) && isset($try['results'])) {
            $data = $try;
            $json_ok = true;
        }
    }
    if (!$json_ok) {
        $data = [
            'questions' => [],
            'results' => []
        ];
    }
    wp_nonce_field('tp_save_test', 'tp-test-nonce');
    ?>
    <div id="tp-metabox-root">
        <h4>Questions</h4>
        <div id="tp-questions"></div>
        <button type="button" class="button" onclick="tpAddQuestion()">Ajouter une question</button>
        <hr>
        <h4>Règles de résultats</h4>
        <p style="font-size:13px;color:#666;margin-top:-10px;margin-bottom:10px;">Précision : les intervalles min/max sont <strong>inclusifs</strong> (ex : 0 à 2 inclut aussi 0 et 2).</p>
        <div id="tp-results"></div>
        <button type="button" class="button" onclick="tpAddResult()">Ajouter une règle de résultat</button>
        <input type="hidden" id="tp-test-data" name="tp-test-data" value='<?php echo esc_attr(json_encode($data)); ?>'>
    </div>
    <style>
        #tp-metabox-root input[type=text] { width: 60%; margin-bottom: 4px; }
        #tp-metabox-root input[type=number] { margin-bottom: 4px; }
        #tp-metabox-root .button { margin-top: 5px; }
    </style>
    <?php
}

// Sauvegarde des données de la metabox
add_action('save_post_personality_test', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (
        isset($_POST['tp-test-data']) &&
        isset($_POST['tp-test-nonce']) &&
        wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tp-test-nonce'])), 'tp_save_test')
    ) {
        // On unslash, on sanitize puis on valide la donnée JSON (Plugin Check OK)
        $raw = sanitize_textarea_field(wp_unslash($_POST['tp-test-data']));
        $decoded = json_decode($raw, true);
        $safe_json = (is_array($decoded)) ? json_encode($decoded) : json_encode(['questions'=>[], 'results'=>[]]);
        // Sanitation et validation des données avant enregistrement
        delete_post_meta($post_id, '_tp_test_data');
        add_post_meta($post_id, '_tp_test_data', $safe_json);
    }
});
