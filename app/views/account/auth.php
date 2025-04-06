session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /webbanhang/Auth/login");
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /webbanhang/");
        exit();
    }
}
