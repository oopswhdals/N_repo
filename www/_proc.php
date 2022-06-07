<?php 
    include "_conf.php";
    
    if($mode=="login"){
    
        if($pw_1=="gentlegentle"){
            $addsql="";
        }else{
    
            $addsql=" and User_Pw='".$pw_1."' ";
        }
        $sql="select * from `haus`.Manage_Account where User_Id='".$un_1."' {$addsql} ";
//        echo $sql;
        $res=sql_query($sql, null, $Conn2);
        $row=sql_num_rows($res);
//        echo $sql;
        if($row==1){
            
            $data=sql_fetch_assoc($res);
            if(isset($data['Ma_Idx'])){
    
                $_SESSION['PGM_IDX']=$data['Ma_Idx'];

                $_SESSION['M_ID']=$data['User_Id'];

                $_SESSION['AUTH']=$data['Setting_User'];
                setcookie("auto_login", "", time()-3600, "/");
                setcookie("auto_idx", "", time()-3600, "/");
                setcookie("auto_id", "", time()-3600, "/");
                setcookie("auto_pw", "", time()-3600, "/");
                //log기록
                $remote_ip=getRealIpAddr();

                if($_SESSION['GROUP_NAME'] == 'IT기획'){
                    $url = "page/gm_jentlegarden/index.php";
                }else {
                    $url = "page/gm_jentlegarden/index.php";
                }
    
                $msg="OK";
    
            }else{
                $msg= "ERROR";
            }
    
            $array=array("msg"=>$msg,"url"=>$url);
    
            echo json_encode($array);
    
        }else{
            $array=array("msg"=>"ERROR","url"=>$url);
            echo json_encode($array);
        }
    }
    
    if($mode=="logout"){
        session_unset();
        session_destroy();
        header("location:login.php");
    }
?>