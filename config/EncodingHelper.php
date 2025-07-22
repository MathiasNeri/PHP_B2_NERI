<?php
/**
 * Classe utilitaire pour la gestion de l'encodage UTF-8
 * Portfolio PHP/MVC - Projet B2
 */

class EncodingHelper {
    
    /**
     * Initialiser l'encodage UTF-8 pour l'application
     */
    public static function initUTF8() {
        // Headers HTTP pour UTF-8
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
        }
        
        // Configuration PHP pour UTF-8
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_language('uni');
        mb_regex_encoding('UTF-8');
        
        // Configuration de la base de données
        if (function_exists('getDatabaseConnection')) {
            try {
                $pdo = getDatabaseConnection();
                $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("SET CHARACTER SET utf8mb4");
                $pdo->exec("SET character_set_connection=utf8mb4");
            } catch (Exception $e) {
                // Ignorer les erreurs de connexion
            }
        }
    }
    
    /**
     * Corriger l'encodage d'une chaîne de caractères (simplifié)
     */
    public static function fixEncoding($string) {
        if (empty($string)) {
            return $string;
        }
        
        // Si la chaîne est déjà en UTF-8, la retourner telle quelle
        if (mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }
        
        // Sinon, essayer de convertir depuis Windows-1252
        return mb_convert_encoding($string, 'UTF-8', 'Windows-1252');
    }
    
    /**
     * Corriger l'encodage d'un tableau de données
     */
    public static function fixArrayEncoding($array) {
        if (!is_array($array)) {
            return self::fixEncoding($array);
        }
        
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::fixArrayEncoding($value);
            } else {
                $result[$key] = self::fixEncoding($value);
            }
        }
        
        return $result;
    }
    
    /**
     * Corriger automatiquement les données de la base de données
     */
    public static function fixDatabaseData($data) {
        if (is_array($data)) {
            return self::fixArrayEncoding($data);
        }
        
        return self::fixEncoding($data);
    }
    
    /**
     * Corriger les données POST/GET automatiquement
     */
    public static function fixInputData() {
        if (!empty($_POST)) {
            $_POST = self::fixArrayEncoding($_POST);
        }
        
        if (!empty($_GET)) {
            $_GET = self::fixArrayEncoding($_GET);
        }
        
        if (!empty($_REQUEST)) {
            $_REQUEST = self::fixArrayEncoding($_REQUEST);
        }
    }
    
    /**
     * Corriger les données de session
     */
    public static function fixSessionData() {
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION)) {
            $_SESSION = self::fixArrayEncoding($_SESSION);
        }
    }
    
    /**
     * Corriger les données affichées dans les vues
     */
    public static function fixDisplayData($data) {
        return self::fixDatabaseData($data);
    }
    
    /**
     * Fonction helper pour htmlspecialchars avec UTF-8
     */
    public static function h($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Corriger les données des projets spécifiquement
     */
    public static function fixProjectData($project) {
        if (is_array($project)) {
            $project['title'] = self::fixEncoding($project['title'] ?? '');
            $project['description'] = self::fixEncoding($project['description'] ?? '');
            $project['link'] = self::fixEncoding($project['link'] ?? '');
        }
        return $project;
    }
    
    /**
     * Corriger les données des compétences spécifiquement
     */
    public static function fixSkillData($skill) {
        if (is_array($skill)) {
            $skill['name'] = self::fixEncoding($skill['name'] ?? '');
            $skill['description'] = self::fixEncoding($skill['description'] ?? '');
            $skill['category'] = self::fixEncoding($skill['category'] ?? '');
        }
        return $skill;
    }
    
    /**
     * Corriger les données des utilisateurs spécifiquement
     */
    public static function fixUserData($user) {
        if (is_array($user)) {
            $user['username'] = self::fixEncoding($user['username'] ?? '');
            $user['email'] = self::fixEncoding($user['email'] ?? '');
            $user['bio'] = self::fixEncoding($user['bio'] ?? '');
            $user['security_question'] = self::fixEncoding($user['security_question'] ?? '');
        }
        return $user;
    }
}
?> 