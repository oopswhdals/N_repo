<?php
include "_conf.php";
include_once rootDir."layout/top.php";

//////////////////////////페이징///
$cntPerPage=20;//한페이지 출력개수
$PAGE_PER_BLOCK = 10;


if($pageno == "")
    $pageno = 1;

////////////////////////////////////////////////////////////////////////////////
// 검색 정보 설정
////////////////////////////////////////////////////////////////////////////////

$num_per_page=10;
$page_per_block=10;

if($idx){
    $searchSql[] = "GCL_IDX = '".$idx."'";
}
if($status_flag){
    $searchSql[] = "Event_Flag = '".$status_flag."'";
}

if($search_id){
    $searchSql[] = "User_Phone like '%".$search_id."'";
}
$searchSql[]="Event_Flag in ('1','3')";

if(($searchSql)){

    $search_res=" where ".@implode(" and ",$searchSql)." ";
}

$sql="SELECT * FROM `haus`.`Event_User` ".$search_res;

$res=sql_query($sql, null, $Conn2);

////////////////////////////////////////////////////////////////////////////////
// 페이징정보 마무리
////////////////////////////////////////////////////////////////////////////////
$recordCnt =$recordCnt2= $total_c=sql_num_rows($res);

$totalpage=ceil($total_c/$cntPerPage);
if($totalpage<1)
    $totalpage=1;
$this_start_num=$pageno*$cntPerPage-$cntPerPage;
////////////////////////페이징끝///

$sql.=" order by Eu_Idx desc limit $this_start_num,$cntPerPage";
$res=sql_query($sql, null, $Conn2);
$num=sql_num_rows($res);


$s_total_sql="select *,count(*) as flag_count from `haus`.`Event_User` where Event_Flag =1";
$s_total_data=sql_fetch($s_total_sql,null,$Conn2);

