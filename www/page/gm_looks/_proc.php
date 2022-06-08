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

if($mode=="change_show_flag") {
    $update_sql = "update gm_looks_orderby set show_flag ='{$value}' where glo_idx='{$idx}'";
    if(sql_query($update_sql,null,$Conn2)){
        return "ok";
    }
}

if($mode=="card_delete") {
    
    $delete_sql = "delete from gm_looks where gl_idx='{$idx}'";
    if(sql_query($delete_sql,null,$Conn2)){
        $delete_sql = "delete from gm_looks_orderby where gl_idx='{$idx}'";
        sql_query($delete_sql,null,$Conn2);
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
            $orderby_cnt = 0;
            $type_array     = [];
            
            for ($i = 2 ; $i <= $maxRow ; $i++) {//행 긁어오기
                
                $mb_id      = $objWorksheet->getCell('A' . $i)->getValue();//ok
                $tag        = $objWorksheet->getCell('B' . $i)->getValue();//ok
                $sku        = $objWorksheet->getCell('C' . $i)->getValue();//ok
                $it_code    = $objWorksheet->getCell('D' . $i)->getValue();//ok
                $gender     = $objWorksheet->getCell('E' . $i)->getValue();//ok
                $img_url    = $objWorksheet->getCell('F' . $i)->getValue();//ok
                
                $psql="INSERT gm_looks
                SET 
                mb_id     = '$mb_id',
                tag_flag  = '$tag',
                sku       = '$sku',
                it_code   = '$it_code',
                gender    = '$gender',
                img_url   = 'https://web-resource.gentlemonster.com/event/".$img_url."',
                regdate   = now()";
                
                $pres           = sql_query($psql, null, $Conn2);
                $gl_idx         = sql_insert_id($Conn2);
                array_push($type_array,"All",$sku,$gender);
                
                if($pres){
                   
                    $arrayLength = count($type_array);
                    while ($orderby_cnt < $arrayLength)
                    {
                        $order_sql="
                        INSERT gm_looks_orderby
                        SET 
                        sort_flag       = '$type_array[$orderby_cnt]',
                        gl_idx          = '$gl_idx',
                        show_flag = '1',
                        orderby_num     = '0'
                        ";

                        $order_res = sql_query($order_sql, null, $Conn2);
                        $orderby_cnt++;
                    }

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

if($mode == "insert_img"){
    
    $mb_id              = $_POST['mb_id'];
    $tag_flag           = $_POST['tag_flag'];
    $sku                = $_POST['sku'];
    $it_code            = $_POST['it_code'];
    $gender             = $_POST['gender'];
  
    $ad_image   = file_get_contents($_FILES['userfile']['tmp_name']);
    $data       = base64_encode($ad_image);
    $timeout    = 30;
    $curl       = curl_init();
    $headers    = array("Content-Type:multipart/form-data");
    
    $data1['filename']      = time().".jpg";
    $data1['base64_encode'] = $data;

    curl_setopt($curl, CURLOPT_URL, 'https://api.systemiic.com/api/aws/ecom_resource_s3.php');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data1);
    
    $str        = curl_exec($curl);
    $resultarr  = curl_getinfo($curl);
    
    if ( $resultarr['http_code'] == 200) {
        $img_url = $str;
    } else {
        print_r($curl);
        echo "<br>".print_r($resultarr)." : 실패<br>";
    }
    curl_close ($curl);
    
    $type_array     = [];
    $orderby_cnt = 0;


    $psql="INSERT gm_looks
    SET 
    mb_id     = '$mb_id',
    tag_flag  = '$tag_flag',
    sku       = '$sku',
    it_code   = '$it_code',
    gender    = '$gender',
    img_url   = '$img_url',
    regdate   = now()";
    
    $pres           = sql_query($psql, null, $Conn2);
    $gl_idx         = sql_insert_id($Conn2);
    array_push($type_array,"All",$sku,$gender);
    
    if($pres){
       
        $arrayLength = count($type_array);
        while ($orderby_cnt < $arrayLength)
        {
            $order_sql="
            INSERT gm_looks_orderby
            SET 
            sort_flag       = '$type_array[$orderby_cnt]',
            gl_idx          = '$gl_idx',
            show_flag = '1',
            orderby_num     = '0'
            ";

            $order_res = sql_query($order_sql, null, $Conn2);
            $orderby_cnt++;
        }

        $msg=array("msg"=>"OK","text"=>$nasasd);
    } else{
        $msg=array("msg"=>"ERROR");
    }
    alert("업로드 완료");
    echo json_encode($msg);
}

if($mode == "delete_mode"){
		
    $sql="SELECT * from whitesun_img where wi_idx='".$idx."' ";

    $sql_res = sql_fetch($sql);


    if($sql_res){

        $del_sql = "DELETE from whitesun_img where wi_idx='".$idx."'";
        $del_res = sql_query($del_sql);
        echo "OK";
    }
}else if($mode == "orderby"){

    // if($tag_value =='전체'){
        //     $trun_sql = "TRUNCATE TABLE gm_looks_orderby";
        // }else{
            // }
            // exit;
    $trun_sql = "DELETE from gm_looks_orderby where sort_flag = '".$tag_value."'";
    $trun_res = sql_query($trun_sql, null, $Conn2);

    $sql = "INSERT into gm_looks_orderby (sort_flag, gl_idx, show_flag) values ";
    
    for($cnt=0; $cnt < count($order_arr); $cnt++){

        if($cnt == 0)
            $sql.= "('".$tag_value ."','".$order_arr[$cnt][0]."','".$order_arr[$cnt][1]."')";
        else
            $sql.= ",('".$tag_value ."','".$order_arr[$cnt][0]."','".$order_arr[$cnt][1]."')";

    }
    $insert_res = sql_query($sql, null, $Conn2);

    // echo $sql;

}

?>