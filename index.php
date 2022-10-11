<?php
error_reporting(E_ERROR);
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 0)) {
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();   // destroy session data in storage
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Bestellung von Büromaterial</title>
		<link rel="stylesheet" type="text/css" href="./styles/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./styles/index.css">
		<style>
			@media print {
				.no-print {
					display:none;
				}
			}
		</style>
	</head>
	<body>
		<div class="col-sm-12">
			<h1>Büromaterial Bestellung für IT-Mitarbeiter</h1>
		</div>
		<div class="col-sm-6">
			<form action="./auswertung.php" method="POST" class="no-print">
				<div class="form-group">
					<label for="name">Name</label>
					<input name="name" type="text" class="form-control" placeholder="Max Muster" value='<?php echo $_SESSION["name"] ?>' />
				</div>
				<!--<div class="form-group">
					<label for="ort">Gebäude</label>
					<select name="ort" class="form-control">
						<option value="Aucenter (IT Enterprise Solutions, IT Projects)" <?php if($_SESSION["geb"] == 'Aucenter (IT Enterprise Solutions, IT Projects)') echo 'selected' ?>>Aucenter (IT Enterprise Solutions, IT Projects)</option>
						<option value="IT User Service (Client Engineering, IT Support)" <?php if($_SESSION["geb"] == 'IT User Service (Client Engineering, IT Support)') echo 'selected' ?>>IT User Service (Client Engineering, IT Support)</option>
						<option value="Büro IT Applications/IT Infrastructure" <?php if($_SESSION["geb"] == 'Büro IT Applications/IT Infrastructure') echo 'selected' ?>>Büro IT Applications/IT Infrastructure</option>  		 
					</select>
				</div>-->
				<div class="form-group">
					<label for="material">Material <a href="./info.html"><span id="qmicon" class="glyphicon glyphicon-question-sign"></span></a> </label>
					<textarea name="order" rows="10" class="form-control" id="material" placeholder="3x Fineliner Blau, 1x Leuchtstift Gelb, 2x Bostich Klammern"><?php echo str_replace('<br />', "\r\n", $_SESSION["order"]); ?></textarea>
				</div>
			  <button type="submit" class="btn btn-danger">Speichern!</button>
			</form>
			<sub class="no-print">
				Lieferung am Freitag sofern vorhanden!
			</sub>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>
								Name
							</th>
							<th>
								Bestellung
							</th>
							<th>
								Bearbeiten
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if(!file_exists('./data/bestellung.txt'))
							{
								fopen('./data/bestellung.txt', 'w');
							}
							$bestellungen = file("./data/bestellung.txt");
							foreach($bestellungen AS $key=>$line)  {
								$data = explode(";", $line);
								echo "<tr><td class='name'>" . $data[0] . "</td><td>" . $data[1] . 
								"</td><td><a href='auswertung.php?edit=$key'>bearbeiten</a></td></tr>";

								if (substr($data[3], 0, 0) == session_id())
								{
									echo "<a href='auswertung.php?edit=$key'>bearbeiten</a>"; 
								}
								echo "</td></tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