$ss_total_sql="select *,count(*) as flag_count from `haus`.`Event_User` where Event_Flag =3";
$ss_total_data=sql_fetch($ss_total_sql,null,$Conn2);
?>
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">발급대기 티켓 수량</p>
                    <h3 class="card-title"><?=number_format($s_total_data['flag_count'])?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="fa fa-twitter"></i>
                    </div>
                    <p class="card-category">발급한 티켓 수량</p>
                    <h3 class="card-title"><?=number_format($ss_total_data['flag_count']-1)?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td style="width: 45%">
                                <form action="index.php" name="search" id="search" method="post">
                                    <select class="form-control" style="width: 40%; margin-right: 5%; display:unset;" name="status_flag" id="status_flag">
                                        <option value="">상태값 선택</option>
                                        <option value='1' <?=($status_flag=='1'?'selected':'')?>>당첨</option>
                                        <option value="3" <?=($status_flag=='3'?'selected':'')?>>수령</option>

                                    </select>
                                    <input type="text" class="form-control" style="width: 40%; margin-right: 5%; display:unset;" placeholder="전화번호 뒷자리 (ex. 1234 )" name="search_id" id="search_id" value="<?=$search_id?>" onkeypress="if(event.keyCode==13){f_search();}"/>
                                    <input type="hidden" id="search_idx" name="search_idx" >
                                    <button class="btn btn-round btn-just-icon" type="button" onclick="f_search()"><i class="material-icons">search</i></button>
                                </form>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-left">
                    Total:<?=$num?>
                </div>
                <table class="table table-hover"  id="inittable">
                    <thead>
                    <tr>
                        <th class="text-center">
                            <label for="chkall"></label>
                            <input type="checkbox" id="chkall" name="chkall" value="1" >
                        </th>
                        <th class="text-center">이름</th>
                        <th class="text-center">전화번호</th>
                        <th class="text-center">당첨시간</th>
                        <th class="text-center">상태</th>
                        <th class="text-center">발급여부</th>
                        <th class="text-center"></th>
                        <!--
                        <th class="text-center">관리자만</th>
                        -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql =$sql.$addSql;

                    $res = sql_query ( $sql, null, $Conn2);

                    if($num){
                        for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {
                            ?>
                            <tr >
                                <td style="text-align: center;">
                                    <input type="checkbox" id="chk_<?= $i; ?>" name="chk" value="<?= $data['GCI_IDX'] ?>" title="내역선택">
                                </td>
                                <td style="text-align: center;"><?=$data['User_Name']?></td>
                                <td style="text-align: center;"><?= $data['User_Phone'];?></td>
                                <td style="text-align: center;"><?=$data['Event_Date'];?></td>
                                <td style='text-align: center;'>
                                    <?php
                                    switch ($data['Event_Flag']) {
                                        case '0' :
                                            echo "미완료";
                                            break;
                                        case '1' :
                                            echo "당첨";
                                            break;
                                        case '2' :
                                            echo "미당첨";
                                            break;
                                        case '3' :
                                            echo "수령";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td style='text-align: center;'>
                                    <?php
                                    switch ($data['Event_Visit']) {

                                        case '1' :
                                            echo $data['Event_Visit_Date']." 쿠폰발급";
                                            break;

                                    }
                                    ?>
                                </td>

                                <td style="text-align: center;">
                                    <?php
                                    if($data['Event_Flag']==1){
                                    ?>
                                    <button class="btn btn-sm btn-success" type="button" id="del_btn_<?=$data['Eu_Idx']?>" onclick="f_del('<?=$data['Eu_Idx']?>');" style="width: 80px; padding: 0.40625rem 0rem;" <?=($data['Event_Flag']=='2'?"disabled":'')?>>발급</button>
                                    <?php }?>
                                    <?php
                                    if($data['Event_Flag']==3){
                                        ?>
                                        <button class="btn btn-sm btn-danger" type="button" id="del_btn_<?=$data['Eu_Idx']?>" onclick="f_del_return('<?=$data['Eu_Idx']?>');" style="width: 80px; padding: 0.40625rem 0rem;" >발급취소</button>
                                    <?php }?>
                                </td>
                                <!--
                                <td style="text-align: center;">

                                        <button class="btn btn-sm btn-danger" type="button" id="del_btn_<?=$data['Eu_Idx']?>" onclick="f_del_2('<?=$data['Eu_Idx']?>');" style="width: 80px; padding: 0.40625rem 0rem;" disabled>삭제</button>
                                </td>
                                   -->

                            </tr>
                            <?php
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="5">
                                No Data
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div style="height: 65px;">
                    <?php
                    parse_str($_SERVER['QUERY_STRING'],$HTTPQueryArray);
                    unset($HTTPQueryArray['pageno']);

                    $param = '&'.http_build_query($HTTPQueryArray);
                    include rootDir."inc/pageing.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

    function f_search(){
        document.search.submit();
        // var search_id = $("#search_id").val();
        // $.ajax({
        //     type: "POST",
        //     url: "_proc.php",
        //     dataType:"JSON",
        //     data: { mode:"search_id", search_id:search_id}
        // }).done(function(msg){
        //
        // });
    }

    function f_del_return(idx){
        if(confirm("발급취소 하시겠습니까?")==false)return false;


        $.ajax({
            type: "POST",
            url: "_proc.php",
            data: { mode:"card_receipt_return",idx:idx }
        })
            .done(function( msg ) {
                // alert(msg);
                location.reload();
            });


    }

    function f_del(idx){
        if(confirm("발급 하시겠습니까?")==false)return false;


            $.ajax({
                type: "POST",
                url: "_proc.php",
                data: { mode:"card_receipt",idx:idx }
            })
                .done(function( msg ) {
                    // alert(msg);
                    location.reload();
                });


    }
    function f_del_2(idx){
        if(confirm("삭제 하시겠습니까?")==false)return false;


        $.ajax({
            type: "POST",
            url: "_proc.php",
            data: { mode:"card_delete",idx:idx }
        })
            .done(function( msg ) {
                // alert(msg);
                location.reload();
            });


    }


    function change_flag(idx, status){
        if(confirm("유효기간을 연장하시겠습니까?")){
            if(status!="1"){
                alert("연장불가능한 상품권입니다.");
                $("#extend_flag_"+idx).prop("checked",false);
            }else{
                if(($("#extend_flag_"+idx).is(":checked"))){
                    var val = 1;
                }
                $.ajax({
                    type: "POST",
                    url: "_proc.php",
                    data: { mode:"change_flag",idx:idx,  val:val}
                }).done(function( msg ) {
                    if(msg=='OK'){
                        alert("연장되었습니다.");
                        location.reload();
                    }
                });
            }
        }else{
            $("#extend_flag_"+idx).prop("checked",false);
        }
    }

    function change_select(idx, flag, val){
        console.log(idx, flag, val);
        $.ajax({
            type: "POST",
            url: "_proc.php",
            data: { mode:"change_select",idx:idx, flag:flag, val:val}
        }).done(function( msg ) {
            console.log(msg);
        });
    }
</script>
<?php

include_once rootDir."layout/foot.php";
?>
