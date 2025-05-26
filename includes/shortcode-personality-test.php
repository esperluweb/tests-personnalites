<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Shortcode pour afficher un test de personnalité côté frontend
add_shortcode('personality_test', function($atts) {
    $atts = shortcode_atts([
        'id' => 0,
    ], $atts);
    $test_id = intval($atts['id']);
    if (!$test_id) return '<p>Test introuvable.</p>';
    $data = get_post_meta($test_id, '_tp_test_data', true);
    $data = $data ? json_decode(stripslashes($data), true) : null;
    if (!$data || empty($data['questions'])) return '<p>Ce test n\'est pas configuré.</p>';
    $container_id = 'tp-personality-test-' . $test_id;
    ob_start();
    ?>
    <div class="tp-personality-test">
        <div id="tp2-data" data-test='<?php echo esc_attr( wp_json_encode($data) ); ?>' data-container="<?php echo esc_attr($container_id); ?>"></div>
        <div id="<?php echo esc_attr($container_id); ?>-question"></div>
        <div id="<?php echo esc_attr($container_id); ?>-buttons"></div>
        <div id="<?php echo esc_attr($container_id); ?>-result"></div>
    </div>
    <?php
    return ob_get_clean();
});

// Enqueue CSS côté frontend pour le test de personnalité
add_action( 'wp_enqueue_scripts', function() {
    if ( is_singular() ) {
        global $post;
        if ( has_shortcode( $post->post_content, 'personality_test' ) ) {
            wp_enqueue_script(
                'tp-frontend-main',
                plugins_url('includes/main.js', dirname(__FILE__, 2) . '/tests-personnalites.php'),
                [],
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'tp-frontend-style',
                plugins_url('includes/style-personality-test.css', dirname(__FILE__, 2) . '/tests-personnalites.php'),
                [],
                '1.0.0'
            );
        }
    }
});
