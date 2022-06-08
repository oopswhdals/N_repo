<?php
include "_conf.php";

include rootDir . "layout/head.php";
?>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>GM - looks 엑셀 업로드</h4>
                <form action="_proc.php" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="mode" value="excel_asbase_insert">

                    <div class="form-group">
                        <input type="file" id="exampleInputFile" name="file_excel"/>
                        <p class="help-block">등록 할 파일을 선택해주세요. <a href="upload/looks_sample_1.xlsx">[샘플다운]</a>
                        </p>
                    </div>
                    <div style="text-align: center;">
                        <button type="submit" class="btn" style="background-color: #666;">등록</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

