<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'connect.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = trim($_POST['username']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if($username == 'Piotrek' || $username == 'piotrek') {
            $errors[] = 'Piotrek jest zbanowany ;)';
        }

        // Sprawdzanie czy użytkownik już istnieje
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if($stmt->get_result()->num_rows > 0) {
            $errors[] = "Użytkownik o takiej nazwie lub email już istnieje.";
        }


        // Sprawdzanie czy email już istnieje
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if($stmt->get_result()->num_rows > 0) {
            $errors[] = "Ten adres email jest już zajęty.";
        }

        if(empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (username, lastname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $lastname, $email, $hashed_password);
            
            if($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Błąd podczas rejestracji.";
            }
        }
    } catch (Exception $e) {
        $errors[] = "Błąd podczas rejestracji: " . $e->getMessage();
        error_log("Błąd rejestracji: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/register.css">
    <link rel="stylesheet" href="./styles/stars.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="stars" id="stars"></div>

    <div id="odstep" style="height: 0px"></div>
    
    <main class="main-content">
        <div class="register-container">
            <h2>Rejestracja</h2>
            
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group center-column">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Wprowadź nazwę użytkownika"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="lastname">Nazwisko:</label>
                    <input type="text" id="lastname" name="lastname" required
                           placeholder="Wprowadź nazwisko"
                           value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required
                           placeholder="Wprowadź adres e-mail"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Wprowadź hasło">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Powtórz hasło:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           placeholder="Powtórz hasło">
                    <div class="password-match-message"></div>
                </div>
                
                <div class="password-requirements full-width">
                    Hasło musi zawierać:
                    <ul>
                        <li>Minimum 8 znaków</li>
                        <li>Przynajmniej jedną wielką literę</li>
                        <li>Nie może zawierać spacji</li>
                    </ul>
                </div>
                
                <input type="submit" value="Zarejestruj" class="full-width">
            </form>
            <p class="login-link">Masz już konto? <a href="login.php">Zaloguj się</a></p>
        </div>
    </main>

    <?php include './includes/footer.php'; ?>

    <script src="./js/stars.js"></script>
    <script>
        createStars();
    </script>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const requirementsList = document.querySelector('.password-requirements ul');
        const requirementItems = requirementsList.getElementsByTagName('li');
        const matchMessage = document.querySelector('.password-match-message');

        // Przypisanie referencji do elementów listy
        const requirements = {
            length: requirementItems[0],
            uppercase: requirementItems[1],
            spaces: requirementItems[2]
        };

        function validatePassword() {
            const password = passwordInput.value;
            
            // Sprawdzanie długości
            if (password.length >= 8) {
                requirements.length.classList.add('valid');
                requirements.length.classList.remove('invalid');
            } else {
                requirements.length.classList.add('invalid');
                requirements.length.classList.remove('valid');
            }

            // Sprawdzanie wielkiej litery
            if (/[A-Z]/.test(password)) {
                requirements.uppercase.classList.add('valid');
                requirements.uppercase.classList.remove('invalid');
            } else {
                requirements.uppercase.classList.add('invalid');
                requirements.uppercase.classList.remove('valid');
            }

            // Sprawdzanie spacji
            if (!/\s/.test(password)) {
                requirements.spaces.classList.add('valid');
                requirements.spaces.classList.remove('invalid');
            } else {
                requirements.spaces.classList.add('invalid');
                requirements.spaces.classList.remove('valid');
            }

            // Sprawdzanie zgodności haseł
            // if (confirmPasswordInput.value) {
            //     validateConfirmPassword();
            // }
        }

        function validateConfirmPassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword === '') {
                matchMessage.textContent = '';
                matchMessage.className = 'password-match-message';
                confirmPasswordInput.classList.remove('valid-input', 'invalid-input');
            } else if (password === confirmPassword) {
                // matchMessage.textContent = 'Hasła są zgodne';
                matchMessage.className = 'password-match-message valid';
                confirmPasswordInput.classList.add('valid-input');
                confirmPasswordInput.classList.remove('invalid-input');
            } else {
                matchMessage.textContent = 'Hasła nie są zgodne';
                matchMessage.className = 'password-match-message invalid';
                confirmPasswordInput.classList.add('invalid-input');
                confirmPasswordInput.classList.remove('valid-input');
            }
        }

        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);
        
        validatePassword();
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const isValid = password.length >= 8 && 
                           /[A-Z]/.test(password) && 
                           !/\s/.test(password) &&
                           password === confirmPasswordInput.value;

            if (!isValid) {
                e.preventDefault();
                if (!document.querySelector('.form-error')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error form-error';
                    errorDiv.textContent = 'Proszę poprawić błędy w formularzu przed wysłaniem.';
                    document.querySelector('form').insertBefore(errorDiv, document.querySelector('form').firstChild);
                }
            }
        });

        localStorage.setItem('hist1', '')
        localStorage.setItem('hist2', '')
        localStorage.setItem('hist3', '')
        localStorage.setItem('a-hist1', '')
        localStorage.setItem('a-hist2', '')
        localStorage.setItem('a-hist3', '')
    </script>
</body>
</html>
