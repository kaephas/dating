<?php
/*
 * Kaephas Kain
 * 5-20-19
 * database.php
 *
 * Database class
 */

//CREATE TABLE member
//(
//    member_id INT PRIMARY KEY AUTO_INCREMENT,
//  fname VARCHAR(40) NOT NULL,
//  lname VARCHAR(40) NOT NULL,
//  age INT NOT NULL,
//  gender VARCHAR(6),
//  phone VARCHAR(15) NOT NULL,
//  email VARCHAR(255) NOT NULL,
//  state CHAR(20) NOT NULL,
//  seeking VARCHAR(6),
//  bio TEXT,
//  premium TINYINT(1) NOT NULL,
//  image VARCHAR(80) NOT NULL
//);
//
//CREATE TABLE interest
//(
//    interest_id INT PRIMARY KEY AUTO_INCREMENT,
//  interest VARCHAR(20),
//  type VARCHAR(7)
//);
//
//CREATE TABLE member_interest
//(
//    member_id INT,
//  interest_id INT,
//  PRIMARY KEY (member_id, interest_id),
//  FOREIGN KEY (member_id) REFERENCES member(member_id),
//  FOREIGN KEY (interest_id) REFERENCES interest(interest_id)
//);

require '/home/kaephasg/config.php';

class Database
{
    private $_dbh;

    function __construct()
    {
        $this->connect();
    }

    function connect()
    {
        try {
            // instantiate a database object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            return $this->_dbh;
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    function getInterests()
    {
        $indoor = array();
        $outdoor = array();

        $sql = "SELECT interest_id, interest, type FROM interest";

        $statement = $this->_dbh->prepare($sql);

        $statement->execute();

        $result = $statement->fetchAll(2);
        foreach($result as $row) {
            if($row['type'] == 'indoor') {
                $indoor[$row['interest_id']] = $row['interest'];
            } else {
                $outdoor[$row['interest_id']] = $row['interest'];
            }
        }
        $lists = array($indoor, $outdoor, count($result));

        return $lists;
    }


    function insertMember($member)
    {
//        member_id INT PRIMARY KEY AUTO_INCREMENT,
//  fname VARCHAR(40) NOT NULL,
//  lname VARCHAR(40) NOT NULL,
//  age INT NOT NULL,
//  gender VARCHAR(6),
//  phone VARCHAR(15) NOT NULL,
//  email VARCHAR(255) NOT NULL,
//  state CHAR(20) NOT NULL,
//  seeking VARCHAR(6),
//  bio TEXT,
//  premium TINYINT(1) NOT NULL,
//  image VARCHAR(80) NOT NULL
        if(get_class($member) == 'PremiumMember') {
            $premium = 1;
        } else {
            $premium = 0;
        }

        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium, image)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :image)";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':fname', $member->getFname());
        $statement->bindParam(':lname', $member->getLname());
        $statement->bindParam(':age', $member->getAge(), 1);
        $statement->bindParam(':gender', $member->getGender());
        $statement->bindParam(':phone', $member->getPhone());
        $statement->bindParam(':email', $member->getEmail());
        $statement->bindParam(':state', $member->getState());
        $statement->bindParam(':seeking', $member->getSeeking());
        $statement->bindParam(':bio', $member->getBio());
        $statement->bindParam(':premium', $premium, 1);
        $statement->bindParam(':image', $member->getImage());

        $statement->execute();

        if(get_class($member) == 'PremiumMember') {
            $id = $this->_dbh->lastInsertId();

            $sql = "INSERT INTO member_interest (member_id, interest_id)
                    VALUES (:id, :interest)";

            $select = "SELECT interest_id FROM interest
                        WHERE interest=:interest";

            $statement = $this->_dbh->prepare($sql);
            $selectStmt = $this->_dbh->prepare($select);

            $indoor = $member->getIndoorInterests();
            $outdoor = $member->getOutdoorInterests();

            foreach($indoor as $interest) {

                // get interest_id
                $selectStmt->bindParam(':interest', $interest);
                $selectStmt->execute();
                $intId = $selectStmt->fetch(2);

                // add member_id / interest_id to member_interest table
                $statement->bindParam(':id', $id);
                $statement->bindParam(':interest', $intId['interest_id']);

                $statement->execute();
            }

            foreach($outdoor as $interest) {
                // get interest_id
                $selectStmt->bindParam(':interest', $interest);
                $selectStmt->execute();
                $intId = $selectStmt->fetch(2);

                $statement->bindParam(':id', $id);
                $statement->bindParam(':interest', $intId['interest_id']);

                $statement->execute();
            }
        }
    }

    function getMembers()
    {

    }

    function getMember($member_id)
    {

    }

}