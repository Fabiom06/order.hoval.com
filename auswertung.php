<?php
	error_reporting(E_ALL);
	header('Access-Control-Allow-Origin: http://order.hoval.com', true);
	$illegal = array(";" , "\\" , ">", "<", ",");
    $newline = array("\r\n","\r","\n");

	session_start();

	print_r($_POST);

	//FUNCTIONS
	function makeSafe($string)	{
		global $illegal, $newline;

		$string = htmlspecialchars(strip_tags($string));
		$string = str_replace( $illegal , "" , $string);
		$string = str_ireplace( $newline , "<br />" , $string);
		return $string;
	}

	function edit($row){
		$lines = file("./data/bestellung.txt");
		$data = explode(";", $lines[$row]);

		$_SESSION["name"] = $data[0];
		$_SESSION["order"] = substr($data[1], 0, -2); //Removes the \r\n before posting it into the Session Var Order

		unset($lines[$row]);
		file_put_contents("./data/bestellung.txt", implode("", $lines));
	}

	function leave(){
		$_SESSION['LAST_ACTIVITY'] = time();
		header('Location: ./index.php');
	}

	function insertOrder($name, $order){
		// Copy pasta from w3schools
		$myfile = fopen("./data/bestellung.txt", "a") or die("Unable to open file!");
		$semi = ";";
		$lineBreak = "\r\n";
		$txt = $name;
		fwrite($myfile, $txt);

		$txt = $semi;
		fwrite($myfile, $txt);

		$txt = $order;
		fwrite($myfile, $txt);

		$txt = $lineBreak;
		fwrite($myfile, $txt);

		fclose($myfile);
	}

	//Code
	if(isset($_GET['edit'])) {
		edit($_GET['edit']);
	} elseif (isset($_POST['name']) && isset($_POST['order'])) {
		unset($_SESSION["order"]);
		unset($_SESSION["name"]);
		unset($_SESSION["edit"]);
		
		
		$name = makeSafe($_POST['name']);
		$order = makeSafe($_POST['order']);
		insertOrder($name, $order);
	}
	leave();
?>
