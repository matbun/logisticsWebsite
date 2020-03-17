<?php  
$cars['fiat'] = 500;
$cars['porsche'] = 911;
$cars['uno'] = 1;
//echo $cars['porsche'];

$a = array();
foreach ($cars as $car => $value){
	$a[] = $car;
}

echo $a[1];
?>