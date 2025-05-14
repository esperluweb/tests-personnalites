// JS pour la metabox de gestion des tests de personnalité (questions/réponses/résultats)
document.addEventListener('DOMContentLoaded', function() {
    let root = document.getElementById('tp-metabox-root');
    if (!root) return;
    let tpData = {};
    // Log la valeur initiale du champ à l'ouverture de la page
    console.log('[DEBUG] Valeur initiale du champ tp-test-data:', document.getElementById('tp-test-data').value);
    try {
        tpData = JSON.parse(document.getElementById('tp-test-data').value);
    } catch(e) {
        tpData = {questions:[], results:[]};
    }
    if (!tpData || typeof tpData !== 'object' || !Array.isArray(tpData.questions) || !Array.isArray(tpData.results)) {
        tpData = {questions:[], results:[]};
    }
    // Force un render immédiat après initialisation pour afficher les données existantes
    tpRender();
    function tpSyncHidden() {
        document.getElementById('tp-test-data').value = JSON.stringify(tpData);
    }
    function tpRender() {
        // Questions
        let qHtml = '';
        tpData.questions.forEach((q, i) => {
            qHtml += `<div class='tp-question' style='margin-bottom:15px;padding:10px;border:1px solid #ccc;'>`;
            qHtml += `<label>Question <input type='text' value="${q.text.replace(/"/g,'&quot;')}" onchange='tpUpdateQuestionText(${i}, this.value)'></label>`;
            qHtml += `<div>Réponses:`;
            q.answers.forEach((a, j) => {
                qHtml += `<div style='margin-left:20px;'>`;
                qHtml += `<input type='text' value="${a.text.replace(/"/g,'&quot;')}" onchange='tpUpdateAnswerText(${i},${j}, this.value)' placeholder='Réponse'>`;
                qHtml += ` <input type='number' value="${a.points}" onchange='tpUpdateAnswerPoints(${i},${j}, this.value)' style='width:60px;' placeholder='Points'>`;
                qHtml += ` <button type='button' onclick='tpRemoveAnswer(${i},${j})'>Supprimer réponse</button>`;
                qHtml += `</div>`;
            });
            qHtml += `<button type='button' onclick='tpAddAnswer(${i})'>Ajouter une réponse</button>`;
            qHtml += `</div>`;
            qHtml += `<button type='button' onclick='tpRemoveQuestion(${i})'>Supprimer question</button>`;
            qHtml += `</div>`;
        });
        document.getElementById('tp-questions').innerHTML = qHtml;
        tpSyncHidden();
        // Résultats
        let rHtml = '';
        tpData.results.forEach((r, i) => {
            rHtml += `<div class='tp-result' style='margin-bottom:10px;padding:8px;border:1px dashed #aaa;'>`;
            rHtml += `De <input type='number' value="${r.min}" onchange='tpUpdateResultMin(${i}, this.value)' style='width:60px;'> à <input type='number' value="${r.max}" onchange='tpUpdateResultMax(${i}, this.value)' style='width:60px;'> points : `;
            rHtml += `<input type='text' value="${r.text.replace(/"/g,'&quot;')}" onchange='tpUpdateResultText(${i}, this.value)' placeholder='Texte du résultat' style='width:40%'>`;
            rHtml += ` <button type='button' onclick='tpRemoveResult(${i})'>Supprimer</button>`;
            rHtml += `</div>`;
        });
        document.getElementById('tp-results').innerHTML = rHtml;
        tpSyncHidden();
    }
    // Helpers globaux
    window.tpAddQuestion = function() { tpData.questions.push({text:'', answers:[]}); tpRender(); }
    window.tpRemoveQuestion = function(i) { tpData.questions.splice(i,1); tpRender(); }
    window.tpUpdateQuestionText = function(i, val) { tpData.questions[i].text = val; tpRender(); }
    window.tpAddAnswer = function(i) { tpData.questions[i].answers.push({text:'', points:0}); tpRender(); }
    window.tpRemoveAnswer = function(i,j) { tpData.questions[i].answers.splice(j,1); tpRender(); }
    window.tpUpdateAnswerText = function(i,j,val) { tpData.questions[i].answers[j].text = val; tpRender(); }
    window.tpUpdateAnswerPoints = function(i,j,val) { tpData.questions[i].answers[j].points = parseInt(val)||0; tpRender(); }
    window.tpAddResult = function() { tpData.results.push({min:0,max:0,text:''}); tpRender(); }
    window.tpRemoveResult = function(i) { tpData.results.splice(i,1); tpRender(); }
    window.tpUpdateResultMin = function(i,val) { tpData.results[i].min = parseInt(val)||0; tpRender(); }
    window.tpUpdateResultMax = function(i,val) { tpData.results[i].max = parseInt(val)||0; tpRender(); }
    window.tpUpdateResultText = function(i,val) { tpData.results[i].text = val; tpRender(); }
    // Initial render
    tpRender();
    // Debug
    console.log('window.tpAddQuestion:', typeof window.tpAddQuestion);
    // Sauvegarde avant submit
    function tpSyncBeforeSave() {
        document.getElementById('tp-test-data').value = JSON.stringify(tpData);
        console.log('[DEBUG] tpSyncBeforeSave - valeur envoyée:', document.getElementById('tp-test-data').value, tpData);
    }
    var form = document.querySelector('form#post');
    if (form) {
        form.addEventListener('submit', tpSyncBeforeSave);
    }

    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('editor-post-publish-button') || e.target.classList.contains('editor-post-save-draft'))) {
            tpSyncBeforeSave();
        }
    });
});
