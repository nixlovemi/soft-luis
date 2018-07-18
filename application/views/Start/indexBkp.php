<h1>Backup do Sistema</h1>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-save"></i></span>
    <h5>Backup</h5>
  </div>
  <div class="widget-content">
    <p>O backup é realizado através da reunião dos dados lançados no banco de dados e é enviado por email ao servidor de backup.</p>
    <p>Para efetuar o backup, esteja conectado na internet.</p>

    <center>
      <a id="btnIniciarBackup" class="btn btn-info btn-large" href="javascript:;">INICIAR BACKUP</a>
    </center>

    <div id="dvRetIniciarBackup">
    </div>
  </div>
</div>
