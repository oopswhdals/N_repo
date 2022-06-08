<?php

include "_conf.php";

header("Content-Type:text/html;charset=utf-8");
require_once("../../PHPExcel/Classes/PHPExcel.php");

    $sql = "SELECT * 
     FROM gm_looks";
    
    $result = sql_query($sql, null, $Conn2);
    $cnt    = @sql_num_rows($result);


	$objPHPExcel = new PHPExcel();
    $sheet = $objPHPExcel->getActiveSheet();

    	// 제일 윗줄 설정
        $objPHPExcel-> setActiveSheetIndex(0)
        -> setCellValue("A1", "NUM")
        -> setCellValue("B1", "ID")
        -> setCellValue("C1", "tag")
        -> setCellValue("D1", "sku")
        -> setCellValue("E1", "it_code")
        -> setCellValue("F1", "gender")
        -> setCellValue("G1", "img_url")
        -> setCellValue("H1", "regdate")
        ;
     

        for($i=1;$i<=$data=sql_fetch_array($result);$i++){   

            if($data['check_flag']== 0){
                $status_flag = "주소 입력x";
            }else{
                $status_flag = "O";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
            -> setCellValue("A".($i+1), $i)
            -> setCellValue("B".($i+1), $data['mb_id'])
            -> setCellValue("C".($i+1), $data['tag_flag'])
            -> setCellValue("D".($i+1), $data['sku'])
            -> setCellValue("E".($i+1), $data['it_code'])
            -> setCellValue("F".($i+1), $data['gender'])
            -> setCellValue("G".($i+1), $data['img_url'])
            -> setCellValue("H".($i+1), $data['regdate'])
            ;
            
            }	
            
            // 시트 이름 설정
            $sheet->setTitle("member_purchase_data");
            
            // 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
        $filename = iconv("UTF-8", "EUC-KR", "GM_looks_list");
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
?>

