<!-- https://wrappixel.com/demos/free-admin-templates/matrix-admin/index.html -->
<!DOCTYPE html>
<html lang="en">
   <head>
      <script>
      var HOME_URL = '<?php echo base_url(); ?>';
      </script>

      <title><?= PORTAL_NAME ?? 'Nome da Loja' ?> - <?php echo base_url(); ?></title>
      <meta charset="UTF-8" />
      <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>img/favicon.ico" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-responsive.min.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/fullcalendar.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/matrix-style.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/matrix-media.css" />
      <link href="<?php echo base_url(); ?>font-awesome/css/font-awesome.css" rel="stylesheet" />
      <link href="<?php echo base_url(); ?>js/Dynatable/jquery.dynatable.css" rel="stylesheet" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.gritter.css" />
      <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
      <!--Header-part-->
      <div id="header">
         <h1 style="background: none; left: auto; line-height: normal; width: 221px; text-align: center">
            <a style="color: white;" href="<?php echo base_url() . "Start"; ?>"><?= PORTAL_NAME ?? 'Nome da Loja' ?></a>
         </h1>
      </div>
      <!--close-Header-part-->
      <!--top-Header-menu-->
      <div id="user-nav" class="navbar navbar-inverse">
         <ul class="nav">
            <li  class="dropdown" id="profile-messages" >
               <a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Olá <?php echo $username; ?></span><b class="caret"></b></a>
               <ul class="dropdown-menu">
                  <li><a href="<?php echo base_url() . 'Login/logout' ?>"><i class="icon-key"></i> Sair</a></li>
               </ul>
            </li>

            <li class=""><a title="" href="<?php echo base_url() . 'Login/logout' ?>"><i class="icon icon-share-alt"></i> <span class="text">Sair</span></a></li>
         </ul>
      </div>
      <!--close-top-Header-menu-->

      <!--sidebar-menu-->
      <div id="sidebar">
         <a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>

         <?php
         // SO DOIS LEVELS PROGRAMADOS
         $vArrMenu = (isset($arrMenu)) ? $arrMenu: array();
         $baseUrl  = base_url();

         echo "<ul>";

         foreach($vArrMenu as $menuLvl1){
           $ml1Descricao  = $menuLvl1["descricao"];
           $ml1Controller = $menuLvl1["controller"];
           $ml1Action     = $menuLvl1["action"];
           $ml1Icon       = $menuLvl1["icon"];
           $ml1ArrChild   = $menuLvl1["child"];
           $isActive      = ($ml1Controller == $controller && $ml1Action == $action) ? " active ": "";
           $linkUrl       = $baseUrl . $ml1Controller . "/" . $ml1Action;

           if(count($ml1ArrChild) > 0){
             $isActive = "submenu";
             $linkUrl  = "javascript:;";
           }

           echo "<li class='$isActive'>";
           echo "  <a href='$linkUrl'>";
           echo "    $ml1Icon";
           echo "    <span>$ml1Descricao</span>";
           echo "  </a>";

           if(count($ml1ArrChild) > 0){
             echo "<ul>";

             foreach($ml1ArrChild as $menuLvl2){
               $ml2Descricao  = $menuLvl2["descricao"];
               $ml2Controller = $menuLvl2["controller"];
               $ml2Action     = $menuLvl2["action"];
               $ml2Icon       = $menuLvl2["icon"];
               $ml2ArrChild   = $menuLvl2["child"];
               $linkUrl       = $baseUrl . $ml2Controller . "/" . $ml2Action;

               echo "<li>";
               echo "  <a href='$linkUrl'>$ml2Descricao</a>";
               echo "</li>";
             }

             echo "</ul>";
           }
           echo "</li>";
         }

         echo "</ul>";
         ?>
      </div>
      <!--sidebar-menu-->
      <!--main-container-part-->
      <div id="content">
         <!--breadcrumbs-->
         <div id="content-header">
            <div id="breadcrumb"><a href="<?php echo base_url() . "Start"; ?>"><i class="icon-home"></i> Home</a></div>
         </div>
         <!--End-breadcrumbs-->
         <!--Action boxes-->
         <div class="container-fluid">
           <?= $contents ?>

            
         </div>
      </div>
      <!--end-main-container-part-->
      <!--Footer-part-->
      <div class="row-fluid">
         <div id="footer" class="span12"> 2018 &copy; App Jóias - Versão 0.1.0</div>
      </div>
      <!--end-Footer-part-->
      <script src="<?php echo base_url(); ?>js/excanvas.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.ui.custom.js"></script>
      <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
      <script src="<?php echo base_url(); ?>js/Dynatable/jquery.dynatable.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery-maskmoney.v3.1.1.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.flot.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.flot.resize.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.peity.min.js"></script>
      <script src="<?php echo base_url(); ?>js/fullcalendar.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.gritter.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.wizard.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.uniform.js"></script>
      <script src="<?php echo base_url(); ?>js/select2.min.js"></script>
      <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>
      <script src="<?php echo base_url(); ?>js/bootbox.min.js"></script>
      <script src="<?php echo base_url(); ?>js/masked.js"></script>
      <script src="<?php echo base_url(); ?>js/numeric.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.dashboard.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.interface.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.chat.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.form_validation.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.form_common.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.popover.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.tables.js"></script>
      <script type="text/javascript">
         // This function is called from the pop-up menus to transfer to
         // a different page. Ignore if the value returned is a null string:
         function goPage (newURL) {

             // if url is empty, skip the menu dividers and reset the menu selection to default
             if (newURL != "") {

                 // if url is "-", it is this page -- reset the menu:
                 if (newURL == "-" ) {
                     resetMenu();
                 }
                 // else, send page to designated URL
                 else {
                   document.location.href = newURL;
                 }
             }
         }

         // resets the menu selection upon entry to this page:
         function resetMenu() {
          document.gomenu.selector.selectedIndex = 2;
         }
      </script>

      <?php
      // essa variavel vem do Start/index
      $scriptPath = (isset($scriptPath)) ? $scriptPath: "";
      if( file_exists($scriptPath) ){
        ?>
        <script>
        fncTelaUpdateBd('<?php echo $scriptPath; ?>');
        </script>
        <?php
      }
      ?>
   </body>
</html>
