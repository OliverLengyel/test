<!-- Demo File erstellt von MMag. Florian Weiss 2013 | http://www.weissheiten.at -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.3.2.min.css" />
    <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.mobile-1.3.2.min.js"></script>

    <style>
       section{
            margin: 20px;
       }

        div#login_error{
            color: #DDDDDD;
            background-color: #EE0000;
            border: 1px solid black;
            text-align: center;
            padding: 3px;
            border-radius: 10px;
        }

    </style>
</head>
<body>
<?php

    // We use ADODB to do a database connection and require the functionality
    require('/adodb5/adodb.inc.php');
    require('PasswordHash.php');
    // Create a new Database Object for mysql
    $DB = NewADOConnection('mysql');
    // Connect with: Server, User, Password, Database
    $DB->Connect('localhost','root','','musicdb');

class loginhelper{
        /*
         * This function puts out a login form that can be used for logging in
         */
        static function outputLoginForm(){
            return '<form name="frm_loginform" action="sign_in.php" method="post">
                        <label for="inp_username_id">Username:</label>
                        <input type="text" name="inp_username" id="inp_username_id" />

                        <label for="inp_password_id">Password:</label>
                        <input type="password" name="inp_password" id="inp_password_id" />
						
						  <label for="inp_geb_id">Geburtstag:</label>
                        <input type="text" name="inp_geb" id="inp_geb_id" />
						
						  <label for="inp_ID_id">ID:</label>
                        <input type="text" name="inp_ID" id="inp_ID_id" />

                        <input type="submit" value="Login" />
                    </form>';
        }

        /*
         * This function puts out a passed error in the according format
         */
        static function outputLoginError($err){
            return '<div id="login_error">'.$err.'</div>';
        }
		
		static function loginUser($username, $password,$geb,$ID,$DB){
            // $DB would not be found inside the class - we reference to the global variable - http://www.php.net/manual/en/language.variables.scope.phpglobal $DB;
            // We try to find the user in the database
			
		$sql = "insert into user (ID,username,password,geb) ";

		$sql .= "values ('$ID','$username','$password','$geb')";



		if ($DB->Execute($sql) === false) {

			print 'error inserting: '.$DB->ErrorMsg().'<BR>';
      
    else{
    return true;
    }

	}

        }


    }
?>

   <section id="sec_login">
        <?php
            // We define error so it can be used later - even if it is empty and we did not have an error (prevent an error message if we don't have the variable)
            $error = "";
            // This variable helps us to keep the status if the user is logged in or not - default is false
            $userisloggedin = false;

            // If username and password have been set - we check the credentials
			if(isset($_POST['inp_username']) && isset($_POST['inp_password'])){
                // make sure we don't face SQL Injections while keeping special chars - http://at1.php.net/manual/en/function.htmlspecialchars.php
                $username = htmlspecialchars($_POST['inp_username']);
                $password = htmlspecialchars($_POST['inp_password']);
				$ID = htmlspecialchars($_POST['inp_ID']);
				$geb = $_POST['inp_geb'];

                if(loginhelper::loginUser($username,$password,$geb,$ID, $DB)){
                    echo 'Sign In success!';
                    // set our login status variable to true
                    $userisloggedin = true;
                }
                else{
                    $error = "Sign In failed!";
                }
            }

            // We show the login Form if the user is not logged in
            if(!$userisloggedin){
                // show the login Form with the help of our loginhelper class
                echo loginhelper::outputLoginForm();

                // if there is any error info display it
                if(strlen($error)>0)
                    echo loginhelper::outputLoginError($error);
            }
        ?>
    </section>
</body>
</html>
<?php
    // don't forget to disconnect from the database - open connections could use too much resources although they time out
    $DB->Close();
?>