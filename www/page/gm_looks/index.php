<?php
include "_conf.php";
include_once rootDir."layout/top.php";

//////////////////////////페이징///
$cntPerPage=500;//한페이지 출력개수
$PAGE_PER_BLOCK = 10;

$vmode = $_GET['vmode'];

if($pageno == "")
    $pageno = 1;

////////////////////////////////////////////////////////////////////////////////
// 검색 정보 설정
////////////////////////////////////////////////////////////////////////////////

$num_per_page=10;
$page_per_block=10;

if( !$search_id ){
    $search_id = "All";
}

if( $search_id=="All" ){
    $searchSql[] = "tag_flag != '' and o.sort_flag = 'All'";
}else if($search_id=="Male"){
    $searchSql[] = "o.sort_flag = 'Men'";
}else if($search_id=="Female"){
    $searchSql[] = "o.sort_flag = 'Women'";
}else{
    $searchSql[] = "sort_flag = '".$search_id."'";
}

$search_res=" where ".@implode(" and ",$searchSql)." ";

$sql="SELECT * FROM gm_looks as t inner join gm_looks_orderby as o using(gl_idx)";
$sql.=$search_res;

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

// if($limit_num){
// }else{
//     $sql.=" order BY o.glo_idx desc ,t.gl_idx DESC limit $this_start_num,$limit_num";
// }

$sql.=" order BY o.glo_idx desc ,t.gl_idx DESC limit $this_start_num,$cntPerPage";
$res=sql_query($sql, null, $Conn2);
$num=sql_num_rows($res);

$s_total_sql="select *,count(*) as flag_count from gm_looks";
$s_total_data=sql_fetch($s_total_sql, null, $Conn2);

$ss_total_sql="select *,count(*) as flag_count from gm_looks";
$ss_total_data=sql_fetch($ss_total_sql, null, $Conn2);

$data_list_sql = "SELECT tag_flag, sku from gm_looks group by tag_flag";
$data_list_res = sql_query($data_list_sql, null, $Conn2);
$tag_value = $_REQUEST['search_id'];
// echo $sql;

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<style>
img {
    max-width: 100%;
    max-height: 100%;
}

#sortable {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 1200px;
    height: 1000px;
}

#sortable li {
    margin: 3px 3px 100px 0;
    padding: 1px;
    float: left;
    width: 230px;
    height: 230px;
    font-size: 1em;
    text-align: center;
    border:0;
    background:#fff;
}
.chkShowYN { vertical-align:-1px; }

