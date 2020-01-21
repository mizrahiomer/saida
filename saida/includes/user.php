<?php
header('Content-Type: text/html; charset=utf-8');
require('init.php');

class User
{
    
    private $fname;
    private $lname;
    private $email;
    private $birth;
    private $role;
    private $uid;
    
    
    public static function fetch_users()
    {
        
        global $database;
        $result_set = $database->query("select * from users");
        $users      = null;
        if (isset($result_set)) {
            $i = 0;
            if ($result_set->num_rows > 0) {
                while ($row = $result_set->fetch_assoc()) {
                    $user = new User();
                    $user->instantation($row);
                    $users[$i] = $user;
                    $i += 1;
                }
            }
        }
        return $users;
    }
    
    private function has_attribute($attribute)
    {
        
        $object_properties = get_object_vars($this);
        return array_key_exists($attribute, $object_properties);
    }
    
    private function instantation($user_array)
    {
        foreach ($user_array as $attribute => $value) {
            if ($result = $this->has_attribute($attribute))
                $this->$attribute = $value;
        }
    }
    public function find_user_by_id($id)
    {
        global $database;
        $error  = null;
        $result = $database->query("select * from users where id='" . $id . "'");
        if (!$result)
            $error = 'Can not find the user.  Error is:' . $database->get_connection()->error;
        elseif ($result->num_rows > 0) {
            $found_user = $result->fetch_assoc();
            $this->instantation($found_user);
        } else
            $error = "Can no find user by this name";
        return $error;
        
    }
    public static function add_user($fname, $lname, $email, $birth, $role, $uid)
    {
        global $database;
        $error  = null;
        $sql    = "Insert into users(fname, lname, email, birth, role, uid) values ('" . $fname . "','" . $lname . "','" . $email . "'," . $birth . ", '" . $role . "', " . $uid . ")";
        $result = $database->query($sql);
        if (!$result)
            $error = 'Can not add user.  Error is:' . $database->get_connection()->error;
        return $error;
        
    }
    
    public static function checkLogin($email, $uid)
    {
        global $database;
        $password  = md5($uid);
        $sql       = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        //checking if the username is available in the table
        $result    = $database->query($sql);
        $user_data = $result->fetch_assoc();
        $count_row = $result->num_rows;
        if ($count_row == 1) {
            // this login variable will use for the session thing
            $_SESSION['login'] = true;
            
            $_SESSION['role']            = $user_data['role'];
            $_SESSION['fname']           = $user_data['fname'];
            $_SESSION['lname']           = $user_data['lname'];
            $_SESSION['backgroundColor'] = $user_data['backgroundColor'];
            $_SESSION['inshift']         = $user_data['inshift'];
            $_SESSION['score']           = $user_data['score'];
            return true;
        } else {
            return false;
        }
    }
    
    public function get_fname()
    {
        return $this->fname;
    }
    public function get_lname()
    {
        return $this->lname;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function get_birth()
    {
        return $this->birth;
    }
    public function get_role()
    {
        return $this->role;
    }
    public function get_uid()
    {
        return $this->uid;
    }
    
    /*** starting the session ***/
    public function get_session()
    {
        return $_SESSION['login'];
    }
    public function user_logout()
    {
        $_SESSION['login'] = FALSE;
        session_destroy();
    }
    
    public function get_fullname($uid)
    {
        global $database;
        $sql       = "SELECT fname FROM users WHERE uid = '$uid'";
        $result    = $database->query($sql);
        $user_data = $result->fetch_assoc();
        echo $user_data['fname'];
    }
}
?>