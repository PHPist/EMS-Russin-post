<?
header("Content-Type: text/html; charset=utf-8");
include('deliveryEMSpost.class.php');

if ($_POST){
	$del_city_to = $_POST['del_city_to'];
	}
else {$del_city_to = 'city--moskva'; }

$deliveryEMSpost = new deliveryEMSpost('city--penza',$del_city_to,1.50);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>

<body>

<form method="post">
<label for="del_city_to">Укажите город доставки:</label>
<select name="del_city_to">
	<optgroup label="Города доставки">
		  <? foreach ($deliveryEMSpost->sity_list as $cityprint){?>
          <option <? if($_POST['del_city_to'] == $cityprint['value']){?> selected="selected"<? } ?> value="<?=$cityprint['value']?>"><?=$cityprint['name']?></option>
          <? } ?>
 	 </optgroup>
  
  
  <optgroup label="Регионы доставки">
	  <? foreach ($deliveryEMSpost->region_list as $regprint){?>
		<option <? if($_POST['del_city_to'] == $regprint['value']){?> selected="selected"<? } ?> value="<?=$regprint['value']?>"><?=$regprint['name']?></option>	   
      <? } ?>
  </optgroup>
  
  
</select>
<input type="submit" />
</form>
<span>Стоимость доставки до <?=$deliveryEMSpost->to['name']?> <?=$deliveryEMSpost->calc['price']?> руб. сроки <?=$deliveryEMSpost->calc['term']['min']?>-<?=$deliveryEMSpost->calc['term']['max']?> дня</span>
</body>
</html>








