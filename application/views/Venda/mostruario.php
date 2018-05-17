<h1>Mostruários</h1>

<a class="btn btn-info btn-large" href="javascript:;" id="btnNovoMostruario">NOVO MOSTRUÁRIO</a>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-book"></i></span>
    <h5>Lista de mostruários</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlMostruarioTable;
    ?>
  </div>
</div>
