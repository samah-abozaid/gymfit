 <?php


abstract class AbstractController
{
    // Charge une vue .phtml
    protected function render(string $view, array $data = []): void
    {
        // Extrait les variables du tableau
        // ['courses' => [...]] devient $courses dans la vue
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.phtml';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found : " . $view);
        }
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
