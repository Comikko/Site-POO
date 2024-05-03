<?php

namespace Services;
//include_once __DIR__ . '/../templates/register.php';
require_once(__DIR__ . '/../security/security.php');
require_once(__DIR__ . '/../model/model.php');
use Security\SecurityFunctions;
use Model\Model;
class ActionHandler {
    public static function handleRegisterAction() {
        $model = new Model();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le jeton CSRF
            SecurityFunctions::verifyCsrfToken();

            // Récupérer les données du formulaire
            $nom = SecurityFunctions::sanitizeInput($_POST['nom']);
            $prenom = SecurityFunctions::sanitizeInput($_POST['prenom']);
            $adresse = SecurityFunctions::sanitizeInput($_POST['adresse']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Valider le formulaire
            $errors = SecurityFunctions::validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

            // Si des erreurs sont présentes, afficher le formulaire avec les erreurs
            if (!empty($errors)) {
                // Ajouter les erreurs au tableau de données pour les afficher dans le formulaire
                $data['errors'] = $errors;
                include_once __DIR__ . '/../templates/register.php';
            } else {
                // Appeler la fonction pour enregistrer l'utilisateur
                $error = $model::registerUser($model,$nom, $prenom, $adresse, $email, $password, $confirmPassword);

                // Si l'enregistrement est réussi, rediriger vers la page de connexion
                if ($error === true) {
                    header("Location: index.php?action=login");
                    exit();
                } else {
                    // En cas d'erreur, afficher le message d'erreur sur la page d'inscription
                    // Ajouter le message d'erreur au tableau de données pour l'afficher dans le formulaire
                    $data['error'] = $error;
                    include_once __DIR__ . '/../templates/register.php';
                }
            }
        } else {
            // Afficher le formulaire d'inscription si la requête n'est pas de type POST
            include_once __DIR__ . '/../templates/register.php';}
    }

    public static function handleLoginAction() {
        $model = new Model();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le jeton CSRF ici avant d'appeler loginUser
            SecurityFunctions::verifyCsrfToken();

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $error = $model::loginUser($model,$email, $password);

            if($error === true){
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                include_once __DIR__ . '/../templates/login.php';
            }
        } else {
            include_once __DIR__ . '/../templates/login.php';
        }
    }

    public static function handleUpdateAction() {
        $model = new Model();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            SecurityFunctions::verifyCsrfToken();

            $id = $_SESSION['user_id'];
            $nom = SecurityFunctions::sanitizeInput($_POST['nom']);
            $prenom = SecurityFunctions::sanitizeInput($_POST['prenom']);
            $adresse = SecurityFunctions::sanitizeInput($_POST['adresse']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            $errors = SecurityFunctions::validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

            if (!empty($errors)) {
                $data['errors'] = $errors;
                include_once __DIR__ . '/../templates/update.php';
            } else {

                $error = $model::updateUserInfo($model,$id, $nom, $prenom,$adresse, $email, $password, $confirmPassword);

                if ($error === true) {
                    header("Location: index.php?action=dashboard");
                    exit();
                } else {
                    include_once __DIR__ . '/../templates/update.php';
                }
            }

        } else {
            include_once __DIR__ . '/../templates/update.php';
        }
    }

    public static function handleCloseAction() {
        $model = new Model();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];

            SecurityFunctions::verifyCsrfToken();

            $model::closeAccount($model,$id);

            session_destroy();

            header("Location: index.php");
            exit();
        }
    }

    public static function handleLogoutAction() {
        // Détruire la session
        session_destroy();

        // Rediriger vers la page d'accueil
        header("Location: index.php");
        exit();
    }
}

// function handleRegisterAction() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         // Vérifier le jeton CSRF
//         functions\verifyCsrfToken();

//         // Récupérer les données du formulaire
//         $nom = functions\sanitizeInput($_POST['nom']);
//         $prenom = functions\sanitizeInput($_POST['prenom']);
//         $adresse = functions\sanitizeInput($_POST['adresse']);
//         $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
//         $password = $_POST['password'];
//         $confirmPassword = $_POST['confirm_password'];

//         // Valider le formulaire
//         $errors = functions\validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

//         // Si des erreurs sont présentes, afficher le formulaire avec les erreurs
//         if (!empty($errors)) {
//             // Ajouter les erreurs au tableau de données pour les afficher dans le formulaire
//             $data['errors'] = $errors;
//             include_once 'templates/register.php';
//         } else {
//             // Appeler la fonction pour enregistrer l'utilisateur
//             $error = registerUser($nom, $prenom, $adresse, $email, $password, $confirmPassword);

//             // Si l'enregistrement est réussi, rediriger vers la page de connexion
//             if ($error === true) {
//                 header("Location: index.php?action=login");
//                 exit();
//             } else {
//                 // En cas d'erreur, afficher le message d'erreur sur la page d'inscription
//                 // Ajouter le message d'erreur au tableau de données pour l'afficher dans le formulaire
//                 $data['error'] = $error;
//                 include_once 'templates/register.php';
//             }
//         }
//     } else {
//         // Afficher le formulaire d'inscription si la requête n'est pas de type POST
//         include_once 'templates/register.php';
//     }
// }

// function handleLoginAction() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         // Vérifier le jeton CSRF ici avant d'appeler loginUser
//         functions\verifyCsrfToken();

//         $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
//         $password = $_POST['password'];

//         $error = loginUser($email, $password);

//         if($error === true){
//             header("Location: index.php?action=dashboard");
//             exit();
//         } else {
//             include_once 'templates/login.php';
//         }
//     } else {
//         include_once 'templates/login.php';
//     }
// }

// function handleUpdateAction() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//         \functions\verifyCsrfToken();

//         $id = $_SESSION['user_id'];
//         $nom = functions\sanitizeInput($_POST['nom']);
//         $prenom = functions\sanitizeInput($_POST['prenom']);
//         $adresse = functions\sanitizeInput($_POST['adresse']);
//         $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
//         $password = $_POST['password'];
//         $confirmPassword = $_POST['confirm_password'];

//         $errors = functions\validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

//         if (!empty($errors)) {
//             $data['errors'] = $errors;
//             include_once 'templates/update.php';
//         } else {

//             $error = updateUserInfo($id, $nom, $prenom,$adresse, $email, $password, $confirmPassword);

//             if ($error === true) {
//                 header("Location: index.php?action=dashboard");
//                 exit();
//             } else {
//                 include_once 'templates/update.php';
//             }
//         }

//     } else {
//         include_once 'templates/update.php';
//     }
// }

// function handleCloseAction() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $id = $_SESSION['user_id'];

//         functions\verifyCsrfToken();

//         closeAccount($id);

//         session_destroy();

//         header("Location: index.php");
//         exit();
//     }
// }

// function handleLogoutAction() {
//     // Détruire la session
//     session_destroy();

//     // Rediriger vers la page d'accueil
//     header("Location: index.php");
//     exit();
// }


?>