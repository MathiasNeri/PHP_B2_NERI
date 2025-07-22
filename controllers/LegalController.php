<?php

/**
 * Contrôleur pour les pages légales
 */
class LegalController {
    
    public function __construct() {
        // Pas besoin d'authentification pour les pages légales
    }
    
    /**
     * Afficher les mentions légales
     */
    public function mentions() {
        $this->renderLegalPage('mentions', 'Mentions Légales');
    }
    
    /**
     * Afficher la politique de confidentialité
     */
    public function privacy() {
        $this->renderLegalPage('privacy', 'Politique de Confidentialité');
    }
    
    /**
     * Afficher les conditions d'utilisation
     */
    public function terms() {
        $this->renderLegalPage('terms', 'Conditions d\'Utilisation');
    }
    
    /**
     * Afficher la page RGPD
     */
    public function gdpr() {
        $this->renderLegalPage('gdpr', 'RGPD - Protection des Données');
    }
    
    /**
     * Rendre une page légale
     */
    private function renderLegalPage($page, $title) {
        include __DIR__ . "/../views/legal/{$page}.php";
    }
} 