<?php
class database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "root";
    private $dbname = "users";
    protected $connect;
    public function __construct()
    {
        $connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        $this->connect = $connection;
    }
    public function connect()
    {
        return $this->connect;
    }
    public function loginVal($email, $password)
    {
        $email_error = "Please enter a valid email address.";
        $password_error = "Please enter a password with at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.";
        $correct_password = "Enter the correct password";
        $correct_mail = "Enter the correct mail";
        $errors = array();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $email_error;
        }

        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $password)) {
            $errors[] = $password_error;
        }

        if (!empty($errors)) {
            return $errors;
        } else {
            $connection = $this->connect();
            $sql = "SELECT * FROM users WHERE email LIKE ?;";
            $statement = $connection->prepare($sql);
            $statement->bind_param("s", $email);
            $statement->execute();
            $result = $statement->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
                if (password_verify($password, $row['password'])) {
                    session_start();
                    if ($row['usertype'] == "admin") {
                        $id = $row["id"];
                        $_SESSION["id"] = $id;
                        redirect("admin/admin");
                    }
                    if ($row['usertype'] == "user") {
                        $id = $row["id"];
                        $_SESSION["id"] = $id;
                        redirect("user/user");
                    }
                } else {
                    $errors[] = $correct_password;
                }
            } else {
                $errors[] = $correct_mail;
            }
        }

        return $errors;
    }
    function registerValidation($email, $password, $confirm_password, $phone)
    {
        $errors = [];

        $email_error = "Please enter a valid email address.";
        $password_error = "Please enter a password with at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.";
        $con_password_error = "Please enter a confirm password with at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.";
        $confirm_password_error = "Password does not match.";
        $phone_error = "Please enter a valid phone number with 10 digits.";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $email_error;
        }

        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $password)) {
            $errors[] = $password_error;
        }

        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $confirm_password)) {
            $errors[] = $con_password_error;
        }

        if (!($password == $confirm_password)) {
            $errors[] = $confirm_password_error;
        }

        if (!preg_match("/^\+[1-9][0-9]{0,2}[-\s]?[1-9][0-9]{9}$/", $phone)) {
            $errors[] = $phone_error;
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }
    public function exists($email)
    {
        $connection = $this->connect();
        $errors = [];
        $email_error = "Email Does not exist";
        $sql = "SELECT * FROM users WHERE email LIKE ?;";
        $statement = $connection->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $token = bin2hex(random_bytes(16));
            $token_hash = hash("sha256", $token);
            date_default_timezone_set('Your_Timezone');
            $date = date("Y-m-d H:i:s", time() + 60 * 30);
            $tokenSql = "UPDATE users SET token_hash = ?,token_expire = ? WHERE email = ?";
            $statementToken = $connection->prepare($tokenSql);
            $statementToken->bind_param("sss", $token_hash, $date, $email);
            $statementToken->execute();
            return true;
        } else {
            $errors[] = $email_error;
            return $errors;
        }
    }
    function updatePass($token, $password, $confirm_password)
    {
        $errors = [];
        $password_error = "Please enter a password with at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.";
        $con_password_error = "Please enter a confirm password with at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.";
        $confirm_password_error = "Password does not match.";
        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $password)) {
            $errors[] = $password_error;
        }
        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $confirm_password)) {
            $errors[] = $con_password_error;
        }
        if (!($password == $confirm_password)) {
            $errors[] = $confirm_password_error;
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            $connection = $this->connect();
            $sqlgetdate = "SELECT token_expire FROM users WHERE token_hash = ?";
            $statements = $connection->prepare($sqlgetdate);
            $statements->bind_param("s",$token);
            $statements->execute();
            $statements->bind_result($date);
            $statements->fetch();
            $statements->close();
            if(strtotime($date)<=time()){
                $errors[] = "Token Expired";
                return $errors;
            }else{
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE token_hash = ?;";
                $statement = $connection->prepare($sql);
                $statement->bind_param("ss", $hash, $token);
                $statement->execute();
                return true;
            }
        }
    }
}
?>