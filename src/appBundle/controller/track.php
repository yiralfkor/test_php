<?php

//require_once '../entity/Find.php';

if (isset($_POST['tracking']) && isset($_POST['imei'])){
    
    $imei = $_POST['imei'];

    //find imei
    //$find = new Find();
    //$result = $find->FindImei($imei);
    
    function FindImei($imei, $pdo){
        $find = $pdo->prepare("SELECT 
                                        *
                                FROM shipment_vehicle A 
                                inner join shipment B using(imei)
                                where imei = :imei");
	    $find->bindValue(':imei',$imei,PDO::PARAM_INT);
	    $find->execute();	
        $result = $find->fetch();
        
        if ($find->rowCount() >=1){
            return true;
        } else {
            return false;
        }
    }

    function FindImeiResult($imei, $pdo){
        $find = $pdo->prepare("SELECT   
                                        *
                                        ,  B.id
                                        , now() as hora
                                FROM shipment_vehicle A 
                                inner join shipment B using(imei)
                                where imei = :imei");
	    $find->bindValue(':imei',$imei,PDO::PARAM_INT);
	    $find->execute();	
        $result = $find->fetchAll();
        
        $allresult = [];
        foreach ($result as $row) {
			$allresult[] = $row;
		}
        
        return $allresult;

    }

  
    function InsertTraza($id, $hora, $pdo){

        $insertTrazo = $pdo->prepare("INSERT INTO tracking (idshipment, consulttime) VALUES (:id, :hora);");
        $insertTrazo->bindValue(':id',$id,PDO::PARAM_INT);
        $insertTrazo->bindValue(':hora',$hora,PDO::PARAM_INT);
        //$insertTrazo->bindValue(':interval',$interval,PDO::PARAM_INT);
	    $insertTrazo->execute();	
        
    }

    if (FindImei($imei, $pdo) == true){
        $temp = FindImeiResult($imei, $pdo);
        //var_dump($temp);
        
        foreach ($temp as $row) {
            // is better on json
            echo "Order Number -> ".$row['order_id'];
            echo "\n";
            echo "Customer Email -> ".$row['customer_email'];
            echo "\n";
            echo "number of the vehicle -> ".$row['vehicle_id'];
            echo "\n";
            echo "Track start -> ".$row['track_start'];
            echo "\n";
            echo "Time of tracking -> ". $row['hora'];
            echo "\n";
            InsertTraza($row['id'], $row['hora'], $pdo);
        }
        
    } else {
        echo "There are no shipments associated with that vehicle";
    }
    
} else {
    echo "missing parameters for tracking";
}


?>