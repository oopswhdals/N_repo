<?php

include "_conf.php";

header("Content-Type:text/html;charset=utf-8");
require_once("../../PHPExcel/Classes/PHPExcel.php");

    $sql = "SELECT * 
     FROM jentlegarden_event";
    
    $result = sql_query($sql);
    $cnt    = @sql_num_rows($result);


	$objPHPExcel = new PHPExcel();
    $sheet = $objPHPExcel->getActiveSheet();

    	// 제일 윗줄 설정
        $objPHPExcel-> setActiveSheetIndex(0)
        -> setCellValue("A1", "번호")
        -> setCellValue("B1", "Email")
        -> setCellValue("C1", "이름")
        -> setCellValue("D1", "전화")
        -> setCellValue("E1", "국가")
        -> setCellValue("F1", "IP")
        -> setCellValue("G1", "State")
        -> setCellValue("H1", "City")
        -> setCellValue("I1", "주소1")
        -> setCellValue("J1", "주소2")
        -> setCellValue("K1", "주소3")
        -> setCellValue("L1", "우편번호")
        -> setCellValue("M1", "Enter_key")
        -> setCellValue("N1", "Check_flag")
        -> setCellValue("O1", "등록일")
        -> setCellValue("P1", "주소입력 URL");
     

        for($i=1;$i<=$data=sql_fetch_array($result);$i++){   

            if($data['check_flag']== 0){
                $status_flag = "주소 입력x";
            }else{
                $status_flag = "O";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
            -> setCellValue("A".($i+1), $i)
            -> setCellValue("B".($i+1), $data['od_email'])
            -> setCellValue("C".($i+1), $data['od_name'])
            -> setCellValue("D".($i+1), $data['od_hp'])
            -> setCellValue("E".($i+1), $data['od_country'])
            -> setCellValue("F".($i+1), $data['od_ip'])
            -> setCellValue("G".($i+1), $data['od_state'])
            -> setCellValue("H".($i+1), $data['od_city'])
            -> setCellValue("I".($i+1), $data['od_address_1'])
            -> setCellValue("J".($i+1), $data['od_address_2'])
            -> setCellValue("K".($i+1), $data['od_address_3'])
            -> setCellValue("L".($i+1), $data['od_zipcode'])
            -> setCellValue("M".($i+1), $data['enter_key'])
            -> setCellValue("N".($i+1), $status_flag)
            -> setCellValue("O".($i+1), $data['regdate'])
            -> setCellValue("P".($i+1), "https://jentlegarden.gentlemonster.com?enter_key=".$data['enter_key'])
            ;
            
            }	
            
            // 시트 이름 설정
            $sheet->setTitle("member_purchase_data");
            
            // 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
        $filename = iconv("UTF-8", "EUC-KR", "jentlegarden_list");
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
?>

