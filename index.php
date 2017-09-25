<?php 
require "flight/Flight.php"; 
require "autoload.php";

//Enregistrer en global dans Flight le BddManager
Flight::set("BddManager", new BddManager());

//Lire toutes les notes
Flight::route("GET /notes", function(){

    $bddManager = Flight::get("BddManager");
    $repo = $bddManager->getNoteRepository();
    $notes = $repo->getAll();

    echo json_encode ( $notes );

});

//Récuperer la note @id
Flight::route("GET /note/@id", function( $id ){
    
});

//Créer une note
Flight::route("POST /note", function(){
    
});

//Supprimer la note @id
Flight::route("DELETE /note/@id", function( $id ){
    
});

Flight::start();