<?php
include_once rootDir . "layout/head.php";
?>

<body class="">
<div class="wrapper ">
    <div class="sidebar" data-color="danger" data-background-color="white">
        <!--
          Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

          Tip 2: you can also add an image using data-image tag
      -->
        <div class="logo gm_c"><a href="#" class="simple-text logo-normal">
                IICOMBINED EVENT
            </a></div>
        <div class="sidebar-wrapper">
            <?php if($_SESSION['M_ID']=='admin'){?>
                <!-- <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/haus/setting.php">
                        <i class="material-icons">dashboard</i>
                        <p>Event Setting</p>
                    </a>
                </li> -->
            <ul class="nav">
                <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>_proc.php?mode=logout">
                        <i class="material-icons">content_paste</i>
                        <p>로그아웃</p>
                    </a> 
                </li>
            </ul>
            <?php }?>
            <ul class="nav">
                 <!-- <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/haus">
                        <i class="material-icons">content_paste</i>
                        <p>haus 이벤트 당첨 확인</p>
                    </a>
                </li> 

                 <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/moncler">
                        <i class="material-icons">content_paste</i>
                        <p>moncler 이벤트 확인</p>
                    </a>
                </li> 

                 <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/tam_cocoon">
                        <i class="material-icons">content_paste</i>
                        <p>Tamburins cocoon 전시</p>
                    </a>
                </li>
                <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/tam_candle">
                        <i class="material-icons">content_paste</i>
                        <p>Tamburins candle 이벤트</p>
                    </a>
                </li> 
                <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/gm_jentlegarden">
                        <i class="material-icons">content_paste</i>
                        <p>jentlegarden 이벤트 확인</p>
                    </a>
                </li> -->
                <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/tam_daydream">
                        <i class="material-icons">content_paste</i>
                        <p>Tamburins daydream 이벤트</p>
                    </a>
                </li> 
                <li class="nav-item " id="board_menu">
                    <a class="nav-link" href="<?= rootDir ?>page/gm_looks/index.php?vmode=easy&search_id=All">
                        <i class="material-icons">content_paste</i>
                        <p>GM_looks</p>
                    </a>
                </li> 
                
                <hr/>
                
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        $('.nav-item').removeClass('active');
        var path_name = location.pathname.split('/')[2] + "_menu";
        $('#' + path_name).addClass('active');
    </script>
    <div class="main-panel">