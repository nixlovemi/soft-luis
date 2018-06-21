<?php
class Tb_Vendedor extends CI_Model {

  public function getHtmlList(){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Nome</th>";
    $htmlTable .= "      <th width='10%'>CPF</th>";
    $htmlTable .= "      <th width='12%'>Telefone</th>";
    $htmlTable .= "      <th>Cidade</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Editar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT ven_id, ven_nome, ven_cpf_cnpj, ven_tel_ddd, ven_tel_numero, ven_end_cidade, ven_end_estado ";
    $vSql .= " FROM tb_vendedor ";
    $vSql .= " WHERE ven_ativo = true ";
    $vSql .= " ORDER BY ven_nome ";

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
        $vVenId        = $rs1["ven_id"];
        $vVenNome      = $rs1["ven_nome"];
        $vVenCpfCnpj   = $rs1["ven_cpf_cnpj"];
        $vVenTelefone  = "(".$rs1["ven_tel_ddd"].") " . $rs1["ven_tel_numero"];
        $vVenCidade    = $rs1["ven_end_cidade"] . " (".$rs1["ven_end_estado"].")";

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVenId</td>";
        $htmlTable .= "  <td>$vVenNome</td>";
        $htmlTable .= "  <td>$vVenCpfCnpj</td>";
        $htmlTable .= "  <td>$vVenTelefone</td>";
        $htmlTable .= "  <td>$vVenCidade</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Vendedor/ver/$vVenId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Vendedor/editar/$vVenId" . "'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbVendedor_deletar' data-id='$vVenId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getVendedor($venId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrVendedorDados"]  = array();

