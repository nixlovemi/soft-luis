<?php
class Tb_Venda_Itens extends CI_Model {
  public function getHtmlList($vdaId){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Produto</th>";
    $htmlTable .= "      <th width='8%'>Quantidade</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Desconto</th>";
    $htmlTable .= "      <th width='10%'>Total</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vdi_id, pro_id, pro_descricao, vdi_qtde, vdi_valor, vdi_desconto, vdi_total ";
    $vSql .= " FROM tb_venda_itens ";
    $vSql .= " LEFT JOIN tb_produto ON pro_id = vdi_pro_id ";
    $vSql .= " WHERE vdi_vda_id = $vdaId ";
    $vSql .= " ORDER BY pro_descricao ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    if(count($arrRs) <= 0){
      /*$htmlTable .= "  <tr class=''>";
      $htmlTable .= "    <td colspan='8'>";
      $htmlTable .= "      <center>Nenhum resultado encontrado!</center>";
      $htmlTable .= "    </td>";
      $htmlTable .= "  </tr>";*/
    } else {
      foreach($arrRs as $rs1){
        $vVdiId        = $rs1["vdi_id"];
        $vProId        = $rs1["pro_id"];
        $vProDescricao = $rs1["pro_descricao"];
        $vVdiQtde      = $rs1["vdi_qtde"];
        $vVdiValor     = "R$" . number_format($rs1["vdi_valor"], 2, ",", ".");
        $vVdiDesconto  = "R$" . number_format($rs1["vdi_desconto"], 2, ",", ".");
        $vVdiTotal     = "R$" . number_format($rs1["vdi_total"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vProId</td>";
        $htmlTable .= "  <td>$vProDescricao</td>";
        $htmlTable .= "  <td>$vVdiQtde</td>";
        $htmlTable .= "  <td>$vVdiValor</td>";
        $htmlTable .= "  <td>$vVdiDesconto</td>";
        $htmlTable .= "  <td>$vVdiTotal</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbVendaItem_deletar' data-id='$vVdiId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  private function getHtmlBlocoTotais($label, $value, $colorClass="bg_lb"){
    $vLabel = mb_strtoupper($label);

    return "<ul class='quick-actions'>
              <li class='$colorClass' style='width: 100%;'>
                <a href='index.html'>
                  <i class='icon icon-tasks'></i>
                  <span style='font-size: 18px;' class=''>$value</span>
                  <br />$vLabel
                </a>
              </li>
            </ul>";
  }

  public function getHtmlTotVenda($vdaId){
    $this->load->database();

    $vTotProdutos = "0";
    $vVlrProdutos = "R$ 0,00";
    $vTotDesconto = "R$ 0,00";
    $vTotVenda    = "R$ 0,00";

    $vSql  = " SELECT vda_id, vda_tot_itens, vda_vlr_itens, SUM(vdi_qtde * vdi_valor) AS vlr_produtos, SUM(vdi_desconto) AS vlr_desconto ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " INNER JOIN tb_venda_itens ON vdi_vda_id = vda_id ";
    $vSql .= " WHERE vda_id = $vdaId ";
    $vSql .= " GROUP BY vda_id ";

    $query = $this->db->query($vSql);
    $rs    = $query->row();

    if($rs){
      $vTotProdutos = isset($rs->vda_tot_itens) ? $rs->vda_tot_itens: "0";
      $vVlrProdutos = isset($rs->vlr_produtos) ? "R$ " . number_format($rs->vlr_produtos, 2, ",", "."): "R$ 0,00";
      $vTotDesconto = isset($rs->vlr_desconto) ? "R$ " . number_format($rs->vlr_desconto, 2, ",", "."): "R$ 0,00";
      $vTotVenda    = isset($rs->vda_vlr_itens) ? "R$ " . number_format($rs->vda_vlr_itens, 2, ",", "."): "R$ 0,00";
    }

    $htmlProdutos    = $this->getHtmlBlocoTotais("PRODUTOS", $vTotProdutos);
    $htmlTotProdutos = $this->getHtmlBlocoTotais("TOTAL PRODUTOS", $vVlrProdutos);
    $htmlTotDesconto = $this->getHtmlBlocoTotais("TOTAL DESCONTO", $vTotDesconto);
    $htmlTotVenda    = $this->getHtmlBlocoTotais("TOTAL VENDA", $vTotVenda, "bg_lg");

    $htmlTable = "<div class='control-group' style='width: 100%; display: block; overflow: hidden;'>
                    <div class='span3 m-wrap'>
                      $htmlProdutos
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotProdutos
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotDesconto
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotVenda
                    </div>
                  </div>";
    return $htmlTable;
  }

  public function getVendaItem($vdiId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrVendaItemDados"]  = array();

    if(!is_numeric($vdiId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o item da venda!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vdi_id, vdi_vda_id, vdi_pro_id, vdi_qtde, vdi_valor, vdi_desconto, vdi_total, vdi_status");
    $this->db->from("tb_venda_itens");
    $this->db->where("vdi_id", $vdiId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrVendaItemDados = [];
      $arrVendaItemDados["vdi_id"]       = $row->vdi_id;
      $arrVendaItemDados["vdi_vda_id"]   = $row->vdi_vda_id;
      $arrVendaItemDados["vdi_pro_id"]   = $row->vdi_pro_id;
      $arrVendaItemDados["vdi_qtde"]     = $row->vdi_qtde;
      $arrVendaItemDados["vdi_valor"]    = $row->vdi_valor;
      $arrVendaItemDados["vdi_desconto"] = $row->vdi_desconto;
      $arrVendaItemDados["vdi_total"]    = $row->vdi_total;
      $arrVendaItemDados["vdi_status"]   = $row->vdi_status;

      $arrRet["arrVendaItemDados"] = $arrVendaItemDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendaItens){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVdiVdaId = (isset($arrVendaItens["vdi_vda_id"])) ? $arrVendaItens["vdi_vda_id"]: null;
    if( !is_numeric($vVdiVdaId) && !$vVdiVdaId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Venda!";
      return $arrRet;
    }

    $vVdiProId = (isset($arrVendaItens["vdi_pro_id"])) ? $arrVendaItens["vdi_pro_id"]: null;
    if( !is_numeric($vVdiProId) && !$vVdiProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVdiQtde = (isset($arrVendaItens["vdi_qtde"])) ? $arrVendaItens["vdi_qtde"]: null;
    if( !is_numeric($vVdiQtde) && !$vVdiQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVdiValor = (isset($arrVendaItens["vdi_valor"])) ? $arrVendaItens["vdi_valor"]: null;
    if( !is_numeric($vVdiValor) && !$vVdiValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaItens){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaItens);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vdaStatusInc = 1;
    $vVdiVdaId    = isset($arrVendaItens["vdi_vda_id"]) ? $arrVendaItens["vdi_vda_id"]: null;
    $vVdiProId    = isset($arrVendaItens["vdi_pro_id"]) ? $arrVendaItens["vdi_pro_id"]: null;
    $vVdiQtde     = isset($arrVendaItens["vdi_qtde"]) ? $arrVendaItens["vdi_qtde"]: null;
    $vVdiValor    = isset($arrVendaItens["vdi_valor"]) ? $arrVendaItens["vdi_valor"]: null;
    $vVdiDesconto = isset($arrVendaItens["vdi_desconto"]) ? $arrVendaItens["vdi_desconto"]: 0;

    $data = array(
      'vdi_vda_id' => $vVdiVdaId,
      'vdi_pro_id' => $vVdiProId,
      'vdi_qtde' => $vVdiQtde,
      'vdi_valor' => $vVdiValor,
      'vdi_desconto' => $vVdiDesconto,
      'vdi_status' => $vdaStatusInc,
    );

    $retInsert = $this->db->insert('tb_venda_itens', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $vdi_id = $this->db->insert_id();

      $arrRet["vdi_id"] = $vdi_id;
      $arrRet["erro"]   = false;
      $arrRet["msg"]    = "Produto inserido com sucesso!";
    }

    return $arrRet;
  }

  public function delete($vdiId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($vdiId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->where('vdi_id', $vdiId);
      $retDelete = $this->db->delete('tb_venda_itens');

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Produto removido com sucesso!";
      }

      return $arrRet;
    }
  }
}