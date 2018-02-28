<h1>Produtos</h1>

<a class="btn btn-info btn-large" href="<?php echo base_url() . "Produto/novoProduto"; ?>">NOVO PRODUTO</a>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
    <h5>Lista de produtos</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlProdTable;
    ?>
  </div>
</div>
