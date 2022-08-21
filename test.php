<?php 
include "ACMEarray.php";
include "Instrument.php";
if($_GET){
    if(isset($_GET['itemSelected'])){
        foreach($inventory as $item){
            if($item[0] === $_GET['itemSelected']){
                $arr = array($item[0], $item[2]);
                echo json_encode($arr);
                break;
            }
        }
   
  }
  if(isset($_GET['discount'])&& isset($_GET['item'])){
    foreach($inventory as $item){
        if($item[0] === $_GET['item']){
            if($item[2]*(100 - $_GET['discount'])/100 < $item[1]){
                echo -1;
            }else{
                $finalPrice = $item[2]*(100 - $_GET['discount'])/100;
                echo $finalPrice;
            }
        }}

  }


}

