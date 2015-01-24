<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Serveur de depot</title>
    <!-- <link rel="stylesheet" media="screen" href="css/style.css" type="text/css"/> -->
</head>
<body>
<div id="logo_esirem"><img src="img/logo.png"/></div>

<div id="en-tete"><h1>Serveur de dépôt de l'ESIREM</h1></div>

<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php
// show potential errors / feedback (from registration object)
if (isset($pre_registration)) {
    if ($pre_registration->errors) {
        foreach ($pre_registration->errors as $error) {
            echo $error;
        }
    }
    if ($pre_registration->messages) {
        foreach ($pre_registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php
// show potential errors / feedback (from creation_depot object)
if (isset($creation)) {
    if ($creation->errors) {
        foreach ($creation->errors as $error) {
            echo $error;
        }
    }
    if ($creation->messages) {
        foreach ($creation->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php
// show potential errors / feedback (from creation_depot object)
if (isset($depot_rapport)) {
    if ($depot_rapport->errors) {
        foreach ($depot_rapport->errors as $error) {
            echo $error;
        }
    }
    if ($depot_rapport->messages) {
        foreach ($depot_rapport->messages as $message) {
            echo $message;
        }
    }
}
?>

<?php
// show potential errors / feedback (from creation_depot object)
if (isset($liste_depot)) {
    if ($liste_depot->errors) {
        foreach ($liste_depot->errors as $error) {
            echo $error;
        }
    }
    if ($liste_depot->messages) {
        foreach ($liste_depot->messages as $message) {
            echo $message;
        }
    }
}
?>
