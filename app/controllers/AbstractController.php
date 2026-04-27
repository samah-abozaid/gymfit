 <?php


abstract class AbstractController
{

protected function render(string $view, array $data = []): void
{
    // extract($data);

    $basePath = __DIR__ . '/../views/';

    require_once $basePath . 'layout/header.phtml';
    require_once $basePath . $view . '.phtml';
    require_once $basePath . 'layout/footer.phtml';
}
    // Redirection
    protected function redirect(string $route): void
    {
        header("Location: index.php?route=" . $route);
        exit;
    }

    // Vérifie si connecté
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    // Vérifie si admin
    protected function isAdmin(): bool
    {
        return isset($_SESSION['user']) 
            && $_SESSION['user']['role'] === 'admin';
    }

    // Protège une route admin
    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('login');
        }
    }

    // Protège une route membre
    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }
}
