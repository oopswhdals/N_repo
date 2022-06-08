<?php
include "_conf.php";
if($mode=="setting_detail") {


    $select_sql="select sum(Lucky_Maxcount) as tot_maxcount from `haus`.`Event_Setting` where Es_Idx!='{$Es_Idx}'";
    $select_data=sql_fetch($select_sql,null,$Conn2);

    if($select_data['tot_maxcount']+$Lucky_Maxcount>5000){
        alert('당첨수량이 5000개 초과 입니다.','setting_detail.php?idx='.$Es_Idx);
        exit;
    }else {

        $update_sql = "update `haus`.`Event_Setting` set Lucky_Percentage ='{$Lucky_Percentage}' , Lucky_Maxcount ='{$Lucky_Maxcount}' where Es_Idx='{$Es_Idx}'";
        if(sql_query($update_sql,null,$Conn2)){
            alert($Lucky_Percentage."% / ".$Lucky_Maxcount."Ea 로 변경 되었습니다.","setting.php");
        }
    }

}
if($mode=="card_receipt") {



        $update_sql = "update `haus`.`Event_User` set Event_Visit ='1',Event_Flag=3 ,Event_Visit_Date=now() where Eu_Idx='{$idx}'";
        if(sql_query($update_sql,null,$Conn2)){
            return "ok";
        }


}
if($mode=="card_receipt_return") {



    $update_sql = "update `haus`.`Event_User` set Event_Visit ='0',Event_Flag=1 ,Event_Visit_Date=now() where Eu_Idx='{$idx}'";
    if(sql_query($update_sql,null,$Conn2)){
        return "ok";
    }


}
if($mode=="card_delete") {



    $update_sql = "delete from `haus`.`Event_User`  where Eu_Idx='{$idx}'";
    if(sql_query($update_sql,null,$Conn2)){

        $update_sql2= "delete from `haus`.`Event_Detail` where Eu_Idx='{$idx}'";
        sql_query($update_sql2,null,$Conn2);
        return "ok";
    }


}

    ?>