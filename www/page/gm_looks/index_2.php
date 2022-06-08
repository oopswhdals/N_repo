<?php
include "_conf.php";
include_once rootDir."layout/top.php";

//////////////////////////페이징///
$cntPerPage=50;//한페이지 출력개수
$PAGE_PER_BLOCK = 10;


if($pageno == "")
    $pageno = 1;

////////////////////////////////////////////////////////////////////////////////
// 검색 정보 설정
////////////////////////////////////////////////////////////////////////////////

$num_per_page=10;
$page_per_block=10;

if($search_id){
    if($search_id=="All"){
        $searchSql[] = "tag_flag != '' and o.sort_flag = 'All'";
    }else if($search_id=="Male"){
        $searchSql[] = "o.sort_flag = 'Men'";
    }else if($search_id=="Female"){
        $searchSql[] = "o.sort_flag = 'Women'";
    }else{
        $searchSql[] = "sort_flag = '".$search_id."'";
    }

    $search_res=" where ".@implode(" and ",$searchSql)." ";
}else{
    $searchSql[] = "tag_flag != '' and o.sort_flag = 'All'";
    $search_res=" where ".@implode(" and ",$searchSql)." ";
}

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
$sql.=" order BY o.glo_idx desc ,t.gl_idx DESC limit $this_start_num,$cntPerPage";

// if($limit_num){
// }else{
//     $sql.=" order BY o.glo_idx desc ,t.gl_idx DESC limit $this_start_num,$limit_num";
// }

$res=sql_query($sql, null, $Conn2);
$num=sql_num_rows($res);

$s_total_sql="select *,count(*) as flag_count from gm_looks";
$s_total_data=sql_fetch($s_total_sql, null, $Conn2);

$ss_total_sql="select *,count(*) as flag_count from gm_looks";
$ss_total_data=sql_fetch($ss_total_sql, null, $Conn2);

$data_list_sql = "SELECT tag_flag, sku from gm_looks group by tag_flag";
$data_list_res = sql_query($data_list_sql, null, $Conn2);
$tag_value = $_POST['search_id'];
// echo $sql;

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
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
    margin: 3px 3px 90px 0;
    padding: 1px;
    float: left;
    width: 230px;
    height:230px;
    font-size: 1em;
    text-align: center;
}
</style>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td style="width: 45%">
                                <form action="index_2.php" name="search" id="search" method="post">
                                    <input type="text" autocomplete="off" class="form-control"
                                        style="width: 40%; margin-right: 5%; display:unset;" placeholder="태그"
                                        name="search_id" list="depList" id="search_id" value="<?=$search_id?>"
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

                                    <input type="hidden" id="tag_value" value="<?=$_POST['search_id']?>">
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
                        <!-- <button class="btn btn-sm btn-success " style="width: 200px; float : right;  padding: 0.40625rem 0rem;" type="button" onclick="excel_upload_open_pop('upload_popup.php')">개별 업로드</button> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

            <div class="container">
                <ul id="sortable" class="reorder_ul reorder-photos-list">
                    <?php
                    $sql = $sql.$addSql;
                    $res = sql_query ($sql, null, $Conn2);
                    for($i = $this_start_num; $i <= $data = sql_fetch_assoc ( $res ); $i ++) {?>

                    <li id="<?=$data['gl_idx'];?>,<?=$data['show_flag'];?>" class="ui-state-default">
                        <a href="javascript:void(0);" style="float:none;" class="image_link">
                            <img src="<?=$data['img_url'];?>" width=300 height=300 class="img-thumbnail">
                        </a>
                        <dd><?=$data['tag_flag'];?></dd>
                        <dd><?=$data['gender'];?></dd>
                        <label >노출여부
                        <input type="checkbox" id = "show_flag_<?=$data['glo_idx'];?>" name = "show_flag" onclick = "change_show_flag(<?=$data['glo_idx'];?>)" 
                             <?=$data['show_flag']==1? "checked":""?>>
                        </label>
                    </li>
                    <span>
                    </span>
                    <?}?>
                </ul>
          
    </div>
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
<script type="text/javascript" src="../../Classes/TableDnD-master/js/jquery.tablednd.js"></script>
<script type="text/javascript">
$(document).ready(function() {

});

$( "#sortable").sortable({
    update: function( event, ui ) {
        var num= $('.ui-state-default').length;
        
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
                "order_arr" : order_arr,
                "mode" : "orderby",
                "tag_value" : tag_value,
                },

            dataType: "json",
            async: false,
            cache: false,
            success: function(msg) {
                alert(msg);
            }
            
        });
    }
});

function f_search() {
    document.search.submit();
}


function change_show_flag(idx) {

    if (confirm("변경하시겠습니까?") == false) return false;

    if($('#show_flag_'+idx).is(":checked") == true){
        value = 1
    }else{
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

}

function f_del_2(idx) {
    if (confirm("삭제 하시겠습니까?") == false) return false;
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<?php

include_once rootDir."layout/foot.php";
?>