// JS principal du plugin Tests Personnalités
// (Ce fichier est chargé côté frontend via wp_enqueue_script)

(function(){
    function initPersonalityTest(container) {
        const dataDiv = container.querySelector('#tp2-data');
        if (!dataDiv) return;
        let data;
        try {
            data = JSON.parse(dataDiv.getAttribute('data-test'));
        } catch(e) {
            return;
        }
        const containerId = dataDiv.getAttribute('data-container');
        const qEl = container.querySelector(`#${containerId}-question`);
        const btnEl = container.querySelector(`#${containerId}-buttons`);
        const resEl = container.querySelector(`#${containerId}-result`);
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
                  `<button class="tp2-btn" type="button">${a.text}</button>`
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
                  prevBtn.onclick = () => { current--; render(); };
                  btnEl.appendChild(prevBtn);
                }
            } else {
                // Calcul du score total
                let score = 0;
                data.questions.forEach((q, i) => {
                  const ansIdx = answers[i];
                  if (typeof ansIdx !== 'undefined' && q.answers[ansIdx]) {
                    score += parseInt(q.answers[ansIdx].points || 0, 10);
                  }
                });
                // Chercher le résultat correspondant à ce score
                let result = data.results.find(r => score >= r.min && score <= r.max);
                let resultText = result ? `<div class="tp-fadein"><strong>Résultat :</strong><br>${result.text}<br><small>Score : ${score}</small></div>` : '<div class="tp-fadein">Merci d\'avoir complété le test !</div>';
                resEl.innerHTML = resultText;
                // Restart button
                const restartBtn = document.createElement('button');
                restartBtn.type = 'button';
                restartBtn.textContent = 'Recommencer';
                restartBtn.className = 'tp2-btn-outline';
                restartBtn.onclick = () => { current = 0; answers = []; render(); };
                btnEl.appendChild(restartBtn);
            }
        }
        render();
    }
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.tp-personality-test').forEach(initPersonalityTest);
    });
})();
