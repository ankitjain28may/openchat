<?php
require_once 'database.php';
session_start();
if(isset($_SESSION['start']))
{
	$id=$_SESSION['start'];
	$query="SELECT * FROM total_message WHERE user1='$id' or user2='$id'";
	$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if($result=$connect->query($query)) 
	{
		if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		// $key="identifier";
		$array = array();
		$ln=strlen($id);
		// var_dump($id);
		var_dump($row);
		$value=$row['identifier'];
		$st=substr($value, 0,$ln);
		// var_dump($st);
		// var_dump($value);
		echo "1";
		if($st==$id)
			echo "true";
		
		else if($st=substr($value,$ln+1)==$id)
			echo "second";	
	
	$array['identifier']=$id;
	}
		echo json_encode($array);
		// var_dump($array);

}

	}
	else{
		echo "Query Failed";
	}
}
else{
	header('Location:login.php');
}
?>