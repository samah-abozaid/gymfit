<?php

use PHPUnit\Framework\TestCase;

class CSRFTokenManagerTest extends TestCase
{
    private CSRFTokenManager $manager;

    protected function setUp(): void
    {
        $_SESSION = [];
        $this->manager = new CSRFTokenManager();
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testGenerateCreatesTokenWhenSessionIsEmpty(): void
    {
        $this->manager->generateCSRFToken();

        $this->assertArrayHasKey('csrf-token', $_SESSION);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $_SESSION['csrf-token']);
    }

    public function testGenerateDoesNotOverwriteExistingToken(): void
    {
        $this->manager->generateCSRFToken();
        $existingToken = $_SESSION['csrf-token'];

        $this->manager->generateCSRFToken();

        $this->assertSame($existingToken, $_SESSION['csrf-token']);
    }

    public function testValidateReturnsFalseWhenNoTokenInSession(): void
    {
        $this->assertFalse($this->manager->validateCSRFToken('nimporte-quoi'));
    }

    public function testValidateReturnsFalseForWrongToken(): void
    {
        $this->manager->generateCSRFToken();

        $this->assertFalse($this->manager->validateCSRFToken('token-invalide'));
    }

    public function testWrongTokenDoesNotRegenerateSessionToken(): void
    {
        $this->manager->generateCSRFToken();
        $existingToken = $_SESSION['csrf-token'];

        $this->manager->validateCSRFToken('token-invalide');

        $this->assertSame($existingToken, $_SESSION['csrf-token']);
    }

    public function testValidateReturnsTrueForCorrectToken(): void
    {
        $this->manager->generateCSRFToken();
        $token = $_SESSION['csrf-token'];

        $this->assertTrue($this->manager->validateCSRFToken($token));
    }

    public function testTokenIsRegeneratedAfterSuccessfulValidation(): void
    {
        $this->manager->generateCSRFToken();
        $token = $_SESSION['csrf-token'];

        $this->manager->validateCSRFToken($token);

        $this->assertNotSame($token, $_SESSION['csrf-token']);
        // L'ancien token ne doit plus etre accepte (protection contre le rejeu)
        $this->assertFalse($this->manager->validateCSRFToken($token));
    }
}
