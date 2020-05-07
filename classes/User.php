<?php

include_once __DIR__ . "/Db.php";

class User
{
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $username;

    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname($firstname)
    {
        if (empty($firstname)) {
            throw new Exception("Firstname cannot be empty");
        }
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */
    public function setLastname($lastname)
    {
        if (empty($lastname)) {
            throw new Exception("Lastname cannot be empty");
        }
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }

        if (!preg_match('/@student.thomasmore.be/', $email)) {
            throw new Exception("You must have a Thomas More student email adress");
        }
        $this->email = $email;

    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        if (empty($username)) {
            throw new Exception("Username cannot be empty");
        }
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        if (empty($password)) {
            throw new Exception("Please enter a password");
        }
        if (!isset($error)) {
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
        }
        $this->password = $password;

        return $this;
    }

    public function createUser()
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare("insert into users (firstname, lastname, email, password, username) values (:firstname, :lastname, :email, :password, :username)");

        $firstname = $this->getFirstname();
        $lastname = $this->getLastname();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $username = $this->getUsername();

        $statement->bindValue(":firstname", $firstname);
        $statement->bindValue(":lastname", $lastname);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
        $statement->bindValue(":username", $username);

        $result = $statement->execute();

        return $result;
    }

    public function checkDuplicate()
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare("select email from users where  email = :email"); //change get and post here

        $email = $this->getEmail();
        $statement->bindValue(":email", $email);

        //$statement->bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $statement->execute();

        if ($statement->fetchColumn()) {
            throw new Exception("Please use a different email address");
        }
    }

    public static function login($email, $password)
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($password, $result['password'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /*public static function toonCoins($email)
    {
    $conn = Db::getConnection();

    $statement = $conn->prepare("SELECT coins FROM users WHERE email = :email");
    $statement->bindValue(":email", $email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    echo $result;

    }*/

    public static function toonCoins($email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT coins FROM users where email = :email");
        $statement->bindValue(":email", $email);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $coins = $result["coins"];
        return $coins;
    }

    public static function findFriend($friend)
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare("SELECT * FROM users WHERE username = '$friend'");

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function sendMoney($amount, $friend)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("UPDATE users SET coins = coins + '$amount' WHERE username = '$friend'");

        $result = $statement->execute();

        return $result;

    }

    public static function loseMoney($amount, $email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("UPDATE users SET coins = coins - '$amount' WHERE email = '$email'");

        $result = $statement->execute();

        return $result;

    }
}
