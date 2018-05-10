<?php
class Tb_Produto extends CI_Model {
  private function geraEan8($proId){
    return "35" . str_pad($proId, 6, "0", STR_PAD_LEFT);
  }

  private function ean8ToProId($ean8){
    return (int) substr($ean8, -6);
  }

  public function getProduto($proId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrProdutoDados"]  = array();

    if(!is_numeric($proId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o produto!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("pro_id, pro_descricao, pro_codigo, pro_ean, pro_estoque, pro_prec_custo, pro_prec_venda, pro_observacao, pro_ativo");
    $this->db->from("tb_produto");
    $this->db->where("pro_id", $proId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrProdutoDados = [];
      $arrProdutoDados["pro_id"]         = $row->pro_id;
      $arrProdutoDados["pro_descricao"]  = $row->pro_descricao;
      $arrProdutoDados["pro_codigo"]     = $row->pro_codigo;
      $arrProdutoDados["pro_ean"]        = $row->pro_ean;
      $arrProdutoDados["pro_estoque"]    = $row->pro_estoque;
      $arrProdutoDados["pro_prec_custo"] = $row->pro_prec_custo;
      $arrProdutoDados["pro_prec_venda"] = $row->pro_prec_venda;
      $arrProdutoDados["pro_observacao"] = $row->pro_observacao;
      $arrProdutoDados["pro_ativo"]      = $row->pro_ativo;
      $arrProdutoDados["ean8"]           = $this->geraEan8( $row->pro_id );

      $arrRet["arrProdutoDados"] = $arrProdutoDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getProdutoByEan($ean8){
    $proId = $this->ean8ToProId($ean8);
    return $this->getProduto($proId);
  }

  public function getProdutos(){
    $arrRet                = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["arrProdutos"] = array();

    $this->load->database();
    $this->db->select("pro_id, pro_descricao, pro_codigo, pro_ean, pro_estoque, pro_prec_custo, pro_prec_venda, pro_observacao, pro_ativo");
    $this->db->from("tb_produto");
    $this->db->where("pro_ativo", 1);
    $this->db->order_by("pro_descricao", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrProdutosDados = [];
        $arrProdutosDados["pro_id"]         = $rs1["pro_id"];
        $arrProdutosDados["pro_descricao"]  = $rs1["pro_descricao"];
        $arrProdutosDados["pro_codigo"]     = $rs1["pro_codigo"];
        $arrProdutosDados["pro_ean"]        = $rs1["pro_ean"];
        $arrProdutosDados["pro_estoque"]    = $rs1["pro_estoque"];
        $arrProdutosDados["pro_prec_custo"] = $rs1["pro_prec_custo"];
        $arrProdutosDados["pro_prec_venda"] = $rs1["pro_prec_venda"];
        $arrProdutosDados["pro_observacao"] = $rs1["pro_observacao"];
        $arrProdutosDados["pro_ativo"]      = $rs1["pro_ativo"];

        $arrRet["arrProdutos"][] = $arrProdutosDados;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getHtmlList(){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Código</th>";
    $htmlTable .= "      <th>Descrição</th>";
    $htmlTable .= "      <th width='8%'>Estoque</th>";
    $htmlTable .= "      <th width='10%'>Preço Venda</th>";
    $htmlTable .= "      <th width='8%'>Etiqueta</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Editar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT pro_id, pro_codigo, pro_descricao, pro_estoque, pro_prec_venda ";
    $vSql .= " FROM tb_produto ";
    $vSql .= " WHERE pro_ativo = true ";
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
        $vProId        = $rs1["pro_id"];
        $vProCodigo    = $rs1["pro_codigo"];
        $vProDescricao = $rs1["pro_descricao"];
        $vProEstoque   = $rs1["pro_estoque"];
        $vProPrecVenda = "R$" . number_format($rs1["pro_prec_venda"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vProId</td>";
        $htmlTable .= "  <td>$vProCodigo</td>";
        $htmlTable .= "  <td>$vProDescricao</td>";
        $htmlTable .= "  <td>$vProEstoque</td>";
        $htmlTable .= "  <td>$vProPrecVenda</td>";
        $htmlTable .= "  <td><a target='_blank' href='".base_url() . "Produto/geraEtiqueta/$vProId" . "' class=''><i class='icon-print icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Produto/ver/$vProId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Produto/editar/$vProId" . "'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbProduto_deletar' data-id='$vProId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  private function validaInsert($arrProdutoDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vProDescricao = (isset($arrProdutoDados["pro_descricao"])) ? $arrProdutoDados["pro_descricao"]: "";
    if( strlen($vProDescricao) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a descrição!";
      return $arrRet;
    }

    // descricao duplicada
    $this->load->database();
    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_descricao", $vProDescricao);
    $this->db->where("pro_descricao IS NOT NULL");
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com essa descrição (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    // codigo duplicado ==
    $vProCodigo = (isset($arrProdutoDados["pro_codigo"])) ? $arrProdutoDados["pro_codigo"]: "";

    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_codigo", $vProCodigo);
    $this->db->where("pro_codigo IS NOT NULL");
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com esse código (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    // ean duplicado =====
    $vProEan = (isset($arrProdutoDados["pro_ean"])) ? $arrProdutoDados["pro_ean"]: "";

    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_ean", $vProEan);
    $this->db->where("pro_ean IS NOT NULL");
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com esse EAN (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    $vProEstoque = (isset($arrProdutoDados["pro_estoque"])) ? $arrProdutoDados["pro_estoque"]: "";
    if( !is_numeric($vProEstoque) || $vProEstoque < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o estoque!";
      return $arrRet;
    }

    $vPrecCusto = (isset($arrProdutoDados["pro_prec_custo"])) ? $arrProdutoDados["pro_prec_custo"]: "";
    if( !is_numeric($vPrecCusto) || $vPrecCusto < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o preço de custo!";
      return $arrRet;
    }

    $vPrecVenda = (isset($arrProdutoDados["pro_prec_venda"])) ? $arrProdutoDados["pro_prec_venda"]: "";
    if( !is_numeric($vPrecVenda) || $vPrecVenda < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o preço de venda!";
      return $arrRet;
    }

    $vAtivo = (isset($arrProdutoDados["pro_ativo"])) ? $arrProdutoDados["pro_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o produto está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrProdutoDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrProdutoDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vProDescricao  = isset($arrProdutoDados["pro_descricao"]) ? $arrProdutoDados["pro_descricao"]: "";
    $vProCodigo     = isset($arrProdutoDados["pro_codigo"]) ? $arrProdutoDados["pro_codigo"]: "null";
    $vProEan        = isset($arrProdutoDados["pro_ean"]) ? $arrProdutoDados["pro_ean"]: "null";
    $vProEstoque    = isset($arrProdutoDados["pro_estoque"]) ? $arrProdutoDados["pro_estoque"]: 0;
    $vProPrecCusto  = isset($arrProdutoDados["pro_prec_custo"]) ? $arrProdutoDados["pro_prec_custo"]: 0.01;
    $vProPrecVenda  = isset($arrProdutoDados["pro_prec_venda"]) ? $arrProdutoDados["pro_prec_venda"]: 0.01;
    $vProObservacao = isset($arrProdutoDados["pro_observacao"]) ? $arrProdutoDados["pro_observacao"]: "null";
    $vProAtivo      = isset($arrProdutoDados["pro_ativo"]) ? $arrProdutoDados["pro_ativo"]: "true";

    $data = array(
      'pro_descricao'  => $vProDescricao,
      'pro_codigo'     => $vProCodigo,
      'pro_ean'        => $vProEan,
      'pro_estoque'    => $vProEstoque,
      'pro_prec_custo' => $vProPrecCusto,
      'pro_prec_venda' => $vProPrecVenda,
      'pro_observacao' => $vProObservacao,
      'pro_ativo'      => $vProAtivo,
    );

    $retInsert = $this->db->insert('tb_produto', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Produto inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrProdutoDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vProId = (isset($arrProdutoDados["pro_id"])) ? $arrProdutoDados["pro_id"]: "";
    if(!$vProId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível editar o Produto! ID inválido!";
      return $arrRet;
    }

    $vProDescricao = (isset($arrProdutoDados["pro_descricao"])) ? $arrProdutoDados["pro_descricao"]: "";
    if( strlen($vProDescricao) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a descrição!";
      return $arrRet;
    }

    // descricao duplicada
    $this->load->database();
    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_descricao", $vProDescricao);
    $this->db->where("pro_descricao IS NOT NULL");
    $this->db->where("pro_id <> " . $vProId);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com essa descrição (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    // codigo duplicado ==
    $vProCodigo = (isset($arrProdutoDados["pro_codigo"])) ? $arrProdutoDados["pro_codigo"]: "";

    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_codigo", $vProCodigo);
    $this->db->where("pro_codigo IS NOT NULL");
    $this->db->where("pro_id <> " . $vProId);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com esse código (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    // ean duplicado =====
    $vProEan = (isset($arrProdutoDados["pro_ean"])) ? $arrProdutoDados["pro_ean"]: "";

    $this->db->select("pro_id");
    $this->db->from("tb_produto");
    $this->db->where("pro_ean", $vProEan);
    $this->db->where("pro_ean IS NOT NULL");
    $this->db->where("pro_id <> " . $vProId);
    $query = $this->db->get();
    if($query->num_rows() > 0){
      $row    = $query->row();
      $vProId = $row->pro_id;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Já existe um produto com esse EAN (ID $vProId)! Verifique!";
      return $arrRet;
    }
    $this->db->reset_query();
    // ===================

    $vProEstoque = (isset($arrProdutoDados["pro_estoque"])) ? $arrProdutoDados["pro_estoque"]: "";
    if( !is_numeric($vProEstoque) || $vProEstoque < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o estoque!";
      return $arrRet;
    }

    $vPrecCusto = (isset($arrProdutoDados["pro_prec_custo"])) ? $arrProdutoDados["pro_prec_custo"]: "";
    if( !is_numeric($vPrecCusto) || $vPrecCusto < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o preço de custo!";
      return $arrRet;
    }

    $vPrecVenda = (isset($arrProdutoDados["pro_prec_venda"])) ? $arrProdutoDados["pro_prec_venda"]: "";
    if( !is_numeric($vPrecVenda) || $vPrecVenda < 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o preço de venda!";
      return $arrRet;
    }

    $vAtivo = (isset($arrProdutoDados["pro_ativo"])) ? $arrProdutoDados["pro_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o produto está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrProdutoDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrProdutoDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vProId         = (isset($arrProdutoDados["pro_id"])) ? $arrProdutoDados["pro_id"]: "";
    $vProDescricao  = isset($arrProdutoDados["pro_descricao"]) ? $arrProdutoDados["pro_descricao"]: "";
    $vProCodigo     = isset($arrProdutoDados["pro_codigo"]) ? $arrProdutoDados["pro_codigo"]: "null";
    $vProEan        = isset($arrProdutoDados["pro_ean"]) ? $arrProdutoDados["pro_ean"]: "null";
    $vProEstoque    = isset($arrProdutoDados["pro_estoque"]) ? $arrProdutoDados["pro_estoque"]: 0;
    $vProPrecCusto  = isset($arrProdutoDados["pro_prec_custo"]) ? $arrProdutoDados["pro_prec_custo"]: 0.01;
    $vProPrecVenda  = isset($arrProdutoDados["pro_prec_venda"]) ? $arrProdutoDados["pro_prec_venda"]: 0.01;
    $vProObservacao = isset($arrProdutoDados["pro_observacao"]) ? $arrProdutoDados["pro_observacao"]: "null";
    $vProAtivo      = isset($arrProdutoDados["pro_ativo"]) ? $arrProdutoDados["pro_ativo"]: "true";

    $data = array(
      'pro_descricao'  => $vProDescricao,
      'pro_codigo'     => $vProCodigo,
      'pro_ean'        => $vProEan,
      'pro_estoque'    => $vProEstoque,
      'pro_prec_custo' => $vProPrecCusto,
      'pro_prec_venda' => $vProPrecVenda,
      'pro_observacao' => $vProObservacao,
      'pro_ativo'      => $vProAtivo,
    );

    $this->db->where('pro_id', $vProId);
    $retInsert = $this->db->update('tb_produto', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Produto editado com sucesso!";
    }

    return $arrRet;
  }

  public function addInventario($arrProdutos){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    if( count($arrProdutos) <= 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Nenhum produto informado para o inventário!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->trans_start();

      foreach($arrProdutos as $Produto){
        $proId      = $Produto["pro_id"];
        $proEstoque = $Produto["pro_estoque"];

        $sql = "UPDATE tb_produto SET pro_estoque = $proEstoque WHERE pro_id = $proId";
        $this->db->query($sql);
      }

      $this->db->trans_complete();
      if($this->db->trans_status() === FALSE){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Erro ao gravar Inventário!";
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"]  = "Inventário gravado com sucesso!";
      }

      return $arrRet;
    }
  }
}