    if(!is_numeric($venId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o vendedor!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("ven_id, ven_nome, ven_cpf_cnpj, ven_rg_ie, ven_tel_ddd, ven_tel_numero, ven_cel_ddd, ven_cel_numero, ven_end_cep, ven_end_tp_lgr, ven_end_logradouro, ven_end_numero, ven_end_bairro, ven_end_cidade, ven_end_estado, ven_observacao, ven_ativo, ven_comissao");
    $this->db->from("tb_vendedor");
    $this->db->where("ven_id", $venId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrVendedorDados = [];
      $arrVendedorDados["ven_id"]             = $row->ven_id;
      $arrVendedorDados["ven_nome"]           = $row->ven_nome;
      $arrVendedorDados["ven_cpf_cnpj"]       = $row->ven_cpf_cnpj;
      $arrVendedorDados["ven_rg_ie"]          = $row->ven_rg_ie;
      $arrVendedorDados["ven_tel_ddd"]        = $row->ven_tel_ddd;
      $arrVendedorDados["ven_tel_numero"]     = $row->ven_tel_numero;
      $arrVendedorDados["ven_cel_ddd"]        = $row->ven_cel_ddd;
      $arrVendedorDados["ven_cel_numero"]     = $row->ven_cel_numero;
      $arrVendedorDados["ven_end_cep"]        = $row->ven_end_cep;
      $arrVendedorDados["ven_end_tp_lgr"]     = $row->ven_end_tp_lgr;
      $arrVendedorDados["ven_end_logradouro"] = $row->ven_end_logradouro;
      $arrVendedorDados["ven_end_numero"]     = $row->ven_end_numero;
      $arrVendedorDados["ven_end_bairro"]     = $row->ven_end_bairro;
      $arrVendedorDados["ven_end_cidade"]     = $row->ven_end_cidade;
      $arrVendedorDados["ven_end_estado"]     = $row->ven_end_estado;
      $arrVendedorDados["ven_observacao"]     = $row->ven_observacao;
      $arrVendedorDados["ven_ativo"]          = $row->ven_ativo;
      $arrVendedorDados["ven_comissao"]       = $row->ven_comissao;

      $arrRet["arrVendedorDados"] = $arrVendedorDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getVendedores(){
    $arrRet                = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["arrVendedores"] = array();

    $this->load->database();
    $this->db->select("ven_id, ven_nome, ven_cpf_cnpj, ven_comissao");
    $this->db->from("tb_vendedor");
    $this->db->where("ven_ativo", 1);
    $this->db->order_by("ven_nome", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrVendedorDados = [];
        $arrVendedorDados["ven_id"]       = $rs1["ven_id"];
        $arrVendedorDados["ven_nome"]     = $rs1["ven_nome"];
        $arrVendedorDados["ven_cpf_cnpj"] = $rs1["ven_cpf_cnpj"];
        $arrVendedorDados["ven_comissao"] = $rs1["ven_comissao"];

        $arrRet["arrVendedores"][] = $arrVendedorDados;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendedorDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVenNome = (isset($arrVendedorDados["ven_nome"])) ? $arrVendedorDados["ven_nome"]: "";
    if( strlen($vVenNome) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o nome!";
      return $arrRet;
    }

    // cpf duplicado
    $vVenCpfCnpj = isset($arrVendedorDados["ven_cpf_cnpj"]) ? $arrVendedorDados["ven_cpf_cnpj"]: "";

    if($vVenCpfCnpj != ""){
      $this->load->helper('utils');
      $validCPF = is_cpf($vVenCpfCnpj);
      if(!$validCPF){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "CPF inválido!";
        return $arrRet;
      }

      $this->load->database();
      $this->db->select("ven_id");
      $this->db->from("tb_vendedor");
      $this->db->where("ven_cpf_cnpj", $vVenCpfCnpj);
      $this->db->where("ven_cpf_cnpj IS NOT NULL");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vVenId = $row->ven_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um vendedor com esse CPF/CNPJ (ID $vVenId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    // rg duplicado
    $vVenRgIe = isset($arrVendedorDados["ven_rg_ie"]) ? $arrVendedorDados["ven_rg_ie"]: "";

    if($vVenRgIe != ""){
      $this->load->database();
      $this->db->select("ven_id");
      $this->db->from("tb_vendedor");
      $this->db->where("ven_rg_ie", $vVenRgIe);
      $this->db->where("ven_rg_ie IS NOT NULL");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vVenId = $row->ven_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um vendedor com esse RG/IE (ID $vVenId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    $vAtivo = (isset($arrVendedorDados["ven_ativo"])) ? $arrVendedorDados["ven_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o vendedor está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendedorDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrVendedorDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vVenNome          = isset($arrVendedorDados["ven_nome"]) ? $arrVendedorDados["ven_nome"]: "null";
    $vVenCpfCnpj       = isset($arrVendedorDados["ven_cpf_cnpj"]) ? $arrVendedorDados["ven_cpf_cnpj"]: "null";
    $vVenRgIe          = isset($arrVendedorDados["ven_rg_ie"]) ? $arrVendedorDados["ven_rg_ie"]: "null";
    $vVenTelDdd        = isset($arrVendedorDados["ven_tel_ddd"]) ? $arrVendedorDados["ven_tel_ddd"]: "null";
    $vVenTelNumero     = isset($arrVendedorDados["ven_tel_numero"]) ? $arrVendedorDados["ven_tel_numero"]: "null";
    $vVenCelDdd        = isset($arrVendedorDados["ven_cel_ddd"]) ? $arrVendedorDados["ven_cel_ddd"]: "null";
    $vVenCelNumero     = isset($arrVendedorDados["ven_cel_numero"]) ? $arrVendedorDados["ven_cel_numero"]: "null";
    $vVenEndCep        = isset($arrVendedorDados["ven_end_cep"]) ? $arrVendedorDados["ven_end_cep"]: "null";
    $vVenEndTpLgr      = isset($arrVendedorDados["ven_end_tp_lgr"]) ? $arrVendedorDados["ven_end_tp_lgr"]: "null";
    $vVenEndLogradouro = isset($arrVendedorDados["ven_end_logradouro"]) ? $arrVendedorDados["ven_end_logradouro"]: "null";
    $vVenEndNumero     = isset($arrVendedorDados["ven_end_numero"]) ? $arrVendedorDados["ven_end_numero"]: "null";
    $vVenEndBairro     = isset($arrVendedorDados["ven_end_bairro"]) ? $arrVendedorDados["ven_end_bairro"]: "null";
    $vVenEndCidade     = isset($arrVendedorDados["ven_end_cidade"]) ? $arrVendedorDados["ven_end_cidade"]: "null";
    $vVenEndEstado     = isset($arrVendedorDados["ven_end_estado"]) ? $arrVendedorDados["ven_end_estado"]: "null";
    $vVenObs           = isset($arrVendedorDados["ven_observacao"]) ? $arrVendedorDados["ven_observacao"]: "null";
    $vVenAtivo         = isset($arrVendedorDados["ven_ativo"]) ? $arrVendedorDados["ven_ativo"]: "null";
    $vVenComissao      = isset($arrVendedorDados["ven_comissao"]) ? $arrVendedorDados["ven_comissao"]: 0;

    $data = array(
      'ven_nome'           => $vVenNome,
      'ven_cpf_cnpj'       => $vVenCpfCnpj,
      'ven_rg_ie'          => $vVenRgIe,
      'ven_tel_ddd'        => $vVenTelDdd,
      'ven_tel_numero'     => $vVenTelNumero,
      'ven_cel_ddd'        => $vVenCelDdd,
      'ven_cel_numero'     => $vVenCelNumero,
      'ven_end_cep'        => $vVenEndCep,
      'ven_end_tp_lgr'     => $vVenEndTpLgr,
      'ven_end_logradouro' => $vVenEndLogradouro,
      'ven_end_numero'     => $vVenEndNumero,
      'ven_end_bairro'     => $vVenEndBairro,
      'ven_end_cidade'     => $vVenEndCidade,
      'ven_end_estado'     => $vVenEndEstado,
      'ven_observacao'     => $vVenObs,
      'ven_ativo'          => $vVenAtivo,
      'ven_comissao'       => $vVenComissao,
    );

    $retInsert = $this->db->insert('tb_vendedor', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Vendedor inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrVendedorDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVenId = (isset($arrVendedorDados["ven_id"])) ? $arrVendedorDados["ven_id"]: "";
    if(!$vVenId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível editar! ID do Vendedor não informado!";
      return $arrRet;
    }

    $vVenNome = (isset($arrVendedorDados["ven_nome"])) ? $arrVendedorDados["ven_nome"]: "";
    if( strlen($vVenNome) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o nome!";
      return $arrRet;
    }

    // cpf duplicado
    $vVenCpfCnpj = isset($arrVendedorDados["ven_cpf_cnpj"]) ? $arrVendedorDados["ven_cpf_cnpj"]: "";

    if($vVenCpfCnpj != ""){
      $this->load->helper('utils');
      $validCPF = is_cpf($vVenCpfCnpj);
      if(!$validCPF){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "CPF inválido!";
        return $arrRet;
      }

      $this->load->database();
      $this->db->select("ven_id");
      $this->db->from("tb_vendedor");
      $this->db->where("ven_cpf_cnpj", $vVenCpfCnpj);
      $this->db->where("ven_cpf_cnpj IS NOT NULL");
      $this->db->where("ven_id <> $vVenId");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vVenId = $row->ven_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um vendedor com esse CPF/CNPJ (ID $vVenId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    // rg duplicado
    $vVenRgIe = isset($arrVendedorDados["ven_rg_ie"]) ? $arrVendedorDados["ven_rg_ie"]: "";

    if($vVenRgIe != ""){
      $this->load->database();
      $this->db->select("ven_id");
      $this->db->from("tb_vendedor");
      $this->db->where("ven_rg_ie", $vVenRgIe);
      $this->db->where("ven_rg_ie IS NOT NULL");
      $this->db->where("ven_id <> $vVenId");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vVenId = $row->ven_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um vendedor com esse RG/IE (ID $vVenId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    $vAtivo = (isset($arrVendedorDados["ven_ativo"])) ? $arrVendedorDados["ven_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o vendedor está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrVendedorDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrVendedorDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vVenId            = isset($arrVendedorDados["ven_id"]) ? $arrVendedorDados["ven_id"]: "";
    $vVenNome          = isset($arrVendedorDados["ven_nome"]) ? $arrVendedorDados["ven_nome"]: "null";
    $vVenCpfCnpj       = isset($arrVendedorDados["ven_cpf_cnpj"]) ? $arrVendedorDados["ven_cpf_cnpj"]: "null";
    $vVenRgIe          = isset($arrVendedorDados["ven_rg_ie"]) ? $arrVendedorDados["ven_rg_ie"]: "null";
    $vVenTelDdd        = isset($arrVendedorDados["ven_tel_ddd"]) ? $arrVendedorDados["ven_tel_ddd"]: "null";
    $vVenTelNumero     = isset($arrVendedorDados["ven_tel_numero"]) ? $arrVendedorDados["ven_tel_numero"]: "null";
    $vVenCelDdd        = isset($arrVendedorDados["ven_cel_ddd"]) ? $arrVendedorDados["ven_cel_ddd"]: "null";
    $vVenCelNumero     = isset($arrVendedorDados["ven_cel_numero"]) ? $arrVendedorDados["ven_cel_numero"]: "null";
    $vVenEndCep        = isset($arrVendedorDados["ven_end_cep"]) ? $arrVendedorDados["ven_end_cep"]: "null";
    $vVenEndTpLgr      = isset($arrVendedorDados["ven_end_tp_lgr"]) ? $arrVendedorDados["ven_end_tp_lgr"]: "null";
    $vVenEndLogradouro = isset($arrVendedorDados["ven_end_logradouro"]) ? $arrVendedorDados["ven_end_logradouro"]: "null";
    $vVenEndNumero     = isset($arrVendedorDados["ven_end_numero"]) ? $arrVendedorDados["ven_end_numero"]: "null";
    $vVenEndBairro     = isset($arrVendedorDados["ven_end_bairro"]) ? $arrVendedorDados["ven_end_bairro"]: "null";
    $vVenEndCidade     = isset($arrVendedorDados["ven_end_cidade"]) ? $arrVendedorDados["ven_end_cidade"]: "null";
    $vVenEndEstado     = isset($arrVendedorDados["ven_end_estado"]) ? $arrVendedorDados["ven_end_estado"]: "null";
    $vVenObs           = isset($arrVendedorDados["ven_observacao"]) ? $arrVendedorDados["ven_observacao"]: "null";
    $vVenAtivo         = isset($arrVendedorDados["ven_ativo"]) ? $arrVendedorDados["ven_ativo"]: "null";
    $vVenComissao      = isset($arrVendedorDados["ven_comissao"]) ? $arrVendedorDados["ven_comissao"]: 0;

    $data = array(
      'ven_nome'           => $vVenNome,
      'ven_cpf_cnpj'       => $vVenCpfCnpj,
      'ven_rg_ie'          => $vVenRgIe,
      'ven_tel_ddd'        => $vVenTelDdd,
      'ven_tel_numero'     => $vVenTelNumero,
      'ven_cel_ddd'        => $vVenCelDdd,
      'ven_cel_numero'     => $vVenCelNumero,
      'ven_end_cep'        => $vVenEndCep,
      'ven_end_tp_lgr'     => $vVenEndTpLgr,
      'ven_end_logradouro' => $vVenEndLogradouro,
      'ven_end_numero'     => $vVenEndNumero,
      'ven_end_bairro'     => $vVenEndBairro,
      'ven_end_cidade'     => $vVenEndCidade,
      'ven_end_estado'     => $vVenEndEstado,
      'ven_observacao'     => $vVenObs,
      'ven_ativo'          => $vVenAtivo,
      'ven_comissao'       => $vVenComissao,
    );

    $this->db->where('ven_id', $vVenId);
    $retInsert = $this->db->update('tb_vendedor', $data);

    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Vendedor alterado com sucesso!";
    }

    return $arrRet;
  }
}
