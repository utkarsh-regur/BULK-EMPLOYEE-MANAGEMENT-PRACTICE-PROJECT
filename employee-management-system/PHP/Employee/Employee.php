<?php

include __DIR__ . '../../DataBaseConnection/dbConnection.php';
include __DIR__ . '../../Validation/Validation.php';
include __DIR__ . '../../Crud/Crud.php';


//DISPLAY ALL EMPLOYEE RECORDS
if ( $_SERVER['REQUEST_METHOD'] == 'GET')
{

    displayEmployees();

}


//INSERT SINGLE/BULK EMPLOYEE RECORD/S
if ($action == 'insert') 
{

    insertEmployees();
}  


//DELETE EMPLOYEE RECORD
if ( $action == 'delete') 
{
    deleteEmployees();

}

//UPDATE EMPLOYEE RECORD
if ( $action == 'edit') 
{
    editEmployees();

} 

?>