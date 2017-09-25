<?php 
require "flight/Flight.php"; 
require "autoload.php";

//Lire toutes les notes
Flight::route("GET /notes", function(){

    $pdo = Connection::getConnection();
    $query = "SELECT * FROM notes";
    $result = $pdo->query( $query );
    $result = $result->fetchAll(PDO::FETCH_ASSOC); //Tableau associatif

    $notes = [];
    foreach( $result as $datas ){
        $notes[] = new Note( $datas );
    }

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