<?php

/**
 * Please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
 * in the application's header (in views/_header.php). To add new languages simply copy this file,
 * and create a language switch in your root files.
 */

// login & registration classes
define("MESSAGE_ACCOUNT_NOT_ACTIVATED", "Votre compte n'est pas encore activé. Cliquez sur le lien de confirmation dans l'e-mail.");
define("MESSAGE_CAPTCHA_WRONG", "Captcha invalide !");
define("MESSAGE_COOKIE_INVALID", "Cookie invalide !");
define("MESSAGE_DATABASE_ERROR", "Problème de connection à la base de donnéeses.");
define("MESSAGE_EMAIL_ALREADY_EXISTS", "Cette adresse e-mail est déjà enregistrée. Utilisez le \"J'ai oublié mon mot de passe\" si vous ne vous en souvenez plus.");
define("MESSAGE_EMAIL_CHANGE_FAILED", "Désolé, le changement de votre e-mail a échoué.");
define("MESSAGE_EMAIL_CHANGED_SUCCESSFULLY", "Votre adresse e-mail a été changé. La nouvelle adresse est ");
define("MESSAGE_EMAIL_EMPTY", "L'adresse e-mail est obligatoire");
define("MESSAGE_EMAIL_INVALID", "Votre adresse e-mail n'est pas valide");
define("MESSAGE_EMAIL_SAME_LIKE_OLD_ONE", "Cette adresse est la même que l'actuelle. Choisissez en une autre.");
define("MESSAGE_EMAIL_TOO_LONG", "L'adresse e-mail ne peut pas dépasser 64 caractères");
define("MESSAGE_LINK_PARAMETER_EMPTY", "Empty link parameter data.");
define("MESSAGE_LOGGED_OUT", "Vous avez été déconnecté.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("MESSAGE_LOGIN_FAILED", "La connection a échouée.");
define("MESSAGE_OLD_PASSWORD_WRONG", "Votre ancien mot de passe est faux.");
define("MESSAGE_PASSWORD_BAD_CONFIRM", "Entrez les deux mêmes mots de passe.");
define("MESSAGE_PASSWORD_CHANGE_FAILED", "Le changement de mot de passe a échoué.");
define("MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY", "Le changement de mot de passe a réussi !");
define("MESSAGE_PASSWORD_EMPTY", "Entrez un mot de passe");
define("MESSAGE_PASSWORD_RESET_MAIL_FAILED", "Password reset mail NOT successfully sent! Error: ");
define("MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT", "Password reset mail successfully sent!");
define("MESSAGE_PASSWORD_TOO_SHORT", "Password has a minimum length of 6 characters");
define("MESSAGE_PASSWORD_WRONG", "Wrong password. Try again.");
define("MESSAGE_PASSWORD_WRONG_3_TIMES", "You have entered an incorrect password 3 or more times already. Please wait 30 seconds to try again.");
define("MESSAGE_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL", "Sorry, no such id/verification code combination here...");
define("MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL", "Activation réussie ! Vous pouvez maintenant vous connectez !<br><br>");
define("MESSAGE_REGISTRATION_FAILED", "Désolé, votre inscription a échouée. Revenez plus tard et réessayez.");
define("MESSAGE_RESET_LINK_HAS_EXPIRED", "Votre lien de réinitialisation a expiré. Utilisez ce lien dans l'heure.");
define("MESSAGE_VERIFICATION_MAIL_ERROR", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("MESSAGE_VERIFICATION_MAIL_NOT_SENT", "Verification Mail NOT successfully sent! Error: ");
define("MESSAGE_VERIFICATION_MAIL_SENT", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.");
define("MESSAGE_USER_DOES_NOT_EXIST", "Cet utilisateur n'existe pas");
define("MESSAGE_USERNAME_CHANGE_FAILED", "Sorry, your chosen username renaming failed");
define("MESSAGE_USERNAME_CHANGED_SUCCESSFULLY", "Your username has been changed successfully. New username is ");
define("MESSAGE_USERNAME_EMPTY", "Username field was empty");
define("MESSAGE_USERNAME_EXISTS", "Sorry, that username is already taken. Please choose another one.");
define("MESSAGE_USERNAME_INVALID", "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters");
define("MESSAGE_USERNAME_SAME_LIKE_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");

// views
define("WORDING_BACK_TO_LOGIN", "Page de connexion");
define("WORDING_CHANGE_EMAIL", "Changer l'e-mail");
define("WORDING_CHANGE_PASSWORD", "Changer le mot de passe");
define("WORDING_CHANGE_USERNAME", "Changer le nom d'utilisateur (juste des chiffres et des lettres, de 2 à 64 caractères)");
define("WORDING_CURRENTLY", "currently");
define("WORDING_EDIT_USER_DATA", "Modifiez les données utilisateur");
define("WORDING_EDIT_YOUR_CREDENTIALS", "You are logged in and can edit your credentials here");
define("WORDING_FORGOT_MY_PASSWORD", "J'ai oublié mon mot de passe");
define("WORDING_LOGIN", "Connexion");
define("WORDING_LOGOUT", "Déconnexion");
define("WORDING_NEW_EMAIL", "Nouvel e-mail");
define("WORDING_NEW_PASSWORD", "Nouveau mot de passe");
define("WORDING_NEW_PASSWORD_REPEAT", "Répétez le nouveau mot de passe");
define("WORDING_NEW_USERNAME", "Nouveau nom d'utilisateur");
define("WORDING_OLD_PASSWORD", "Votre ancien mot de passe");
define("WORDING_PASSWORD", "Mot de passe");
define("WORDING_PROFILE_PICTURE", "Votre photo de profil (de gravatar):");
define("WORDING_REGISTER", "S'enregistrer");
define("WORDING_REGISTER_NEW_ACCOUNT", "Enregistrer un nouveau compte");
define("WORDING_REGISTRATION_CAPTCHA", "Recopiez ces caractères");
define("WORDING_REGISTRATION_EMAIL", "E-mail utilisateur (entrez une vraie adress, vous allez recevoir un mail d'activation avec un lien)");
define("WORDING_REGISTRATION_PASSWORD", "Mot de passe (min. 6 caractères !)");
define("WORDING_REGISTRATION_PASSWORD_REPEAT", "Retapez le mot de passe");
define("WORDING_REGISTRATION_USERNAME", "Nom d'utilisateur (juste des chiffres et des lettres, de 2 à 64 caractères)");
define("WORDING_REMEMBER_ME", "Gardez moi connecté (pour 2 semaines)");
define("WORDING_REQUEST_PASSWORD_RESET", "Demandez une mise réinitialisation du mot de passe. Entrez votre nom d'utilisateur et vous recevrez un mail avec des instructions :");
define("WORDING_RESET_PASSWORD", "Réinitialisez mon mot de passe");
define("WORDING_SUBMIT_NEW_PASSWORD", "Soumettre un nouveau mot de passe");
define("WORDING_USERNAME", "Nom d'utilisateur");
define("WORDING_YOU_ARE_LOGGED_IN_AS", "Vous êtes connecté en tant que ");
