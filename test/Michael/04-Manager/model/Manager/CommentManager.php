<?php

namespace model\Manager ;

use Exception;
use model\Interface\InterfaceManager;
use model\Mapping\CommentMapping;
use model\Mapping\UserMapping;
use model\OurPDO;

class CommentManager implements InterfaceManager{

    // On va stocker la connexion dans une propriété privée
    private ?OurPDO $connect = null;

    // on va passer notre connexion OurPDO
    // à notre manager lors de son instanciation
    public function __construct(OurPDO $db){
        $this->connect = $db;
    }

    // sélection de tous les articles
    public function selectAll(): ?array
    {
        // requête SQL
        $sql = "SELECT * FROM `comment` -- WHERE `comment_id`=999
         ORDER BY `comment_date_create` DESC";
        // query car pas d'entrées d'utilisateur
        $select = $this->connect->query($sql);

        // si on ne récupère rien, on quitte avec un message d'erreur
        if($select->rowCount()===0) return null;

        // on transforme nos résultats en tableau associatif
        $array = $select->fetchAll(OurPDO::FETCH_ASSOC);

        // on ferme le curseur
        $select->closeCursor();

        // on va stocker les commentaires dans un tableau
        $arrayComment = [];

        /* pour chaque valeur, on va créer une instance de classe
        CommentMapping, liée à la table qu'on va manager
        */
        foreach($array as $value){
            // on remplit un nouveau tableau contenant les commentaires
            $arrayComment[] = new CommentMapping($value);
        }

        // on retourne le tableau
        return $arrayComment;
    }

    // récupération d'un commentaire via son id avec l'utilisateur
    public function selectOneByIdWithUser(int $id): null|string|CommentMapping
    {

        // requête préparée
        $sql = "SELECT c.*, u.user_id, u.user_full_name FROM `comment` c
        LEFT JOIN `comment_has_user` h ON h.comment_comment_id = c.comment_id
        LEFT JOIN `user` u ON u.user_id = h.user_user_id
         WHERE c.comment_id= ?";

        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$id, OurPDO::PARAM_INT);
            $prepare->execute();

            // pas de résultat = null
            if($prepare->rowCount()===0) return null;

            // récupération des valeurs en tableau associatif
            $result = $prepare->fetch(OurPDO::FETCH_ASSOC);

            // création de l'instance CommentMapping
            $result1 = new CommentMapping($result);
            // création de l'instance UserMapping
            $user = new UserMapping($result);
            $result1->setUser($user);

            $prepare->closeCursor();
            
            return $result1;


        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    // récupération d'un commentaire via son id avec les informations de l'utilisateur
    public function selectOneById(int $id): null|string|CommentMapping
    {

        // requête préparée
        $sql = "SELECT * FROM `comment` WHERE `comment_id`= ?";
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$id, OurPDO::PARAM_INT);
            $prepare->execute();

            // pas de résultat = null
            if($prepare->rowCount()===0) return null;

            // récupération des valeurs en tableau associatif
            $result = $prepare->fetch(OurPDO::FETCH_ASSOC);

            // création de l'instance CommentMapping
            $result = new CommentMapping($result);

            $prepare->closeCursor();

            return $result;


        }catch(Exception $e){
            return $e->getMessage();
        }

    }

    // mise à jour d'un commentaire
    public function update(object $object): bool|string
    {

        // requête préparée
        $sql = "UPDATE `comment` SET `comment_text`=?, `comment_date_update`=? WHERE `comment_id`=?";
        // mise à jour de la date de modification
        $object->setCommentDateUpdate(date("Y-m-d H:i:s"));
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$object->getCommentText());
            $prepare->bindValue(2,$object->getCommentDateUpdate());
            $prepare->bindValue(3,$object->getCommentId(), OurPDO::PARAM_INT);

            $prepare->execute();

            $prepare->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }


    // insertion d'un commentaire
    public function insert(object $object): bool|string
    {

        // requête préparée
        $sql = "INSERT INTO `comment`(`comment_text`)  VALUES (?)";
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$object->getCommentText());

            $prepare->execute();

            $prepare->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    // suppression d'un commentaire
    public function delete(int $id): bool|string
    {
        // requête préparée
        $sql = "DELETE FROM `comment` WHERE `comment_id`=?";
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$id, OurPDO::PARAM_INT);

            $prepare->execute();

            $prepare->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

}