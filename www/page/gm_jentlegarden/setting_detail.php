<?php
include "_conf.php";
include_once rootDir."layout/top.php";




$sql="select * from `haus`.`Event_Setting` where Es_Idx='".$idx."'";
$data=sql_fetch($sql,null,$Conn2);
if(!$data['Es_Idx'])
    alert("잘못된 경로입니다.","setting.php");
?>



<div class="container-fluid">
    <form action="_proc.php" method="post">
        <input type="hidden" name="mode" value="setting_detail">
        <input type="hidden" name="Es_Idx" value="<?=$data['Es_Idx']?>">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title"><?=$data['Lucky_Date']?></h4>
                    <p class="card-category">당첨확률 및 당첨 수량을 변경하려면 변경하여주세요.</p>
                </div>
                <div class="card-body">
                    <form>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="bmd-label-floating">Percent(0-100%)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group bmd-form-group ">
                                    현재상태: <?=$data['Lucky_Percentage']?>%
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group bmd-form-group">
                                    <input type="number" class="form-control"  min="0" max="100" name="Lucky_Percentage">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="bmd-label-floating">Count(총 5000ea)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group bmd-form-group ">
                                    당첨갯수: <?=$data['Lucky_Maxcount']?>ea
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group bmd-form-group">
                                    <input type="number" class="form-control" min="0" max="5000" name="Lucky_Maxcount">
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </form>

</div>


<?php

include_once rootDir."layout/foot.php";
?>

