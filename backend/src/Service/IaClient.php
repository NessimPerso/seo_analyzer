<?php
/**
 * Client pour les appels API à l'IA (OpenAI / ChatGPT)
 */

class IaClient
{
    private $apiKey;
    private $endpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = API_KEY_OPENAI;
    }

    public function analyze($prompt)
    {
        if (empty($this->apiKey)) {
            throw new Exception('Clé API OpenAI non configurée');
        }

        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un expert en analyse SEO et contenu éditorial.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000
        ];

        $response = $this->sendRequest($payload);

        if ($response === null) {
            return null;
        }

        // Extraire le contenu JSON de la réponse
        $content = $response['choices'][0]['message']['content'] ?? '';
        
        // Nettoyer la réponse si elle est entourée de ```json ... ```
        $content = preg_replace('/^```json\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);

        return json_decode($content, true);
    }

    private function sendRequest($payload)
    {
        $ch = curl_init($this->endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, API_TIMEOUT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        // Désactiver la vérification SSL en développement (temporaire)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Debug log
        error_log('OpenAI Response - Code: ' . $httpCode . ', Error: ' . $curlError . ', Response: ' . substr($response, 0, 500));

        if ($httpCode !== 200) {
            throw new Exception('Erreur API: ' . $httpCode . ' - ' . ($response ?: $curlError));
        }

        return json_decode($response, true);
    }
}
