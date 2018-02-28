<h1>Novo Produto</h1>



<div class="row-fluid">
  <div class="span12">
    <div class="widget-box">
      <div class="widget-title">
        <span class="icon"> <i class="icon icon-tasks"></i> </span>
        <h5>Produto</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . "Produto/salvaNovo"; ?>">
          <div class="control-group">
            <label class="control-label">Descrição</label>
            <div class="controls">
              <input class="validate-required span10" type="text" name="proDescricao" id="proDescricao" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Código</label>
            <div class="controls">
              <input class="span10" type="text" name="proCodigo" id="proCodigo" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">EAN (Código de Barra)</label>
            <div class="controls">
              <input class="span10" type="text" name="proEan" id="proEan" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Estoque (Und)</label>
            <div class="controls">
              <input class="validate-required span10" type="text" name="proEstoque" id="proEstoque" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Preço Custo</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on">R$</span>
                <input class="validate-required span10 txt_moeda_jqformat" type="text" name="proPrecCusto" id="proPrecCusto" />
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Preço Venda</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on">R$</span>
                <input class="validate-required span10 txt_moeda_jqformat" type="text" name="proPrecVenda" id="proPrecVenda" />
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Observação</label>
            <div class="controls">
              <textarea class="span10" name="proObservacao" id="proObservacao"></textarea>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Ativo</label>
            <div class="controls">
              <select class="" name="proObservacao" id="proObservacao">
                <option value="1">Sim</option>
                <option value="0">Não</option>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" value="Gravar Produto" class="btn btn-success" />
            <a href="<?php echo base_url() . "Produto/index"; ?>" class="btn">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
