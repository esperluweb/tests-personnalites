<?php
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
    <div id="tp2-container" style="max-width:500px;margin:auto;padding:20px;font-family:sans-serif">
      <div id="<?php echo esc_attr($container_id); ?>-question"></div>
      <div id="<?php echo esc_attr($container_id); ?>-buttons" style="margin-top:15px"></div>
      <div id="<?php echo esc_attr($container_id); ?>-result" style="margin-top:20px;font-weight:500"></div>
    </div>
    <script>
    (function(){
        const data = <?php echo json_encode($data); ?>;
        const qEl = document.getElementById('<?php echo esc_js($container_id); ?>-question');
        const btnEl = document.getElementById('<?php echo esc_js($container_id); ?>-buttons');
        const resEl = document.getElementById('<?php echo esc_js($container_id); ?>-result');
        let current = 0;
        let answers = [];
        function render() {
            qEl.innerHTML = '';
            btnEl.innerHTML = '';
            resEl.innerHTML = '';
            if (current < data.questions.length) {
                const q = data.questions[current];
                qEl.innerHTML = `<p><strong>Question ${current+1} sur ${data.questions.length} :</strong><br>${q.text}</p>`;
                btnEl.innerHTML = q.answers.map((a, i) =>
                  `<button class="tp2-btn" onclick="return false;" style="display:block;width:100%;margin:6px 0;padding:10px;border:none;border-radius:6px;background:#1c8adb;color:#fff;font-size:15px">${a.text}</button>`
                ).join('');
                // Attach events
                Array.from(btnEl.children).forEach((btn, i) => {
                  btn.onclick = () => { answers[current] = i; current++; render(); };
                });
                // Navigation
                if(current > 0) {
                  const prevBtn = document.createElement('button');
                  prevBtn.type = 'button';
                  prevBtn.textContent = 'Précédent';
                  prevBtn.className = 'tp2-btn-outline';
                  prevBtn.style = 'margin-top:8px;background:#fff;color:#1c8adb;border:1px solid #1c8adb;font-size:15px;padding:10px;border-radius:6px;width:100%';
                  prevBtn.onclick = () => { current--; render(); };
                  btnEl.appendChild(prevBtn);
                }
            } else {
                // Calcul du score
                let score = 0;
                data.questions.forEach((q, idx) => {
                    const aIdx = answers[idx];
                    if (typeof aIdx !== 'undefined') {
                        score += parseInt(q.answers[aIdx].points || 0);
                    }
                });
                // Chercher le résultat
                let result = data.results.find(r => score >= r.min && score <= r.max);
                let resultText = result ? result.text : 'Aucun résultat trouvé.';
                resEl.innerHTML = `<div style='padding:15px;background:#f0f0f0;border-radius:8px;text-align:center'><h3 style='margin-top:0'>Résultat</h3><div style='font-size:1.2em;margin-bottom:1em;'>${resultText}</div><div style='margin-bottom:1em;'>Votre score : <strong>${score}</strong></div></div>`;
                // Bouton recommencer
                const restartBtn = document.createElement('button');
                restartBtn.type = 'button';
                restartBtn.className = 'tp2-btn';
                restartBtn.textContent = 'Recommencer';
                restartBtn.style = 'margin-top:18px;display:block;width:100%;padding:10px;border:none;border-radius:6px;background:#1c8adb;color:#fff;font-size:15px';
                restartBtn.onclick = function() {
                    current = 0;
                    answers = [];
                    render();
                };
                resEl.appendChild(restartBtn);
            }
        }
        render();
    })();
    </script>
    <style>
    .tp2-btn {
      display:block;
      width:100%;
      margin:6px 0;
      padding:10px;
      border:none;
      border-radius:6px;
      background:#1c8adb;
      color:#fff;
      font-size:15px;
      cursor:pointer;
      transition:background 0.2s;
    }
    .tp2-btn:hover {
      background:#176bb5;
    }
    .tp2-btn-outline {
      background:#fff;
      color:#1c8adb;
      border:1px solid #1c8adb;
      font-size:15px;
      padding:10px;
      border-radius:6px;
      width:100%;
      cursor:pointer;
      margin-top:8px;
      transition:background 0.2s, color 0.2s;
    }
    .tp2-btn-outline:hover {
      background:#eaf6ff;
      color:#176bb5;
    }
    </style>
    <style>
    .tp-personality-test { max-width: 500px; margin: 2em auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); padding: 2em; }
    @media (max-width:600px) { .tp-personality-test { padding: 1em; } }
    .tp-fadein { animation: tp-fadein .35s; }
    .tp-fadeout { animation: tp-fadeout .2s; }
    @keyframes tp-fadein { from { opacity: 0; } to { opacity: 1; } }
    @keyframes tp-fadeout { from { opacity: 1; } to { opacity: 0; } }
    </style>
    <?php
    return ob_get_clean();
});

// Injection Material UI et Roboto uniquement sur pages avec le shortcode
add_action('wp_head', function() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'personality_test')) {
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mui/material@5.14.17/dist/material.min.css">';
        echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">';
        echo '<style>.mui-btn{font-family:Roboto,sans-serif;font-size:1.05em;}</style>';
    }
});
