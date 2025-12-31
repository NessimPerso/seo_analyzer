/**
 * Application principale SEO Content Analyzer
 * Gère l'interface utilisateur et la logique métier
 */

// Éléments du DOM
const analyzeForm = document.getElementById('analyzeForm');
const loadingSpinner = document.getElementById('loadingSpinner');
const resultsSection = document.getElementById('results');
const errorMessageDiv = document.getElementById('errorMessage');

// Événements
document.addEventListener('DOMContentLoaded', function() {
    analyzeForm.addEventListener('submit', handleFormSubmit);
});

/**
 * Gère la soumission du formulaire
 */
async function handleFormSubmit(e) {
    e.preventDefault();

    // Récupérer les valeurs
    const editorialGuidelines = document.getElementById('editorialGuidelines').value.trim();
    const clientGuidelines = document.getElementById('clientGuidelines').value.trim();
    const contentText = document.getElementById('contentText').value.trim();

    // Valider
    if (!editorialGuidelines || !clientGuidelines || !contentText) {
        showError('Veuillez remplir tous les champs');
        return;
    }

    if (contentText.length > 5000 || editorialGuidelines.length > 5000 || clientGuidelines.length > 5000) {
        showError('Les champs texte ne doivent pas dépasser 5000 caractères');
        return;
    }

    // Afficher le chargement
    hideError();
    hideResults();
    showLoading();

    try {
        // Appeler l'API
        const response = await APIClient.analyze(
            editorialGuidelines,
            clientGuidelines,
            contentText
        );

        if (!response.success) {
            throw new Error(response.error || 'Erreur inconnue');
        }

        // Afficher les résultats
        displayResults(response);

    } catch (error) {
        showError(error.message || 'Une erreur est survenue lors de l\'analyse');
    } finally {
        hideLoading();
    }
}

/**
 * Affiche les résultats de l'analyse
 */
function displayResults(response) {
    const scoring = response.scoring;

    // Score global
    const scoreCard = document.getElementById('scoreCard');
    scoreCard.style.backgroundColor = getScoreColor(scoring.globalScore);

    document.getElementById('globalScore').textContent = Math.round(scoring.globalScore * 10) / 10;
    document.getElementById('decision').textContent = scoring.decision;
    document.getElementById('verdict').textContent = scoring.isAcceptable 
        ? '✅ Contenu acceptable pour publication'
        : '⚠️ Contenu à revoir avant publication';

    // Affichage des warnings
    const warningsDiv = document.getElementById('warnings');
    if (Array.isArray(scoring.warnings) && scoring.warnings.length > 0) {
        warningsDiv.innerHTML = scoring.warnings.map(w => `<div class="warning-item"> ${w}</div>`).join('');
        warningsDiv.classList.remove('hidden');
    } else {
        warningsDiv.innerHTML = '';
        warningsDiv.classList.add('hidden');
    }

    // Analyse détaillée - Les 4 critères
    const detailedAnalysisDiv = document.getElementById('detailedAnalysis');
    detailedAnalysisDiv.innerHTML = '';

    if (scoring.details && scoring.details.detailedFeedback) {
        Object.keys(scoring.details.detailedFeedback).forEach(key => {
            const feedback = scoring.details.detailedFeedback[key];
            const item = createAnalysisItem(key, feedback);
            detailedAnalysisDiv.appendChild(item);
        });
    }

    // Afficher les résultats
    showResults();
}

/**
 * Crée un élément d'analyse détaillée
 */
function createAnalysisItem(title, feedback) {
    const item = document.createElement('div');
    item.className = 'analysis-item ' + (feedback.status === '✓' ? 'success' : 'failure');

    const header = document.createElement('div');
    header.className = 'analysis-item-header';
    header.innerHTML = `<span class="analysis-item-icon">${feedback.status}</span><span>${title}</span>`;

    const comment = document.createElement('div');
    comment.className = 'analysis-item-comment';
    comment.textContent = feedback.comment;

    const recommendation = document.createElement('div');
    recommendation.className = 'analysis-item-recommendation';
    recommendation.textContent = feedback.recommendation ? `💡 ${feedback.recommendation}` : '';

    item.appendChild(header);
    item.appendChild(comment);
    if (feedback.recommendation) {
        item.appendChild(recommendation);
    }

    return item;
}

/**
 * Obtient la couleur selon le score
 */
function getScoreColor(score) {
    if (score >= 8.5) return 'linear-gradient(135deg, #10b981, #059669)';
    if (score >= 7) return 'linear-gradient(135deg, #f59e0b, #d97706)';
    if (score >= 5) return 'linear-gradient(135deg, #f97316, #ea580c)';
    return 'linear-gradient(135deg, #ef4444, #dc2626)';
}

/**
 * Affiche le message de chargement
 */
function showLoading() {
    loadingSpinner.classList.remove('hidden');
}

/**
 * Cache le message de chargement
 */
function hideLoading() {
    loadingSpinner.classList.add('hidden');
}

/**
 * Affiche les résultats
 */
function showResults() {
    resultsSection.classList.remove('hidden');
    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/**
 * Cache les résultats
 */
function hideResults() {
    resultsSection.classList.add('hidden');
}

/**
 * Affiche une erreur
 */
function showError(message) {
    errorMessageDiv.textContent = message;
    errorMessageDiv.classList.remove('hidden');
}

/**
 * Cache le message d'erreur
 */
function hideError() {
    errorMessageDiv.classList.add('hidden');
}

/**
 * Réinitialise le formulaire
 */
function resetForm() {
    analyzeForm.reset();
    hideResults();
    hideError();
}
