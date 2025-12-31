<?php
/**
 * Contrôleur principal pour l'analyse de contenu SEO
 * Reçoit données → appelle OpenAI → retourne résultats
 */

$basePath = __DIR__ . '/..';
require_once $basePath . '/Service/PromptBuilder.php';
require_once $basePath . '/Service/IaClient.php';
require_once $basePath . '/Service/ScoringService.php';

class AnalyzeController
{
    private $iaClient;
    private $promptBuilder;
    private $scoringService;

    public function __construct()
    {
        $this->iaClient = new IaClient();
        $this->promptBuilder = new PromptBuilder();
        $this->scoringService = new ScoringService();
    }

    public function analyze()
    {
        try {
            // Récupérer les données POST
            $input = json_decode(file_get_contents('php://input'), true);

            // Valider les données
            if (!$this->validateInput($input)) {
                http_response_code(400);
                echo json_encode(['error' => 'Données manquantes ou invalides']);
                return;
            }

            // Construire le prompt pour l'IA
            $prompt = $this->promptBuilder->buildAnalysisPrompt(
                $input['editorialGuidelines'],
                $input['clientGuidelines'],
                $input['text']
            );

            // Appeler l'IA
            $iaResponse = $this->iaClient->analyze($prompt);

            if (!$iaResponse) {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors de l\'analyse IA']);
                return;
            }

            // Évaluer et scorer la réponse
            $scoring = $this->scoringService->score($iaResponse);

            // Retourner au frontend
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'scoring' => $scoring
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function validateInput($input)
    {
        return isset($input['editorialGuidelines']) &&
               isset($input['clientGuidelines']) &&
               isset($input['text']) &&
               !empty(trim($input['text'])) &&
               strlen($input['editorialGuidelines']) <= MAX_TEXT_LENGTH &&
               strlen($input['clientGuidelines']) <= MAX_TEXT_LENGTH &&
               strlen($input['text']) <= MAX_TEXT_LENGTH;
    }
}
