
<?php

/**
 * XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
 */
	header('Content-Type: text/plain');
	
	
	function getUniqueCode($length = ""){
    $code = md5(uniqid(rand(), true));
    if ($length != "") return substr($code, 0, $length);
    else return $code;
}

require_once 'app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

	$Filepath ='sku_list1.csv';

	require('csvimport.php');
	$data = new Csvimport();	


if ($data->get_array($Filepath)) {


		$csv_array = $data->get_array($Filepath);
		$insert_data=array();		


//Products array		
		       foreach ($csv_array as $row) {	


		    
$randomString = getUniqueCode(20);

if(!empty($row['sku'])){
	$sku=$row['sku'];
}else{
	$sku='0PR-'.$randomString;
};


if($row['Status']==='In Stock'){
	$status=1;
}elseIF($row['Status']==='Out Of Stock'){
	$status=2;
}
 
			$insert_data[] = array(
							'sku' => $sku,
							'_type' => 'simple',
							'_attribute_set' => 'Default',
							'_product_websites' => 'base',
							'_category'=>$row['Category'],							
							'name' =>$row['Title'],
							'qty' =>$row['Stock Qty'],
							'is_in_stock' => $status,
							'price' => $row['price'],
							'rrp_price' => $row['RrpPrice'],						
							'vic' =>$row['VIC'],
							'nsw' =>$row['NSW'],
							'sa' =>$row['SA'],							
							'qld' => $row['QLD'],
							'tas' => $row['TAS'],
							'wa' => $row['WA'],
							'nt' => $row['NT'],
							'bulky_item' => $row['bulky item'],
							'discontinued' => $row['Discontinued'],
							'ean_code' => $row['EAN Code'],
							'brand' => $row['Brand'],
							'mpn' => $row['MPN'],
							'weight' => $row['Weight (kg)'],
							'carton_length_cm' => $row['Carton Length (cm)'],
							'carton_width_cm' => $row['Carton Width (cm)'],
							'carton_height_cm' => $row['Carton Height (cm)'],
							'description' => $row['Description'],								
							'meta_keyword' => 'Default',
							'meta_title' => $row['Title'],
							'meta_description' =>  'Default',							
							'enable_googlecheckout' => '1',
							'gift_message_available' => '0',
							'_media_image' => $row['Image 1'],
							'_media_target_filename' => $row['Image 1'],
							'image' => $row['Image 1'],
							'small_image' => $row['Image 1'],
							'thumbnail' => $row['Image 1'],
							
							
							'url_key' => strtolower($randomString),
                   
                    );
			
}
print_r($insert_data);
die();

$time = microtime(true);

try {
    /** @var $import AvS_FastSimpleImport_Model_Import */
    $import = Mage::getModel('fastsimpleimport/import');
    $import->processProductImport($insert_data);
} catch (Exception $e) {
    print_r($import->getErrorMessages());
}

echo 'Elapsed time: ' . round(microtime(true) - $time, 2) . 's' . "\n";
}


?>


 