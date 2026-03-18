<?php
/**
 * Login Page
 * Handles admin authentication
 */

session_start();

// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(__DIR__ . '/.env');

$error = '';
$timeout = isset($_GET['timeout']) && $_GET['timeout'] == '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $expectedUser = $_ENV['ADMIN_USERNAME'] ?? 'admin';
    $expectedPass = $_ENV['ADMIN_PASSWORD'] ?? '';

    // Use hash_equals to prevent timing attacks
    if (hash_equals($expectedUser, $username) && hash_equals($expectedPass, $password)) {
        $_SESSION['authenticated'] = true;
        $_SESSION['last_activity'] = time();
        $_SESSION['username'] = $username;

        // Redirect to admin panel
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

// Already logged in?
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#F37021',
                            hover: '#F04E23',
                        },
                        dark: {
                            DEFAULT: '#3D191A',
                            light: '#4a2122',
                        },
                        light: {
                            DEFAULT: '#FFF1E2',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-dark-light rounded-xl p-8 shadow-lg">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-light mb-2">Admin Login</h1>
                <p class="text-light/70">Enter your credentials to access the dashboard</p>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-900/50 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($timeout): ?>
            <div class="bg-yellow-900/50 border border-yellow-700 text-yellow-200 px-4 py-3 rounded-lg mb-6">
                Your session has expired. Please login again.
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-light/70 mb-2">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autofocus
                        class="w-full px-4 py-3 bg-dark border border-dark-light rounded-lg text-light placeholder-light/50 focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Enter your username"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-light/70 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 bg-dark border border-dark-light rounded-lg text-light placeholder-light/50 focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Enter your password"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary-hover text-light px-6 py-3 rounded-lg font-medium transition-colors"
                >
                    Login
                </button>
            </form>

            <!-- Setup Instructions -->
            <?php if (!file_exists(__DIR__ . '/.env')): ?>
            <div class="mt-8 p-4 bg-yellow-900/30 border border-yellow-700 rounded-lg">
                <p class="text-yellow-300 text-sm font-medium mb-2">Setup Required</p>
                <p class="text-gray-400 text-sm">
                    Create a <code class="bg-gray-700 px-2 py-1 rounded">.env</code> file from
                    <code class="bg-gray-700 px-2 py-1 rounded">.env.example</code> to set your credentials.
                </p>
            </div>
            <?php endif; ?>
        </div>

        <p class="text-center text-light/60 text-sm mt-6">
            Dynamic Image Generator v1.0
        </p>
    </div>
</body>
</html>
