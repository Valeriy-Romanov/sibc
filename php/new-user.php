<?php
    session_start();
    
   //////////проверка сессии и прав доступа///////////////////////////////////
    
   $user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
   $fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
   $role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
   if (empty($user_id) || empty($fname) || empty($role)){logout ();}
   if ($role < 3){logout ();} //если условие выполняеться - не пускает

	//////////////////////////////////////////////////////////////
	$login = mysqli_real_escape_string($link, htmlspecialchars($_POST['log_NU']));
	$fnameNew = mysqli_real_escape_string($link, htmlspecialchars($_POST['fio_NU']));
	$roleNewUser = $_POST['role_NU'];
	$pass = $_POST['pass_NU']; 
   
    if(!empty($login) && !empty($fname) && !empty($roleNewUser) && !empty($pass)){
        ///проверяем, есть ли такой логин в базе/////
        $loginFromBD_querty = "SELECT `login` FROM `users`";
        $sql = mysqli_query($link, $loginFromBD_querty);
        $err = " " .mysqli_errno($link) . ": " . mysqli_error($link). "\n";
            if($sql != false){
                while($row = mysqli_fetch_assoc($sql)){
                    if($row['login'] == $login){
                        echo '<span class="alert">Пользователь с логином - "' . $login . '" в базе уже есть, добавление невозможно! Придумайте другой логин!</span>';
                        goto next;
                    }
                }
            }
        /////////////////////////////////////////////        
            $salt = '';
            $saltLength = 8; //длина соли
                for($i=0; $i<$saltLength; $i++){
                    $salt .= chr(mt_rand(33,126)); //символ из ASCII-table
                }
            $saltPassword = md5($pass.$salt);
            $new_user_querty = "INSERT INTO `users` (`login`, `password`, `solt`, `fname`, `role`) 
                                VALUES ('$login', '$saltPassword', '$salt', '$fnameNew', '$roleNewUser')";
            $sql = mysqli_query($link, $new_user_querty);
            $err = " " .mysqli_errno($link) . ": " . mysqli_error($link). "\n";
                if($sql != false){
                    echo '<span class="success">Новый пользователь успешно добавлен!</span>';
                }
                else{
                    print_r ($err);
                }
    }
    else{
        echo '<span class="alert">Ошибка! Были заполнены не все поля!</span>';
    }
    next:
    $_POST = array();
    mysqli_close($link);
?>