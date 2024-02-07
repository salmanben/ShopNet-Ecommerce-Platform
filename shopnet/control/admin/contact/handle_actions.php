<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']))
{
    $response = array();
    if ($_GET['action'] == 'delete')
    {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $contact_id = $data->id;
        $sql = "DELETE FROM contact where id='$contact_id'";
        $result = mysqli_query($connect, $sql);
        if ($result)
        {
            $response['status'] = 'success';
            echo json_encode($response);
        }
        else{
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
        

    }
}
?>