<?php
include "_conf.php";

function GenerateString($length){  
    $characters  = "0123456789";  
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  

    $nmr_loops = $length;  
    $string_generated = "";  
    
    while( $nmr_loops-- ){  
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
    } 
    
    return $string_generated;  
} 

function get_enter_key($num){

    $code_loops = false;  

    $enter_key = "";
    
    while(!$code_loops){
        
        $string_generated = GenerateString($num);
        
        $selCode = "
        SELECT *
        FROM jentlegarden_event
        WHERE enter_key = '".$string_generated."'
        ";
        $resCode = sql_query($selCode);
        $rowspan = sql_num_rows($resCode);
        
        if( $rowspan == 0 ){
            $enter_key = $string_generated;
            $code_loops = true;
        }
        
    }
    return $enter_key;  
}

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


if($mode=="excel_asbase_insert"){


    $relate_idx     = array();
    $uploads_dir    = 'upload';

    // 변수 정리
    $error = $_FILES['file_excel']['error'];
    $name = $_FILES['file_excel']['name'].date("Ymdhis");
    $ext = array_pop(explode('.', $name));

    move_uploaded_file( $_FILES['file_excel']['tmp_name'], "$uploads_dir/$name");

    set_include_path(get_include_path() . PATH_SEPARATOR . rootDir.'Classes/');

    require_once "PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
    $objPHPExcel = new PHPExcel();
    require_once "PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
    $filename = $uploads_dir."/".$name; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.
    try {

        // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        // 읽기전용으로 설정
        $objReader->setReadDataOnly(true);
        // 엑셀파일을 읽는다
        $objExcel = $objReader->load($filename);

        //형식 검수
        for ($j = 0 ; $j < 1 ; $j++) {//시트 셋팅

            $objExcel->setActiveSheetIndex($j);
            $objWorksheet = $objExcel->getActiveSheet();
            $rowIterator = $objWorksheet->getRowIterator();
            
            foreach ($rowIterator as $row) { // 모든 행에 대해서
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
            }
            $maxRow = $objWorksheet->getHighestRow();


            $success=0;
            $fail=0;
            for ($i = 2 ; $i <= $maxRow ; $i++) {//행 긁어오기

                $od_email = $objWorksheet->getCell('B' . $i)->getValue();//ok
                // $serial_code = $objWorksheet->getCell('C' . $i)->getValue(); // 시리얼코드
                $Login_code = get_enter_key(10);
                
                // $name = $objWorksheet->getCell('C' . $i)->getValue();//ok

                $psql="INSERT jentlegarden_event 
                SET 
                od_email = '$od_email',
                enter_key = '$Login_code',
                check_flag = 0,
                regdate = now()";
                if( $pres=sql_query($psql)){
                    $success++;
                } else{
                    $fail++;
                }
                
            } //엑셀 행 끝
            
        }//시트세팅 끝
        // exit;
        alert($success."건 성공했습니다. ".$fail."건 실패했습니다.");
        
    }catch (exception $e) {
        echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
    }
    
}


?>