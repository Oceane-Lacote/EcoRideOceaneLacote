<?php

// auth.php
header("Content-Type: application/json"); // Pour que le front-end puisse traiter le retour comme du JSON
header("Access-Control-Allow-Origin: *"); // Permettre les requêtes cross-origin (facultatif en prod)

// Connexion à la base de données
$servername = "localhost";
$username = "administrateur";
$password = "@dminEc0Rid3!";
$dbname = "ecoride";

$conn = new mysqli($servername, $username, $password, $dbname);

// Récupère les données d'un utilisateur (profil)
function getUserDataFromDB($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupère les véhicules d'un utilisateur, avec les préférences
function getVehiclesFromDB($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM véhicule WHERE utilisateur_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter un véhicule avec des préférences dans la base de données
function addVehicleToDB($userId, $vehicleName, $vehicleModel, $vehicleColor, $vehicleType, $vehiclePlate, $vehicleDate, $preferences) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO véhicule (utilisateur_id, nom, modèle, couleur, type, plaque, date_enregistrement, préférences) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $vehicleName, $vehicleModel, $vehicleColor, $vehicleType, $vehiclePlate, $vehicleDate, $preferences]);
}