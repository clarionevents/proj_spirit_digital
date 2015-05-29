<?php
// Turn off all error reporting
error_reporting(0);

$dbServer = '127.0.0.1';
$dbName = 'spiritboutique-dev';
$dbUser = 'root';
$dbPass = 'root';
$exportFile;
$importFile = "var/import/shipping-rules.csv";
$exportLimit = 10000; //set lower for testing if you want
 
 
 echo "Export Ubercart products from db " . $dbName . " as user " . $dbUser . " to Magento csv file, Would you like to continue? [Yes|No] ";
if(!strncasecmp(trim(fgets(STDIN)),'y',1)) 
{	 
	try {
		$connection = new PDO("mysql:host=$dbServer;dbname=$dbName",$dbUser,$dbPass);
		echo "Connected to database...\r\n";
	}
	catch(PDOException $e) {
		echo "Connection error! " . $e->getMessage() . "\r\n";
	}
 
	
	$shipping_rules = readCsv();
	
	$products= array();
	$sql = "SELECT * from catalog_product_entity";
	
	foreach( $connection->query($sql) as $row){
		
		array_push($products, $row['entity_id']);
	}
	
	foreach($shipping_rules as $items){
		addRule($connection, $items, $products);
	}
 
	
    
}
function addRule($connection,$shipping_rules, $products) {
	
	
	foreach ($shipping_rules as $rule) {
		
		if($rule[0] == "price"){
			$sql="SELECT * FROM marketplace_product m JOIN catalog_product_index_price c ON m.mageproductid = c.entity_id WHERE customer_group_id =0"; //price query
		}elseif($rule[0] == "weight"){
			$sql="SELECT * FROM marketplace_product m JOIN catalog_product_entity_decimal c ON m.mageproductid = c.entity_id WHERE attribute_id=80"; //weight
		}
			foreach($connection->query($sql) as $row){
				if($rule[4] !== '0'){
					if($row['userid'] == $rule[3]){
					
					if($rule[1] == "less"){
						if($row['value'] < $rule[2]){
							// echo "\n" . $rule[4];
						
							$sql='SELECT value FROM catalog_product_entity_varchar WHERE entity_id = '. $row['mageproductid']  .' AND attribute_id = 270';
							$result = mysql_query($sql);
							
							if (mysql_num_rows($result) <= 0) {
								$sql = 'INSERT INTO `catalog_product_entity_varchar`(`value_id`, `entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (DEFAULT,4,270,0,'. $row["entity_id"] .',"GB, '. $rule[4] .'/")';
							$connection->query($sql);
							}else{
								$sql = 'UPDATE catalog_product_entity_varchar SET value = "GB, ' . $rule[4] . '/" WHERE value IS NULL AND entity_id = '. $row['mageproductid'] .' AND attribute_id = 270';
							$connection->query($sql);
							}
						}
				
					}elseif($rule[1] == "more"){
						
						if($row['value'] > $rule[2]){
							// echo "\n" . $rule[4];
							
							$sql='SELECT value FROM catalog_product_entity_varchar WHERE entity_id = '. $row['mageproductid']  .' AND attribute_id = 270';
							$result = mysql_query($sql);
							
							if (mysql_num_rows($result) <= 0) {
								$sql = 'INSERT INTO `catalog_product_entity_varchar`(`value_id`, `entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (DEFAULT,4,270,0,'. $row["entity_id"] .',"GB, '. $rule[4] .'/")';
							$connection->query($sql);
							}else{
								$sql = 'UPDATE catalog_product_entity_varchar SET value = "GB, ' . $rule[4] . '/" WHERE value IS NULL AND entity_id = '. $row['mageproductid'] .' AND attribute_id = 270';
							$connection->query($sql);
							}
							
							
						}
						
					}elseif($rule[1] == "all"){
						// echo "\n" . $rule[4];
						$sql='SELECT value FROM catalog_product_entity_varchar WHERE entity_id = '. $row['mageproductid']  .' AND attribute_id = 270';
							$result = mysql_query($sql);
							
							if (mysql_num_rows($result) <= 0) {
								$sql = 'INSERT INTO `catalog_product_entity_varchar`(`value_id`, `entity_type_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (DEFAULT,4,270,0,'. $row["entity_id"] .',"GB, '. $rule[4] .'/")';
							$connection->query($sql);
							}else{
								$sql = 'UPDATE catalog_product_entity_varchar SET value = "GB, ' . $rule[4] . '/" WHERE value IS NULL AND entity_id = '. $row['mageproductid'] .' AND attribute_id = 270';
							$connection->query($sql);
							}
					}
				}
				}
				
			}
				
	}
	
}


function readCsv() {

	//echo "Array contents to write:\r\n" . print_r($mProducts,true)  . "\r\n";

	$fp = fopen($GLOBALS['importFile'], "r");
	
	$x = 0;
	
	$shipping_rules=array();
	$data = array();
	if (($fp) !== FALSE) {
	    while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
			array_push($shipping_rules, array($data));
	    }
    	fclose($fp);
	}
	
	return $shipping_rules;

}
?>