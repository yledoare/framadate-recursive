<?php
include("/var/www/framadate/app/inc/config.php");

$polls=array();
$needshift=FALSE;

try{
$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
     echo $e->getMessage();
}

$sql_recursive_poll="select id from fd_poll where poll_is_recursive = 1";
$sql_old_slot="select id, title, moments from fd_slot where title < (UNIX_TIMESTAMP() - 86400) and poll_id='";


$PollResult = $pdo->query($sql_recursive_poll);
foreach ($PollResult as $poll) {
	$polls[]=$poll['id'];
	echo exec("date") . " - ";
	echo $poll['id'] . PHP_EOL;
}

$slots=1;

foreach ($polls as $poll) {
	$newsql=$sql_old_slot. $poll."'";
	$SlotResult = $pdo->query($newsql);
	foreach ($SlotResult as $slot) {
		echo "SLOT ID: ". $slot['id'] . PHP_EOL;
		echo "SLOT TITLE:". $slot['title'] . PHP_EOL;
		echo "Moments : " . $slot['moments'] . PHP_EOL;
		$moments=explode(",",$slot['moments']);
		echo "Nb Moments : " . sizeof($moments) . PHP_EOL;
		$slots=$slots + sizeof($moments);

		// 2 Weeks
		//$nextweek=1209600  + $slot['title'];
		
		// 1 Week
		$nextweek=604800  + $slot['title'];

		$sql="update fd_slot set title=$nextweek where id=".$slot['id'];
		echo $sql . PHP_EOL;
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$id]);
		$count = $stmt->rowCount();
  			echo "$count Slot updated nextweek successfully!". PHP_EOL;
		$data = [
		     'title'=> $nextweek,
     		     'id' => $slot['id']
		];

		/*
		$sql = "UPDATE fd_slot SET title=:title WHERE id=:id";
		echo $sql . PHP_EOL;
		$statement = $pdo->prepare($sql);
		if($statement->execute($data)) {
			$count = $statement->rowCount();
  			echo "$count Slot updated successfully!" . PHP_EOL;
		}
		*/
		$needshift=TRUE;

	}
}

if($needshift)
{
	$data = [ 'poll_id' => $poll ];
	$sql="update fd_vote set choices=substring(choices,$slots,100) where poll_id=:poll_id";
	$statement = $pdo->prepare($sql);
	if($statement->execute($data)) {
		$count = $statement->rowCount();
  			echo "$count Vote updated successfully!";
	}

	$sql="delete from fd_vote where choices = ''";
	$statement = $pdo->prepare($sql);
	if($statement->execute($data)) {
		$count = $statement->rowCount();
 			echo "$count fd_vote deleted successfully!";
	}
}
