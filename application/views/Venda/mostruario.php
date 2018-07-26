<h1>Mostruários</h1>

<a class="btn btn-info btn-large" href="javascript:;" id="btnNovoMostruario">NOVO MOSTRUÁRIO</a>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
if(isset($okMsg) && $okMsg != ""){
  echo $okMsg;
}
?>
<div class="widget-box">
  <div class="widget-title">
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#tab1">Ativos</a>
      </li>
      <li>
        <a data-toggle="tab" href="#tab2">Finalizados</a>
      </li>
    </ul>
  </div>
  <div class="widget-content tab-content">
    <div id="tab1" class="tab-pane active">
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
    </div>
    <div id="tab2" class="tab-pane">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"><i class="icon icon-book"></i></span>
          <h5>Lista de mostruários finalizados</h5>
        </div>
        <div class="widget-content nopadding">
          <?php
          echo $htmlMostruarioTableFin;
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
