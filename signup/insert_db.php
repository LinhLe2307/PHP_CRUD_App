<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // This is for sanitizing the user's inputs
        $firstname = test_inputs($_POST['firstname']);
        $lastname = test_inputs($_POST['lastname']);
        $email = test_inputs($_POST['email']);
        $password = test_inputs($_POST['password']);
        $phonenumber = test_inputs($_POST['phonenumber']);
        $gender = test_inputs($_POST['gender'] ?? "");
                

        if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password) && !empty($phonenumber) && !empty($gender)) {
                    
            // Password encryption
            $hashFormat = "$2y$10$";
            $salt = "helsinkibusinesscollege";
            $hashFormatAndSalt = $hashFormat . $salt;
            $pass_encrypted = crypt($password, $hashFormatAndSalt);

            $objectDB = new DatabaseConnect;
            $conn = $objectDB->connect();
            $query = "SELECT * FROM user_registration_123 WHERE email=:email";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            if (strlen($phonenumber) <= 14) {
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    if($stmt->rowCount()!=0){
                                
                        //Email already registered. Exit with a message
                        die("Email already exists");
                    }

                    $objectDB = new DatabaseConnect;
                    $conn = $objectDB->connect();
                    $sql = 'INSERT INTO user_registration_123(id, firstname, lastname, email, password, phonenumber, gender) VALUES(null, :firstname, :lastname, :email, :password, :phonenumber, :gender)';
                    $stmt = $conn->prepare($sql);
                                
                    $stmt->bindValue(':firstname', $firstname);
                    $stmt->bindValue(':lastname', $lastname);
                    $stmt->bindValue(':email', $email);
                    $stmt->bindValue(':password', $pass_encrypted);
                    $stmt->bindValue(':phonenumber', $phonenumber);
                    $stmt->bindValue(':gender', $gender);

                    if($stmt->execute()){
                        $displayMsg = "Add task successfully!";
                        $msgClass = "success-alert";
                        
                        // Create individual table for each user
                        $query = "CREATE TABLE IF NOT EXISTS `{$email}`(
                            id INTEGER(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            firstname VARCHAR(50) NOT NULL,
                            lastname VARCHAR(50) NOT NULL,
                            email VARCHAR(50) NOT NULL,
                            phonenumber VARCHAR(14) NOT NULL,
                            gender VARCHAR(12) NOT NULL
                            )";
                        $stmt2 = $conn->prepare($query);
                        $stmt2->execute();                
            
                    } else {
                        die("Please fill in all fields!");
                    }        
                } else {
                    $displayMsg = "Please fill in all fields!";
                    $msgClass = "danger-alert";
                }
            } else {
                $displayMsg = ('Phone Number exceeds maximum characters!'); 
                $msgClass = "danger-alert";
            }
        } else {
            $displayMsg = "Please fill in all fields!";
            $msgClass = "danger-alert";
        }
}
        
    


