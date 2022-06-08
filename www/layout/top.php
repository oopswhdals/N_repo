<?php 
    include_once rootDir."layout/menu.php";
    if($_SESSION['M_ID'] =="admin"){
      $is_admin =true;
    }

?>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;" id ="navbar-text">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <div class="dropdown-item" ><?=$_SESSION['M_ID']?></div>
                  <div class="dropdown-item" ><?=$_SESSION['M_NAME']?></div>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?=rootDir?>_proc.php?mode=logout">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    <script>
    if(location.pathname.split('/')[2]!=null){
    	$('.nav-link').each(function(){
    		if(this.href.split('/')[4] == location.pathname.split('/')[2]){
    			document.getElementById('navbar-text').innerHTML = this.childNodes[3].innerText;
    		}
    	});
    }

    $( function() {
        $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    } );
    
    </script>
      <!-- End Navbar -->
      <div class="content">