<?php 
class NoteRepository extends Repository {

    function getAll(){

        $query = "SELECT * FROM notes";
        $result = $this->connection->query( $query );
        $result = $result->fetchAll( PDO::FETCH_ASSOC );

        $notes = [];
        foreach( $result as $data ){
            $notes[] = new Note( $data );
        }

        return $notes;  

    }

}