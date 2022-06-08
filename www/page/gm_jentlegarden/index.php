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
    $searchSql[] = "je_idx = '".$idx."'";
}
if($status_flag){
    $searchSql[] = "check_flag = '".$status_flag."'";
}

if($search_id){
    $searchSql[] = "od_hp like '%".$search_id."'";
}
$searchSql[]="check_flag in ('0','1')";

if(($searchSql)){

    $search_res=" where ".@implode(" and ",$searchSql)." ";
}

$sql="SELECT * FROM jentlegarden_event ".$search_res;

$res=sql_query($sql);
// echo $sql;

////////////////////////////////////////////////////////////////////////////////
// 페이징정보 마무리
////////////////////////////////////////////////////////////////////////////////
$recordCnt =$recordCnt2= $total_c=sql_num_rows($res);

$totalpage=ceil($total_c/$cntPerPage);
if($totalpage<1)
    $totalpage=1;
$this_start_num=$pageno*$cntPerPage-$cntPerPage;
////////////////////////페이징끝///

$sql.=" order by je_idx desc limit $this_start_num,$cntPerPage";
$res=sql_query($sql);
$num=sql_num_rows($res);


$s_total_sql="select *,count(*) as flag_count from jentlegarden_event";
$s_total_data=sql_fetch($s_total_sql);

$ss_total_sql="select *,count(*) as flag_count from jentlegarden_event where check_flag =1";
$ss_total_data=sql_fetch($ss_total_sql);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">당첨 수</p>
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
                    <p class="card-category">주소입력 수</p>
                    <h3 class="card-title"><?=number_format($ss_total_data['flag_count'])?></h3>
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
                                        <option value="0" <?=($status_flag=='0'?'selected':'')?>>주소입력x</option>
                                        <option value="1" <?=($status_flag=='1'?'selected':'')?>>입력완료</option>

                                    </select>
                                    <input type="text" class="form-control" style="width: 40%; margin-right: 5%; display:unset;" placeholder="전화번호 뒷자리 (ex. 1234 )" name="search_id" id="search_id" value="<?=$search_id?>" onkeypress="if(event.keyCode==13){f_search();}"/>
                                    <input type="hidden" id="search_idx" name="search_idx" >
                                    <button class="btn btn-round btn-just-icon" type="button" onclick="f_search()"><i class="material-icons">search</i></button>
                                </form>
                            </td>
                        </tr>
                        <form name="forderprint" action="./print_excel.php"
                                onsubmit="return forderprintcheck(this);" autocomplete="off">
                                <input type="submit" value="이벤트 전체 현황 (EXCEL)"
                                    style="width: 200px; float : right;  padding: 0.40625rem 0rem;"
                                    class="btn btn-sm btn-success">
                            </form>
                        <button class="btn btn-sm btn-success " style="width: 200px; float : right;  padding: 0.40625rem 0rem;" type="button" onclick="excel_upload_open_pop('upload_excel_popup.php')">당첨자 EXCEL 업로드</button>

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
                        <!-- <th class="text-center">
                            <label for="chkall"></label>
                            <input type="checkbox" id="chkall" name="chkall" value="1" >
                        </th> -->
                        <th class="text-center">Email</th>
                        <th class="text-center">이름</th>
                        <th class="text-center">전화번호</th>
                        <th class="text-center">국가</th>
                        <th class="text-center">주소</th>
                        <th class="text-center">enter_key</th>
                        <th class="text-center"></th>
                        <!--
                        <th class="text-center">관리자만</th>
                        -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql =$sql.$addSql;

                    $res = sql_query ($sql);

                

                    if($num){
                        for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {
                            ?>
                            <tr >
                                <!-- <td style="text-align: center;">
                                    <input type="checkbox" id="chk_<?= $i; ?>" name="chk" value="<?= $data['GCI_IDX'] ?>" title="내역선택">
                                </td> -->
                                <td style="text-align: center;"><?=$data['od_email']?></td>
                                <td style="text-align: center;"><?=$data['od_name'];?></td>
                                <td style="text-align: center;"><?=$data['od_hp'];?></td>
                                <td style="text-align: center;"><?=$data['od_country'];?></td>
                                <?
                                if(!$data['od_zipcode']){?>
                                <td style="text-align: center;"></td>

                                <?}else{?>
                                <td style="text-align: center;"><?=$data['od_state']." ".$data['od_city']." ".$data['od_address_1']." ".$data['od_address_2']." ".$data['od_address_3']." (".$data['od_zipcode'].")";?></td>

                                <?}?>
                                <!-- <td style="text-align: center;"><?=$data['od_state']." ".$data['od_city']." ".$data['od_address_1']." ".$data['od_address_2']." ".$data['od_address_3']." (".$data['od_zipcode'].")";?></td> -->
                                
                                <td style="text-align: center;"><?=$data['enter_key'];?></td>


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

    
function forderprintcheck(f) {
    if (f.csv[0].checked || f.csv[1].checked) {
        f.target = "_top";
    } else {
        var win = window.open("", "winprint",
            "left=10,top=10,width=670,height=800,menubar=yes,toolbar=yes,scrollbars=yes");
        f.target = "winprint";
    }

    f.submit();
}
</script>
<?php

include_once rootDir."layout/foot.php";
?>
