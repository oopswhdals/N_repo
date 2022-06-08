<?php
include "_conf.php";
include_once rootDir."layout/top.php";
$total_sql="select *,count(*) as flag_count from `haus`.`Event_User`";
$total_data=sql_fetch($total_sql,null,$Conn2);

$s_total_sql="select *,count(*) as flag_count from `haus`.`Event_User` where Event_Flag in(1,3)";
$s_total_data=sql_fetch($s_total_sql,null,$Conn2);
//총 날짜
$kk=9;
$dd='2021-02-22';
?>



<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">content_copy</i>
                    </div>
                    <p class="card-category">총 참여수</p>
                    <h3 class="card-title"><?=number_format($total_data['flag_count'])?>

                    </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">당첨 수</p>
                    <h3 class="card-title"><?=number_format($s_total_data['flag_count']-1)?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">info_outline</i>
                    </div>
                    <p class="card-category">당첨률</p>
                    <h3 class="card-title"><?=number_format($s_total_data['flag_count']/$total_data['flag_count']*100)?>%</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-twitter"></i>
                    </div>
                    <p class="card-category">남은 당첨 티켓</p>
                    <h3 class="card-title"><?=number_format(5000-$s_total_data['flag_count']+1)?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <table class="table table-hover"  id="inittable">
                    <thead class="text-warning">
                    <tr>
                        <th class="text-center"></th>


                       <?php  for($i = 0; $i <= $kk;$i ++) { ?>
                                <th class="text-center"><?=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));?></th>
                       <?php }?>

                    </tr>
                    </thead>
                    <tbody>

<!--해당 일자 당첨 확률-->
                    <tr>
                        <th style='text-align: center;'>
                            당첨 확률(0~100)%
                        </th>
                    <?php
//                        for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {
                         for($i = 0; $i <= $kk;$i ++) {
                            ?>
                        <?php $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));

                        ?>

                             <?php
                             $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));
                             $search_sql="select * from `haus`.`Event_Setting`  where Lucky_Date='".$search_date."'";
                             $search_data=sql_fetch($search_sql, null, $Conn2);


                             ?>
                                <td style='text-align: center;'>
                                    <div class="form-group bmd-form-group">

                                        <?=$search_data['Lucky_Percentage']?>%
                                    </div>
                                </td>
                            <?php
                        }
                    ?>
                    </tr>

<!--해당 일자 당첨 갯수-->
                    <tr>
                        <th style='text-align: center;'>
                            셋팅 한 당첨 수
                        </th>
                        <?php
                        for($i = 0; $i <= $kk;$i ++) {
                            ?>
                            <?php
                            $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));
                            $search_sql="select * from `haus`.`Event_Setting`  where Lucky_Date='".$search_date."'";
                            $search_data=sql_fetch($search_sql, null, $Conn2);


                            ?>
                            <td style='text-align: center;'>
                                <div class="form-group bmd-form-group">

                                   <?=$search_data['Lucky_Maxcount']?>
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>

<!--해당 일자 응모자-->
                    <tr>
                        <th style='text-align: center;'>
                          일별 응모자
                        </th>
                        <?php
                        for($i = 0; $i <= $kk;$i ++) {
                            ?>





                            <?php
                            $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));
                            $search_sql="select *,count(*) as flag_count from `haus`.`Event_User`  where DATE_FORMAT(Event_Date,'%Y-%m-%d')='".$search_date."' ";
                            $search_data=sql_fetch($search_sql, null, $Conn2);


                            ?>
                            <td style='text-align: center;'>
                                <?=$search_data['flag_count']?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>

<!--해당 일자 당첨자-->
                    <tr>
                        <th style='text-align: center;'>
                            일별 당첨자
                        </th>
                        <?php
                        for($i = 0; $i <= $kk;$i ++) {
                            ?>
                            <?php
                            $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));
                            $search_sql="select *,count(*) as flag_count from `haus`.`Event_User`  where DATE_FORMAT(Event_Date,'%Y-%m-%d')='".$search_date."' and Event_Flag=1";
                            $search_data=sql_fetch($search_sql, null, $Conn2);
                            ?>
                            <td style='text-align: center;'>
                               <?=$search_data['flag_count']?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>

<!--해당 일자 당첨자-->
<tr>
    <th style='text-align: center;'>
        일별 수정
    </th>
    <?php
    for($i = 0; $i <= $kk;$i ++) {
        ?>
        <?php
        $search_date=date("Y-m-d",strtotime("+".($i)." days",strtotime($dd)));
        $search_sql="select *  from `haus`.`Event_Setting`  where DATE_FORMAT(Lucky_Date,'%Y-%m-%d')='".$search_date."'";
        $search_data=sql_fetch($search_sql, null, $Conn2);

            ?>
            <td style='text-align: center;'>
                <?php
                // if($search_date>date("Y-m-d")) {
                    ?>
                    <button class="btn btn-sm btn-primary"
                            onclick="location.href='setting_detail.php?idx=<?= $search_data['Es_Idx'] ?>'">수정
                    </button>
                    <?php
                // }
            ?>
            </td>
            <?php
    }
    ?>
</tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

?>
<?php
//search_script_back('1','2021-02-15');
function search_script_back($type,$date=null){
//    $date='2020-02-15';
    $search_sql="select * from `haus`.`Event_Setting`  where Lucky_Date=".$date;

    $search_res=sql_query($search_sql , null,$Conn2);

//    $search_data=sql_fetch_array($search_res);
//    echo $search_sql;
    $search_data=sql_fetch_assoc($search_res);


//    $search_data=sql_fetch($search_sql, null, $Conn2);

//    return print_r($resultArr);

    if($type=='percent'){
        echo $search_data['Lucky_Percentage'];
//        echo $search_sql;
    }else if($type=='maxcount'){
//        echo $search_data['Lucky_Maxcount'];
        echo $search_sql;
    }else{
        echo $search_sql;
    }

}
?>

<?php

include_once rootDir."layout/foot.php";
?>

