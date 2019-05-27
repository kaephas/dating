<?php
/*
 * Kaephas Kain
 * 5-20-19
 * database.php
 *
 * Connects to Dating database with methods to retrieve Member data and Interests
 * and insert new members
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
//  image VARCHAR(80) NOT NULL DEFAULT '/images/profile.jpg'
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

/**
 * Class Dating represents a database connection
 *
 * Connects to Dating database with functions to insert and select
 * @author Kaephas Kain
 * @version 1.0
 *
 */
class Database
{
    private $_dbh;

    /**
     * Database constructor connects to database
     *
     * @return void
     */
    function __construct()
    {
        $this->connect();
    }

    /**
     * Returns the database connection or echos error message
     *
     * @return PDO database connection
     */
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

    /**
     * Returns an array of indoor and outdoor interest -- associative arrays of interest_id => interests
     * and the count of total items in the arrays
     *
     * @return array $lists     [indoor array, outdoor array, interest count]
     */
    function getAllInterests()
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


    /**
     * Inserts data from a Member or Premium member into the database
     *
     * @param Member|PremiumMember $member    the Member or PremiumMember to be added
     * @return void
     */
    function insertMember($member)
    {
        // set premium status
        if(get_class($member) == 'PremiumMember') {
            $premium = 1;
        } else {
            $premium = 0;
        }
        // insert query
        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium, image)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :image)";

        $statement = $this->_dbh->prepare($sql);

        // bind all parameters
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

        // link interests to member if Premium
        if(get_class($member) == 'PremiumMember') {
            // get id of entered member
            $id = $this->_dbh->lastInsertId();

            // junction table insert string
            $sql = "INSERT INTO member_interest (member_id, interest_id)
                    VALUES (:id, :interest)";

            // get ids for interests
            $select = "SELECT interest_id FROM interest
                        WHERE interest=:interest";

            $statement = $this->_dbh->prepare($sql);
            $selectStmt = $this->_dbh->prepare($select);

            $indoor = $member->getIndoorInterests();
            $outdoor = $member->getOutdoorInterests();
            // add each member -> interest to the junction table
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

                // add member_id / interest_id to member_interest table
                $statement->bindParam(':id', $id);
                $statement->bindParam(':interest', $intId['interest_id']);

                $statement->execute();
            }
        }
    }

    /**
     * Retrieves all members from the Dating database
     *
     * @return array  $members  array of arrays of member data
     */
    function getMembers()
    {
        global $f3;
        $db = $this->_dbh;

        // select query
        $sql = "SELECT member_id, fname, lname, age, gender, phone, email, state, seeking, premium
                FROM member
                ORDER BY lname";
        $statement = $db->prepare($sql);
        $statement->execute();

        // store all results
        $members = $statement->fetchAll(2);

        // match interests to member query to be used only with premium members
        $sql = "SELECT interest FROM interest, member, member_interest
                WHERE member.member_id=:id 
                  AND member.member_id = member_interest.member_id 
                  AND interest.interest_id = member_interest.interest_id";
        $statement = $db->prepare($sql);

        // concatenate name and change state string for each member for readability
        foreach($members as $index => $row) {
            // concatenate full name for each member
            $name = $row['fname'] . ' ' . $row['lname'];
            $members[$index]['name'] = $name;

            // get state abbreviation
            $state = ucwords(strtolower($row['state']));
            $abbr = $f3->get("states[$state]");
            $members[$index]['state'] = $abbr;

            // run interests query for premium members
            if($row['premium'] == 1) {
                // get array of interests
                $statement->bindParam(':id', $row['member_id']);
                $statement->execute();
                $result = $statement->fetchAll(2);

                $interests = array();
                foreach($result as $interest){
                    // add each interest to the string
                    $interests[] = $interest['interest'];
                }
                $interests = implode(', ', $interests);

                $members[$index]['interests'] = $interests;
            }
        }
        return $members;
    }

    /**
     * Creates a Member/Premium object that matches the id in the database
     *
     * @param int $member_id    The id of the member to find
     * @return Member|PremiumMember $member     The Member object created
     */
    function getMember($member_id)
    {
        global $f3;
        $db = $this->_dbh;
        // query string to match member id
        $sql = "SELECT fname, lname, age, gender, phone, email, state, seeking, bio, premium, image
                FROM member
                WHERE member_id=:member_id";

        $statement = $db->prepare($sql);
        $statement->bindParam(':member_id', $member_id);
        $statement->execute();
        $memberInfo = $statement->fetch(2);

        // get interests for each member
        if($memberInfo['premium'] == 0) {
            $member = new Member($memberInfo['fname'], $memberInfo['lname'], $memberInfo['age'], $memberInfo['gender'],
                $memberInfo['phone']);
        }

        if($memberInfo['premium'] == 1) {
            $member = new PremiumMember($memberInfo['fname'], $memberInfo['lname'], $memberInfo['age'], $memberInfo['gender'],
                $memberInfo['phone']);
            // works whether image is actually set or using default profile image
            $member->setImage($memberInfo['image']);
//
            // allInterests = f3 variable for view member page, implode for comma-separated string
            $f3->set('allInterests', implode(', ', $this->getInterests($member_id)));
        }

        // values to add for both member types
        $member->setEmail($memberInfo['email']);
        $member->setState($memberInfo['state']);
        $member->setSeeking($memberInfo['seeking']);
        $member->setBio($memberInfo['bio']);

        return $member;
    }

    /**
     * Gets an array of all interests in the database matching the member id
     *
     * @param int $member_id    id of the member
     * @return array $interests     all interests of the member
     */
    private function getInterests($member_id) {
        // get all interests associated with member id
        $sql = "SELECT interest FROM interest, member, member_interest
                WHERE member.member_id=:id 
                  AND member.member_id = member_interest.member_id 
                  AND interest.interest_id = member_interest.interest_id";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':id', $member_id);
        $statement->execute();
        $result = $statement->fetchAll(2);

        $interests = array();
        foreach($result as $interest){
            // add each interest to the array
            $interests[] = $interest['interest'];
        }
        return $interests;
    }

}