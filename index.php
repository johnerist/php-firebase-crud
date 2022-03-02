<?PHP
//source link: https://github.com/morrislaptop/firestore-php
require 'vendor/autoload.php';
use Morrislaptop\Firestore\Factory;
use Kreait\Firebase\ServiceAccount;

$conn = db();
$collection = $conn->collection('user-profile'); //table
//$user = $collection->document('LAmPFMasQa7qPPXcvHRw'); //rows

	
?>
<h1>Firebase test CRUD</h1>
<h2>Create Operation</h2>
<?PHP
if(isset($_POST['btnSubmit'])){
	$frmName = $_POST['frmName'];
	$frmDOB = $_POST['frmDOB'];
	$frmRandomNumber = $_POST['frmRandomNumber'];
	$frmLongText = $_POST['frmLongText'];
	$randomNumber = $_POST['hiddenNumber'];
	$id = rand();
	$user = $collection->document($id);
	$user->set(['id'=> $id, 'name' => $frmName, 'dob' => $frmDOB, 'numbering' => $frmRandomNumber, 'long_text' => $frmLongText, 'hiddenVal' => $randomNumber]);
}
?>
<form method="POST">
	<label>Name:</label> <input type="text" name="frmName" required><br/>
	<label>Date of Birth</label> <input type="date" name="frmDOB"><br/>
	<label>Put any Number</label> <input type="number" name="frmRandomNumber"><br/>
	<label>What is your feeling today?</label><br/>
	<textarea name="frmLongText"></textarea><br/>
	<input type="hidden" name="hiddenNumber" value="<?PHP echo rand();?>">
	<input type="submit" name="btnSubmit" value="Send">
</form>

<br><br>
<h2>Read Operation</h2>
<?PHP
$snaps = $collection->documents();
//echo $snaps['name'];
?>
<table border="1">
	<tr>
	<td>ID</td>
	<td>Name</td>
	<td>dob</td>
	<td>Number</td>
	<td>Long Text</td>
	<td>Hidden Vals</td>
	<td>Edit</td>
	<td>Delete</td>
	</tr>
	<?PHP
	foreach($snaps as $snap){
	?>
	<tr>
		<td><?PHP echo $snap['id']; ?></td>
		<td><?PHP echo $snap['name']; ?></td>
		<td><?PHP echo $snap['dob']; ?></td>
		<td><?PHP echo $snap['numbering']; ?></td>
		<td><?PHP echo $snap['long_text']; ?></td>
		<td><?PHP echo $snap['hiddenVal']; ?></td>
		<td><a href="?edit&id=<?PHP echo $snap['id']; ?>">Edit</a></td>
		<td><a href="?delete&id=<?PHP echo $snap['id']; ?>">Delete</a></td>
	</tr>
	<?PHP } ?>
</table>

<br><br>
<h2>Update Operation</h2>
<?PHP
if(isset($_GET['edit'])){
	$id = $_GET['id'];
	if(isset($_POST['btnUpdate'])){
		$frmName = $_POST['frmName'];
		$frmDOB = $_POST['frmDOB'];
		$frmRandomNumber = $_POST['frmRandomNumber'];
		$frmLongText = $_POST['frmLongText'];
		$randomNumber = $_POST['hiddenNumber'];
		
		$user = $collection->document($id);
		//just overwrite existing data, morris build lack of update function, too bad..
		if($user->set(['id'=> $id, 'name' => $frmName, 'dob' => $frmDOB, 'numbering' => $frmRandomNumber, 'long_text' => $frmLongText, 'hiddenVal' => $randomNumber])){
			echo "update success";
			echo "<script>window.location.href='?';</script>";
		}else{
			echo "Haloma, not working la..";
		}
	}
	//init edit: get info
	$result = $collection->document($id);
	$row = $result->snapshot();
	?>
	<form method="POST">
		<label>Name:</label> <input type="text" name="frmName" value="<?PHP echo $row['name']?>" required><br/>
		<label>Date of Birth</label> <input type="date" name="frmDOB" value="<?PHP echo $row['dob']?>"><br/>
		<label>Put any Number</label> <input type="number" name="frmRandomNumber" value="<?PHP echo $row['numbering']?>"><br/>
		<label>What is your feeling today?</label><br/>
		<textarea name="frmLongText"><?PHP echo $row['long_text']?></textarea><br/>
		<input type="hidden" name="hiddenNumber" value="<?PHP echo $row['hiddenVal'];?>">
		<input type="submit" name="btnUpdate" value="Save">
	</form>
	<?PHP
}else{ echo "<p>click edit link above</p>";}
?>

<br><br>
<h2>Delete Operation</h2>
<?PHP
if(isset($_GET['delete'])){
	$id = $_GET['id'];
	$collection->document($id)->delete();
	echo "delete success";
	echo "<script>window.location.href='?';</script>";
}
?>

<?PHP
function db(){
	// This assumes that you have placed the Firebase credentials in the same directory
	// as this PHP file.
	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/firebasekey.json');

	$firestore = (new Factory)
		->withServiceAccount($serviceAccount)
		->createFirestore();
	
	return $firestore;
}
?>
