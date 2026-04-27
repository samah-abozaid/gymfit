<?php

class CSRFTokenManager
{
    public function generateCSRFToken(): void
    {
        // Génère TOUJOURS un nouveau token si vide
        if (empty($_SESSION['csrf-token'])) {
            $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
        }
    }

    public function validateCSRFToken(string $token): bool
    {
        if (empty($_SESSION['csrf-token'])) {
            return false;
        }

        $isValid = hash_equals($_SESSION['csrf-token'], $token);

        if ($isValid) {
            // Régénère après validation
            $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
        }

        return $isValid;
    }
}