<?php
if(isset($_POST['update'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $update = ($_POST['update']);
            $userId = $_POST['userId'];
            // Since the data is displayed by table, we need to know the position where the updated data is 
            $count = count($userId);
            for($x = 0; $x < $count; $x += 1){
                if ($userId[$x] === $update) {
                    $objectDb = new DatabaseConnect;
                    $conn = $objectDb->connect();
                break;
                }
            }
            
            $firstname = ($_POST['updateFirstname']);
            $lastname = ($_POST['updateLastname']);
            $email = ($_POST['updateEmail']);
            $phonenumber = $_POST['updatePhone'];
            $gender = ($_POST['updateGender']);

            if (strlen($phonenumber[$x]) <= 14) {

                // This is for check whether or not email is valid
                if(filter_var($email[$x], FILTER_VALIDATE_EMAIL)) {

                    $query = "UPDATE `{$_SESSION['userDatabase']}` SET ";
                    $query .= "firstname = :firstname, ";
                    $query .= "lastname = :lastname, ";
                    $query .= "email = :email, ";
                    $query .= "phonenumber = :phonenumber, ";
                    $query .= "gender = :gender ";
                    $query .= "WHERE id = :update";

                    $stmt = $conn->prepare($query);

                    $stmt->bindValue(':firstname', $firstname[$x]);
                    $stmt->bindValue(':lastname', $lastname[$x]);
                    $stmt->bindValue(':email', $email[$x]);
                    $stmt->bindValue(':phonenumber', $phonenumber[$x]);
                    $stmt->bindValue(':gender', $gender[$x]);
                    $stmt->bindValue(':update', $update);
                            
                    if(!$stmt->execute()) {                   
                        die('Update task(s) failed!');
                    } else {
                        $displayMsg = ('Update task(s) successfully!');
                        $msgClass = "success-alert";
                    }
                } else {
                    $displayMsg = "Please fill in all fields!";
                    $msgClass = "danger-alert";
                }
            } else {
                $displayMsg = ('Phone Number exceeds maximum characters!'); 
                $msgClass = "danger-alert";
            }
    }
}
?>