<?php
    session_start();
    
    //////////проверка сессии и прав доступа///////////////////////////////////
     
    $user_id = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['user_id']));
    $fname = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['fname']));
    $role = mysqli_real_escape_string($link, htmlspecialchars($_SESSION['role']));
    if (empty($user_id) || empty($fname) || empty($role)){logout ();}
    if ($role < 9){logout ();} //если условие выполняеться - не пускает
 
     ///////////////////////////////////////////////////////////////////////
     $query = "SELECT * FROM `users`";
     $sql = mysqli_query($link, $query) or die("нет соединения с БД");
        if($sql != false){
            while($row = mysqli_fetch_assoc($sql)){
                if($row['user_id'] == 1){goto nextRow;}
                echo"<div id=\"p-".$row['user_id']."\"><p>
                        <fieldset>
                        <legend>
                            <h4 id=\"h4-".$row['user_id']."\">".$row['fname']."</h4>
                        </legend>
                        <div>
                        <div id=\"".$row['user_id']."\" hidden></div>
                            <Form name=\"".$row['user_id']."\">
                                <table class=\"userUpdate\">
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <th>ЛОГИН</th>
                                            <th>ПАРОЛЬ</th>
                                            <th>ФИО</th>
                                            <th>КАТЕГОРИЯ</th>
                                        </tr>
                                        <tr>
                                            <td class=\"userUpdatetr\"><input type=\"checkbox\" id=\"chckb-".$row['user_id']."\" onchange=\"showButton(".$row['user_id'].")\"></td>
                                            <td class=\"userUpdatetr\"><input required readonly type=\"text\" id=\"lgn-".$row['user_id']."\" name=\"log_NU\"  value=\"".$row['login']."\" autocomplete=\"off\"></td>
                                            <td class=\"userUpdatetr\"><input required readonly type=\"text\" id=\"psw-".$row['user_id']."\" name=\"pass_NU\" value=\"\" autocomplete=\"off\"></td>
                                            <td class=\"userUpdatetr\"><input required readonly type=\"text\" id=\"fio-".$row['user_id']."\" name=\"fio_NU\" value=\"".$row['fname']."\" autocomplete=\"off\"></td>
                                            <td class=\"userUpdatetr\">
                                                <select  id=\"role-".$row['user_id']."\" name=\"role_NU\" required disabled>
                                                    <option selected value=\"".$row['role']."\">значение по умолчанию</option>
                                                    <option value=\"10\">Администратор</option>
                                                    <option value=\"9\">Директор</option>
                                                    <option value=\"7\">Бухгалтер</option>
                                                    <option value=\"5\">Менеджер</option>
                                                    <option value=\"3\">Оператор</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </Form>
                        </div>
                            <div id=\"btn-".$row['user_id']."\" style=\"display: none;\" >
                                <button class=\"btn\" name=\"update\" id=\"".$row['user_id']."\">изменить</button>      
                                <button class=\"btn\" name=\"delete\" id=\"".$row['user_id']."\" )\">удалить</button>
                            </div>
                        </fieldset>    
                     </p></div>";
                nextRow:
            }
        }else {
            echo"ошибка запроса к бд";
        }
?>