</style>
<!-- <div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">Looks</p>
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
                    <p class="card-category">필요한 카운트 확인(best, , )</p>
                    <h3 class="card-title"><?=number_format($ss_total_data['flag_count'])?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td style="width: 45%">
                                <form action="index.php?vmode=<?=$vmode?>&search_id=<?=$search_id?>" name="search"
                                    id="search" method="post">
                                    <input type="text" autocomplete="off" class="form-control"
                                        style="width: 40%; margin-right: 5%; display:unset;" placeholder="태그"
                                        name="search_id" list="depList" id="search_id" value="<?=$tag_value?>"
                                        onkeypress="if(event.keyCode==13){f_search();}" />
                                    <datalist id="depList">
                                        <option value="All">All</option>
                                        <option value="Men">Men</option>
                                        <option value="Women">Women</option>
                                        <?
                                         for($i=0; $i<=$data=sql_fetch_array($data_list_res); $i++){ 
                                        ?>
                                        <option value="<?=$data['sku']?>"><?=$data['tag_flag']?></option>
                                        <?}?>
                                    </datalist>

                                    <input type="hidden" id="tag_value" value="<?=$_REQUEST['search_id']?>">
                                    <input type="hidden" id="search_idx" name="search_idx">

                                    <!-- <select name="limit_num" id="limit_num" class="form-control" style="width: 10%; margin-right: 5%; display:unset;">
                                       
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="200">200</option>
                                    </select> -->

                                    <button class="btn btn-round btn-just-icon" type="button" onclick="f_search()"><i
                                            class="material-icons">search</i></button>
                                </form>
                            </td>
                        </tr>
                        <form name="forderprint" action="./print_excel.php" onsubmit="return forderprintcheck(this);"
                            autocomplete="off">
                            <input type="submit" value="전체 현황 (EXCEL)"
                                style="width: 200px; float : right;  padding: 0.40625rem 0rem;"
                                class="btn btn-sm btn-success">
                        </form>
                        <button class="btn btn-sm btn-success "
                            style="width: 200px; float : right;  padding: 0.40625rem 0rem;" type="button"
                            onclick="excel_upload_open_pop('upload_excel_popup.php')">EXCEL 업로드</button>

                        <button class="btn btn-sm btn-success " style="width: 200px; float : right;  padding: 0.40625rem 0rem;" type="button" onclick="window.open('upload_popup.php','개별 업로드','width=500,height=500');">개별 업로드</button>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?if($vmode == "table"){?>
    <div class="container-fluid">
        <div class="col-md-12">
            <p style="padding-right:10px; text-align:right; margin-bottom:0;">
                <a href="index.php?vmode=easy&search_id=<?=$search_id?>">이지뷰</a>
            </p>
            <div class="card" style="margin-top:10px;">
                <div class="card-body">
                    <div class="pull-left">
                        Total <?=$num?>
                    </div>
                    <table class="table table-hover" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">Num</th>
                                <th class="text-center">Img</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Tag</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">IT_CODE</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">노출 여부</th>
                                <th class="text-center">regdate</th>
                                <th class="text-center">삭제</th>

                            </tr>
                        </thead>
                        <tbody>

                            <!-- 전체 보기 시작 -->
                            <?php
                            $sql = $sql.$addSql;
                            $res = sql_query ($sql, null, $Conn2);
        
                            if($num){
                                for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {
                                    ?>
                            <tr id="<?=$data['gl_idx'];?>,<?=$data['show_flag'];?>">
                                <td style="text-align: center;"><?=$i+1?></td>
                                <td style="text-align: center;">
                                    <img src="<?=$data['img_url'];?>" width=100 height=100 class="img-thumbnail">
                                </td>
                                <td style="text-align: center;"><?=$data['mb_id']?></td>
                                <td style="text-align: center;"><?=$data['tag_flag'];?></td>
                                <td style="text-align: center;"><?=$data['sku'];?></td>
                                <td style="text-align: center;"><?=$data['it_code'];?></td>
                                <td style="text-align: center;"><?=$data['gender'];?></td>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="show_flag" id="show_flag_<?=$data['glo_idx'];?>"
                                        onclick="change_show_flag(this,'table',<?=$data['glo_idx'];?>)"
                                        <?=$data['show_flag']==1? "checked":""?>>
                                </td>
                                <td style="text-align: center;"><?=$data['regdate'];?></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-danger" type="button" id="del_btn"
                                        onclick="f_del_2('<?=$data['gl_idx']?>');"
                                        style="width: 80px; padding: 0.40625rem 0rem;">삭제</button>
                                </td>
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
                            <!-- 전체보기 끝 -->
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

<?}else{?>
    <div class="container-fluid">
        <div class="col-md-12">
            <p style="padding-right:10px; text-align:right; margin-bottom:0px;">
                <a href="index.php?vmode=table&search_id=<?=$search_id?>">테이블뷰</a>
            </p>
            <div class="card" style="margin-top:10px; padding:0.9375rem 20px 0;">
                <div class="pull-left">
                    Total <?=$num?>
                </div>
                <div class="container" id="sortable_div">
                    <ul id="sortable" class="reorder_ul reorder-photos-list">
                        <?php
                        $sql = $sql.$addSql;
                        $res = sql_query ($sql, null, $Conn2);
                        for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {?>
                            <li id="<?=$data['gl_idx'];?>,<?=$data['show_flag'];?>" class="ui-state-default">
                                <a href="javascript:void(0);" style="float:none;" class="image_link">
                                    <img src="<?=$data['img_url'];?>" width=300 height=300 class="img-thumbnail">
                                </a>
                                <dd style="position:relative;">
                                    <?=$data['tag_flag'];?> 
                                    <button class="btn btn-sm btn-danger" type="button" id="del_btn" onclick="f_del_2('<?=$data['gl_idx']?>');" 
                                        style="height:20px; margin:auto; font-size:.8em; width:40px; padding:0 5px; position:absolute; right:0; top:3px;">
                                        삭제
                                    </button>
                                </dd>
                                <dd><?=$data['gender'];?></dd>
                                <label>노출여부
                                    <input type="checkbox" id="show_flag_<?=$data['glo_idx'];?>" name="show_flag" class="chkShowYN"
                                        onclick="change_show_flag(this, 'easy' , <?=$data['glo_idx'];?>)"
                                        <?=$data['show_flag']==1 ? "checked" : ""?>>
                                </label>
                                
                            </li>
                        <?}?>
                    </ul>
                </div>
                <div style="height:65px; margin-top:30px;">
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

<?}?>


<script type="text/javascript" src="../../Classes/TableDnD-master/js/jquery.tablednd.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $("#table-1").tableDnD({
            //드래그 기능이 동작하는 동안 특정 CLASS를 드래그하는 TR에 적용해준다. 
            // onDragStyle : 'css제어', 
            // onDropStyle : '드롭기능시 스타일', 
            // onDragClass: '클래스지정', 

            onDrop: function(table, row) {

                var tag_value = '<?=$tag_value?>';
                var order_arr = [];
                var rows = table.tBodies[0].rows;

                for (var i = 0; i < rows.length; i++) {
                    array = (rows[i].id).split(",");
                    order_arr.push(array)
                }
                order_arr.reverse();

                $.ajax({
                    url: "_proc.php",
                    type: "POST",
                    data: {
                        "order_arr": order_arr,
                        "mode": "orderby",
                        "tag_value": tag_value,
                    },

                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(msg) {
                        alert(msg);
                    }

                });
                window.location.reload();

            },
            onDragStart: function(table, row) {

            }
        }

    );
});

