<?php
/**
 * Service de construction des prompts pour l'IA
 * Crée les instructions pour ChatGPT
 */

class PromptBuilder
{
    public function buildAnalysisPrompt($editorialGuidelines, $clientGuidelines, $text)
    {
        $prompt = <<<PROMPT
Tu es un expert en analyse de contenu éditorial et SEO.
Analyse le texte suivant selon 4 critères précis et retourne une évaluation structurée en JSON.

CONSIGNES ÉDITORIALES :
$editorialGuidelines

CONSIGNES CLIENT :
$clientGuidelines

TEXTE À ANALYSER :
$text

Évalue le texte sur ces 4 critères EXACTEMENT :
1. L'orthographe et la grammaire : Vérifie les fautes, conjugaison, accords. Sois STRICT.
2. Le respect des consignes édito : VÉRIFIE CHAQUE POINT (longueur, structure, mots-clés, etc.). Sois EXIGEANT.
3. Le respect des consignes client : Vérifie que le ton, le style et les directives client sont respectées. Sois STRICT.
4. Vérification et pertinence : Les infos sont-elles justes ? Le texte est-il complet ? Peut-il être mieux développé ?

RÈGLES D'ÉVALUATION STRICTES :
- Si une consigne parle de longueur (ex: 700 mots), compte exactement. Un texte de 200 mots n'est PAS acceptable si 700 est demandé.
- Si une consigne parle de structure (ex: H2, sections), vérifiez leur présence.
- Si des mots-clés sont obligatoires, cherchez-les. S'ils manquent, c'est un ✗.
- Sois EXIGEANT : un "À peu près" n'est PAS acceptable.
- Si tu détectes des CONSIGNES INCOHÉRENTES ou CONTRADICTOIRES (ex : minimum 500 mots et maximum 200 mots), signale-le dans un champ "warnings" du JSON et pénalise fortement le score global.
- Si le texte est HORS SUJET, dans la MAUVAISE LANGUE, ou ne correspond pas au contexte SEO demandé, ajoute un warning "warnings" et pénalise fortement le score.
- Si tout est cohérent, le champ "warnings" doit être vide.

Retourne un JSON avec cette structure EXACTE :
{
    "globalScore": <note 0-10>,
    "warnings": ["message d'avertissement si incohérence dans les consignes éditoriales ou hors sujet"],
    "analysis": {
        "detailedFeedback": {
            "Orthographe et grammaire": {
                "status": "✓" ou "✗",
                "comment": "constat détaillé",
                "recommendation": "action à faire"
            },
            "Respect des consignes édito": {
                "status": "✓" ou "✗",
                "comment": "constat détaillé",
                "recommendation": "action à faire"
            },
            "Respect des consignes client": {
                "status": "✓" ou "✗",
                "comment": "constat détaillé",
                "recommendation": "action à faire"
            },
            "Vérification et pertinence": {
                "status": "✓" ou "✗",
                "comment": "constat détaillé",
                "recommendation": "action à faire"
            }
        }
    }
}

Sois précis et concis. Justifie chaque évaluation. Si tu détectes un problème, explique-le clairement dans "warnings".
PROMPT;

        return $prompt;
    }
}
