/**
 * Module d'appels API
 * Gère la communication avec le backend
 */

const API_BASE_URL = 'http://localhost:8000/analyze'; // À ajuster selon votre configuration

class APIClient {
    /**
     * Envoie une requête d'analyse au backend
     */
    static async analyze(editorialGuidelines, clientGuidelines, text) {
        try {
            const response = await fetch(API_BASE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    editorialGuidelines: editorialGuidelines,
                    clientGuidelines: clientGuidelines,
                    text: text
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `Erreur API: ${response.status}`);
            }

            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Erreur API:', error);
            throw error;
        }
    }
}