$("#sortable").sortable({
    update: function(event, ui) {
        var num = $('.ui-state-default').length;
        var tag_value = '<?=$tag_value?>';
        var order_arr = [];

        $("ul.reorder-photos-list li").each(function() {
            array = ($(this).attr('id').substr()).split(",");
            order_arr.push(array);
        });

        order_arr.reverse();

        $.ajax({
            url: "_proc.php",
            type: "POST",
            data: {
                "order_arr": order_arr,
                "mode": "orderby",
                "tag_value": tag_value,
            },

            dataType: "json",
            async: false,
            cache: false,
            success: function(msg) {
                // window.location.reload();
            }
        });
        // reloadDivArea();
    },
    
    stop: function(event, ui) {
        location.reload();
        // $('#sortable_div').load(location.href + ' #sortable_div');
    }
}).disableSelection();

function f_search() {
    document.search.submit();
}

function change_show_flag(obj,type, idx) {

    if ($('#show_flag_' + idx).is(":checked") == true) {
        value = 1
    } else {
        value = 0
    }
   
    $.ajax({
            type: "POST",
            url: "_proc.php",
            data: {
                mode: "change_show_flag",
                idx: idx,
                value: value
            }
        })
        .done(function(msg) {
            // location.reload();
        });
        if(type=="easy"){
            // 노출여부 id값 변경        
            var preId = $(obj).parent('label').parent('li').attr('id').split(',');
            var tmpID = preId[0] + ',' + value;
            $(obj).parent('label').parent('li').attr('id',tmpID);
        }else{
            var preId = $(obj).parent('td').parent('tr').attr('id').split(',');
            var tmpID = preId[0] + ',' + value;
            $(obj).parent('td').parent('tr').attr('id',tmpID);
        }
}

function f_del_2(idx) {
    if (confirm("해당 태그와 관련된 looks가 모두 삭제됩니다.") == false) return false;
    $.ajax({
            type: "POST",
            url: "_proc.php",
            data: {
                mode: "card_delete",
                idx: idx
            }
        })
        .done(function(msg) {
            // alert(msg);
            location.reload();
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