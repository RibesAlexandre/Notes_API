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
    
    $status = [
        "success" => false,
        "note" => false
    ];

    $note = new Note();
    $note->setId( $id );

    $bddManager = Flight::get("BddManager");
    $repo = $bddManager->getNoteRepository();
    $note = $repo->getById( $note );

    if( $note != false ){
        $status["success"] = true;
        $status["note"] = $note;
    }

    echo json_encode( $status );

});

/**
 * Notes par utilisateur
 */
Flight::route('GET /user/@id', function($id) {

    $user = new User();
    $user->setId($id);

    $bddManager = Flight::get("BddManager");
    $repo = $bddManager->getNoteRepository();
    $notes = $repo->getByUserId($user);

    if( !$notes ) {
        return json_encode([
            'success'   =>  false
        ]);
    }

    return json_encode([
        'success'   =>  true,
        'notes'     =>  $notes,
    ]);

});

//Créer une note
Flight::route("POST /note", function(){

    $title = Flight::request()->data["title"];
    $content = Flight::request()->data["content"];

    $status = [
        "success" => false,
        "id" => 0
    ];

    if( strlen( $title ) > 0 && strlen( $content ) > 0 ) {

        $note = new Note();
        $note->setTitle( $title );
        $note->setContent( $content );

        $bddManager = Flight::get("BddManager");
        $repo = $bddManager->getNoteRepository();
        $id = $repo->save( $note );

        if( $id != 0 ){
            $status["success"] = true;
            $status["id"] = $id;
        }

    }

    echo json_encode( $status ); 
    
});

//Supprimer la note @id
Flight::route("DELETE /note/@id", function( $id ){

    $status = [
        "success" => false
    ];

    $note = new Note();
    $note->setId( $id );

    $bddManager = Flight::get("BddManager");
    $repo = $bddManager->getNoteRepository();
    $rowCount = $repo->delete( $note );

    if( $rowCount == 1 ){
        $status["success"] = true;
    }

    echo json_encode( $status );
    
});

Flight::route("PUT /note/@id", function( $id ){

    //Pour récuperer des données PUT -> les données sont encodé en json string
    //avec ajax, puis décodé ici en php
    $json = Flight::request()->getBody();
    $_PUT = json_decode( $json , true);//true pour tableau associatif

    $status = [
        "success" => false
    ];

    if( isset( $_PUT["title"] ) && isset( $_PUT["content"] ) ){

        $title = $_PUT["title"];
        $content = $_PUT["content"];

        $note = new Note();
        $note->setId( $id );
        $note->setTitle( $title );
        $note->setContent( $content );

        $bddManager = Flight::get("BddManager");
        $repo = $bddManager->getNoteRepository();
        $rowCount = $repo->save( $note );

        if( $rowCount == 1 ){
            $status["success"] = true;
        }

    }

    echo json_encode( $status );

});

/**
 * Traitement de la requête de connexion
 */
Flight::route('POST /connexion', function() {
    $json = Flight::request()->getBody();
    $inputs = json_decode( $json , true);

    if( isset($inputs['email']) && isset($inputs['password']) ) {
        $bddManager = Flight::get("BddManager");
        $repo = $bddManager->getUserRepository();

        if( $repo->login($inputs['email'], $inputs['password']) ) {
            return json_encode([
                'success'   =>  true
            ]);
        }

        return json_encode([
            'success'   =>  false,
        ]);
    }

    return json_encode([
        'success'   =>  false,
    ]);
});

/**
 * Traitement de la requête d'inscription
 */
Flight::Route('POST /inscription', function() {
    $json = Flight::request()->getBody();
    $inputs = json_decode( $json , true);

    $error = false;
    foreach( $inputs as $key => $value ) {
        if( !isset($inputs[$key]) ) {
            $error = true;
        }
    }

    if( !$error ) {
        $bddManager = Flight::get("BddManager");
        $repo = $bddManager->getUserRepository();

        $user = new User();
        $user->setEmail($inputs['email']);
        $user->setFirstName($inputs['firstname']);
        $user->setLastName($inputs['lastname']);
        $user->setPassword($inputs['password']);

        $rowCount = $repo->save();
        if( $rowCount ) {
            return json_encode([
                'success'   =>  true,
                'firstname' =>  $user->getFirstName(),
            ]);
        }

        return json_encode([
            'success'   =>  false
        ]);
    }

    return json_encode([
        'success'   =>  false
    ]);

});

Flight::start();