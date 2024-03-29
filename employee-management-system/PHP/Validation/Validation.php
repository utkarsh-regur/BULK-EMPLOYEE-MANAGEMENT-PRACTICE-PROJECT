<?php

$action='';

//EXTRACT DATA
if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{

    //FOR EDIT
    if($_POST)
    {
        $data = new stdClass;

        //SET GLOBAL VARIABLES
        $firstName = 	$_POST['firstName'];
        $lastName =  	$_POST['lastName'];
        $email = 	  	$_POST['email'];
        $department =   $_POST['department'];

        //SEPARATE REQUIRED DATA
        $data->firstName = $firstName;
        $data->lastName =  $lastName ;
        $data->email = $email;
        $data->department = $department;

        //GET VALUE OF ACTION PARAMETER
        $action =  $_POST['action'];
    }

    //FOR INSERT
    else
    {
        $data = json_decode(stripslashes(file_get_contents("php://input")));

        //GET VALUE OF ACTION PARAMETER
        $action = $data[0]->action;
        
        //REMOVE ACTION PARAMETER FROM EACH ENTRY
        for ($i = 0; $i < sizeof($data); $i++) 
        {
            unset($data[$i]->action);
        }
    }

    //STANDARDIZE DATA FORMAT
    $dataFormatted = 
    [
        "data" => $data,
        "action" => $action,
    ];
   
}

//VALIDATE DATA
function isValidData()
{

//ERROR LIST
$errors = array();

global $action;

//INSERT VALIDATION
if($action == 'insert'){

    global $dataFormatted;

    //GET VALUE OF EACH FIELD OF EACH ROW
    $firstName = $lastName = $email = $department = [];

    for($i=0; $i< sizeof($dataFormatted['data']) ; $i++)
    {
      $firstName[] = $dataFormatted['data'][$i]->firstName;
      $lastName[] = $dataFormatted['data'][$i]->lastName;
      $email[] = $dataFormatted['data'][$i]->email;
      $department[] = $dataFormatted['data'][$i]->department;
    }

    //VALIDATE NAMES
    $nameFields = array("First Name"=>$firstName, "Last Name"=>$lastName);

    foreach ($nameFields as $key => $value){
        foreach ($value as $val => $name){
           if (empty($name)) 
           array_push($errors, "$key cannot be empty");
        }

        foreach ($value as $key => $name){
            if (!preg_match ("/^[a-zA-z]*$/", $name))
            array_push($errors, "Names should contain only alphabets");
        }
    }

    //VALIDATE EMAIL
    foreach ($email as $key => $value){
        if (filter_var($value, FILTER_VALIDATE_EMAIL) == false)
          array_push($errors, "Please enter a valid email address");
    }

    //VALIDATE DEPARTMENT
    foreach ($department as $key => $value){
        if ($value == 0)
          array_push($errors, "Please select a department");
    }
}

//UPDATE VALIDATION
else{

    global $firstName;
    global $lastName;
    global $email;
    global $department;

    $nameFields = array("First Name"=>$firstName, "Last Name"=>$lastName);

    //VALIDATE NAMES
    foreach ($nameFields as $key => $value){
        if (empty($value))
          array_push($errors, "$key cannot be empty");
    }

    if (!preg_match ("/^[a-zA-z]*$/", $firstName) || !preg_match ("/^[a-zA-z]*$/", $lastName)) 
        array_push($errors, "Names should contain only alphabets");

   //VALIDATE EMAIL
   if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
     array_push($errors, "Please enter a valid email address");

    //VALIDATE DEPARTMENT
    if ($department == 0)
        array_push($errors, "Please select a department");
}

  //IF NO ERRORS EXECUTE CRUD OPERATION
  if (count($errors) === 0)
    return true;

  
  //IF ERRORS FOUND PRINT ERROR LIST
  else
  {
    foreach($errors as $error)
      echo "$error <br/>";
  }
}

?>