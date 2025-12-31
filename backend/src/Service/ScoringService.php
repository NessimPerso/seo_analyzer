<?php
/**
 * Service d'évaluation et scoring du contenu
 */

class ScoringService
{
    public function score($analysis)
    {
        if (!is_array($analysis) || !isset($analysis['globalScore'])) {
            return $this->getDefaultScoring();
        }

        $globalScore = (float)$analysis['globalScore'];

        return [
            'globalScore' => min(10, max(0, $globalScore)),
            'isAcceptable' => $globalScore >= 7,
            'warnings' => $analysis['warnings'] ?? "",
            'decision' => $this->getDecision($globalScore),
            'details' => [
                'detailedFeedback' => $analysis['analysis']['detailedFeedback'] ?? []
            ]
        ];
    }

    private function getDecision($score)
    {
        if ($score >= 8.5) {
            return 'Excellent - Prêt à publication';
        } elseif ($score >= 7) {
            return 'Bon - Prêt à publication avec remarques mineures';
        } elseif ($score >= 5) {
            return 'À corriger - Demande des modifications';
        } else {
            return 'À revoir entièrement';
        }
    }

    private function getDefaultScoring()
    {
        return [
            'globalScore' => 0,
            'isAcceptable' => false,
            'decision' => 'Erreur - Impossible à évaluer',
            'details' => null
        ];
    }
}
