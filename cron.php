<?php
include("app/inc/config.php");

$polls=array();
$needshift=FALSE;

try{
$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
     echo $e->getMessage();
}

//$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

$sql_recursive_poll="select id from fd_poll where poll_is_recursive = 1";
$sql_old_slot="select id, title from fd_slot where title < (UNIX_TIMESTAMP() - 86400) and poll_id='";


$PollResult = $pdo->query($sql_recursive_poll);
foreach ($PollResult as $poll) {
	$polls[]=$poll['id'];
	//echo $poll['id'] . PHP_EOL;
}

foreach ($polls as $poll) {
	$newsql=$sql_old_slot. $poll."'";
	//echo $newsql;
	$SlotResult = $pdo->query($newsql);
	foreach ($SlotResult as $slot) {
		echo $slot['id'] . PHP_EOL;
		echo $slot['title'] . PHP_EOL;
		$nextweek=604800 + $slot['title'];
		$sql="update fd_slot set title=$nextweek where id=".$slot['id'];
		echo $sql . PHP_EOL;
		$stmt= $pdo->prepare($sql);
		$stmt->execute([$id]);
		$data = [
		     'title'=> $nextweek,
     		     'id' => $slot['id']
		];
		print_r($data);
		$sql = "UPDATE fd_slot SET title=:title WHERE id=:id";
		$statement = $pdo->prepare($sql);
		if($statement->execute($data)) {
  			echo "Slot updated successfully!";
		}
		$needshift=TRUE;

	}
}

if($needshift)
{
	$data = [ 'poll_id' => $poll ];
	$sql="update fd_vote set choices=substring(choices,2,100) where poll_id=:poll_id";
	$statement = $pdo->prepare($sql);
	if($statement->execute($data)) {
  			echo "Vote updated successfully!";
	}

	$sql="delete from fd_vote where choices = ''";
	$statement = $pdo->prepare($sql);
	if($statement->execute($data)) {
		$count = $statement->rowCount();
 			echo "$count Vote deleted successfully!";
	}
}
