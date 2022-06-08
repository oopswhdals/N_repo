<?php
include "_conf.php";

include rootDir . "layout/head.php";
?>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>GM - looks 개별 업로드</h4>
                <form action="_proc.php" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="mode" value="insert_img">
                    <div class="form-group">
                        <input type="file"  class="form-control" id="userfile"  name="userfile"/>
                    </div>
                    <img id="image" />

                    <input type="text" autocomplete="off" class="form-control" 
                    placeholder="ID" name="mb_id"  value="">

                    <input type="text" autocomplete="off" class="form-control" 
                    placeholder="태그" name="tag_flag" value="">
                    
                    <input type="text" autocomplete="off" class="form-control" 
                    placeholder="SKU" name="sku" value="">

                    <input type="text" autocomplete="off" class="form-control" 
                    placeholder="It_code" name="it_code" value="">

                    <input type="text" autocomplete="off" class="form-control" 
                    placeholder="성별" name="gender" value="">

                    <div style="text-align: center;">
                        <button type="submit" class="btn" style="background-color: #666;">등록</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.getElementById("userfile").onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
        // get loaded data and render thumbnail.
        document.getElementById("image").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
};


</script>

