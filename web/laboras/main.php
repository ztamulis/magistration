
<?php
$criteriaErr = $brand_req_Err = "";
$criteria = $brand_req = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["criteria"])) {
        $criteriaErr = "Criteria is required";
    } else {
        $criteria = test_input($_POST["criteria"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["brand_req"])) {
        $brand_req_Err = "Criteria is required";
    } else {
        $brand_req = test_input($_POST["criteria"]);
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
Price :<input type="text" name="criteria" value="<?php echo $criteria;?>">
  <span class="error">* <?php echo $criteriaErr;?></span>
<br><br>
Brand: <input type="text" name="brand_req" value="<?php echo $brand_req;?>">
    <span class="error">* <?php echo $brand_req_Err;?></span>
    <br><br>
<input type="submit" name="submit" value="Submit">
</form>



<?php
require_once './Domain.php';
require_once './Navigacija.php';
$domain = new Domain();
$file = fopen("Navigacijos.txt", "r");
while (!feof($file)) {
    $line = fgets($file);
    $items = explode(";", $line);
    $item = new Navigacija($items[0], $items[1], $items[2], $items[3]);
    $domain->insertIntoArray($item);
}
$countedElements = $domain->get_count_elements();
function print_values($domain, $counted_elements)
{

    for ($i = 0; $i<$counted_elements; $i++){
        $object_to_array = ((array) $domain);
  var_dump($object_to_array);
//        echo $object_to_array['brand'] . " " . $object_to_array['model'] . " " . $object_to_array['maps_number'] . " " . $object_to_array['price'] . "<br>";
    }
}

print_values($domain, $countedElements);
//    if($counted_elements > 3){
//    for ($i = 0; $i<$counted_elements; $i++){
//      $take_element = $Domain->get_element($i);
//        $object_to_array = ((array) $take_element);
//        echo $object_to_array['brand'] . " " . $object_to_array['model'] . " " . $object_to_array['maps_number'] . " " . $object_to_array['price'] . "<br>";
//    }
//    echo "<br>" . "PO ISTRINIMO" . "<br>" . "<br>";
//    }
//    if($counted_elements > 3){
//        for ($i = 0; $i<$counted_elements; $i++) {
//            $take_element = $Domain->get_element($i);
//            $object_to_array = ((array)$take_element);
//            if ($criteria < $object_to_array['price'] && $brand_req == $object_to_array['brand']) {
//                echo $object_to_array['brand'] . " " . $object_to_array['model'] . " " . $object_to_array['maps_number'] . " " . $object_to_array['price'] . "<br>";
//            }
//        }
//    }




fclose($file);


?>