<?php
class Tb_Cliente extends CI_Model {

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

    $vSql  = " SELECT cli_id, cli_nome, cli_cpf_cnpj, cli_tel_ddd, cli_tel_numero, cli_end_cidade, cli_end_estado ";
    $vSql .= " FROM tb_cliente ";
    $vSql .= " WHERE cli_ativo = true ";
    $vSql .= " ORDER BY cli_nome ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    if(count($arrRs) <= 0){
      $htmlTable .= "  <tr class=''>";
      $htmlTable .= "    <td colspan='8'>";
      $htmlTable .= "      <center>Nenhum resultado encontrado!</center>";
      $htmlTable .= "    </td>";
      $htmlTable .= "  </tr>";
    } else {
      foreach($arrRs as $rs1){
        $vCliId        = $rs1["cli_id"];
        $vCliNome      = $rs1["cli_nome"];
        $vCliCpfCnpj   = $rs1["cli_cpf_cnpj"];
        $vCliTelefone  = "(".$rs1["cli_tel_ddd"].") " . $rs1["cli_tel_numero"];
        $vCliCidade    = $rs1["cli_end_cidade"] . " (".$rs1["cli_end_estado"].")";

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vCliId</td>";
        $htmlTable .= "  <td>$vCliNome</td>";
        $htmlTable .= "  <td>$vCliCpfCnpj</td>";
        $htmlTable .= "  <td>$vCliTelefone</td>";
        $htmlTable .= "  <td>$vCliCidade</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Cliente/ver/$vCliId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Cliente/editar/$vCliId" . "'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbCliente_deletar' data-id='$vCliId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getCliente($cliId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrClienteDados"]  = array();

    if(!is_numeric($cliId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o cliente!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("cli_id, cli_nome, cli_cpf_cnpj, cli_rg_ie, cli_tel_ddd, cli_tel_numero, cli_cel_ddd, cli_cel_numero, cli_end_cep, cli_end_tp_lgr, cli_end_logradouro, cli_end_numero, cli_end_bairro, cli_end_cidade, cli_end_estado, cli_observacao, cli_ativo");
    $this->db->from("tb_cliente");
    $this->db->where("cli_id", $cliId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrClienteDados = [];
      $arrClienteDados["cli_id"]             = $row->cli_id;
      $arrClienteDados["cli_nome"]           = $row->cli_nome;
      $arrClienteDados["cli_cpf_cnpj"]       = $row->cli_cpf_cnpj;
      $arrClienteDados["cli_rg_ie"]          = $row->cli_rg_ie;
      $arrClienteDados["cli_tel_ddd"]        = $row->cli_tel_ddd;
      $arrClienteDados["cli_tel_numero"]     = $row->cli_tel_numero;
      $arrClienteDados["cli_cel_ddd"]        = $row->cli_cel_ddd;
      $arrClienteDados["cli_cel_numero"]     = $row->cli_cel_numero;
      $arrClienteDados["cli_end_cep"]        = $row->cli_end_cep;
      $arrClienteDados["cli_end_tp_lgr"]     = $row->cli_end_tp_lgr;
      $arrClienteDados["cli_end_logradouro"] = $row->cli_end_logradouro;
      $arrClienteDados["cli_end_numero"]     = $row->cli_end_numero;
      $arrClienteDados["cli_end_bairro"]     = $row->cli_end_bairro;
      $arrClienteDados["cli_end_cidade"]     = $row->cli_end_cidade;
      $arrClienteDados["cli_end_estado"]     = $row->cli_end_estado;
      $arrClienteDados["cli_observacao"]     = $row->cli_observacao;
      $arrClienteDados["cli_ativo"]          = $row->cli_ativo;

      $arrRet["arrClienteDados"] = $arrClienteDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrClienteDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vCliNome = (isset($arrClienteDados["cli_nome"])) ? $arrClienteDados["cli_nome"]: "";
    if( strlen($vCliNome) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o nome!";
      return $arrRet;
    }

    // cpf duplicado
    $vCliCpfCnpj = isset($arrClienteDados["cli_cpf_cnpj"]) ? $arrClienteDados["cli_cpf_cnpj"]: "";

    $this->load->helper('utils');
    $validCPF = is_cpf($vCliCpfCnpj);
    if(!$validCPF){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "CPF inválido!";
      return $arrRet;
    }

    if($vCliCpfCnpj != ""){
      $this->load->database();
      $this->db->select("cli_id");
      $this->db->from("tb_cliente");
      $this->db->where("cli_cpf_cnpj", $vCliCpfCnpj);
      $this->db->where("cli_cpf_cnpj IS NOT NULL");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vCliId = $row->cli_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um cliente com esse CPF/CNPJ (ID $vCliId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    // rg duplicado
    $vCliRgIe = isset($arrClienteDados["cli_rg_ie"]) ? $arrClienteDados["cli_rg_ie"]: "";

    if($vCliRgIe != ""){
      $this->load->database();
      $this->db->select("cli_id");
      $this->db->from("tb_cliente");
      $this->db->where("cli_rg_ie", $vCliRgIe);
      $this->db->where("cli_rg_ie IS NOT NULL");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vCliId = $row->cli_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um cliente com esse RG/IE (ID $vCliId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    $vAtivo = (isset($arrClienteDados["cli_ativo"])) ? $arrClienteDados["cli_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o cliente está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrClienteDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrClienteDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vCliNome          = isset($arrClienteDados["cli_nome"]) ? $arrClienteDados["cli_nome"]: "null";
    $vCliCpfCnpj       = isset($arrClienteDados["cli_cpf_cnpj"]) ? $arrClienteDados["cli_cpf_cnpj"]: "null";
    $vCliRgIe          = isset($arrClienteDados["cli_rg_ie"]) ? $arrClienteDados["cli_rg_ie"]: "null";
    $vCliTelDdd        = isset($arrClienteDados["cli_tel_ddd"]) ? $arrClienteDados["cli_tel_ddd"]: "null";
    $vCliTelNumero     = isset($arrClienteDados["cli_tel_numero"]) ? $arrClienteDados["cli_tel_numero"]: "null";
    $vCliCelDdd        = isset($arrClienteDados["cli_cel_ddd"]) ? $arrClienteDados["cli_cel_ddd"]: "null";
    $vCliCelNumero     = isset($arrClienteDados["cli_cel_numero"]) ? $arrClienteDados["cli_cel_numero"]: "null";
    $vCliEndCep        = isset($arrClienteDados["cli_end_cep"]) ? $arrClienteDados["cli_end_cep"]: "null";
    $vCliEndTpLgr      = isset($arrClienteDados["cli_end_tp_lgr"]) ? $arrClienteDados["cli_end_tp_lgr"]: "null";
    $vCliEndLogradouro = isset($arrClienteDados["cli_end_logradouro"]) ? $arrClienteDados["cli_end_logradouro"]: "null";
    $vCliEndNumero     = isset($arrClienteDados["cli_end_numero"]) ? $arrClienteDados["cli_end_numero"]: "null";
    $vCliEndBairro     = isset($arrClienteDados["cli_end_bairro"]) ? $arrClienteDados["cli_end_bairro"]: "null";
    $vCliEndCidade     = isset($arrClienteDados["cli_end_cidade"]) ? $arrClienteDados["cli_end_cidade"]: "null";
    $vCliEndEstado     = isset($arrClienteDados["cli_end_estado"]) ? $arrClienteDados["cli_end_estado"]: "null";
    $vCliObs           = isset($arrClienteDados["cli_observacao"]) ? $arrClienteDados["cli_observacao"]: "null";
    $vCliAtivo         = isset($arrClienteDados["cli_ativo"]) ? $arrClienteDados["cli_ativo"]: "null";

    $data = array(
      'cli_nome'           => $vCliNome,
      'cli_cpf_cnpj'       => $vCliCpfCnpj,
      'cli_rg_ie'          => $vCliRgIe,
      'cli_tel_ddd'        => $vCliTelDdd,
      'cli_tel_numero'     => $vCliTelNumero,
      'cli_cel_ddd'        => $vCliCelDdd,
      'cli_cel_numero'     => $vCliCelNumero,
      'cli_end_cep'        => $vCliEndCep,
      'cli_end_tp_lgr'     => $vCliEndTpLgr,
      'cli_end_logradouro' => $vCliEndLogradouro,
      'cli_end_numero'     => $vCliEndNumero,
      'cli_end_bairro'     => $vCliEndBairro,
      'cli_end_cidade'     => $vCliEndCidade,
      'cli_end_estado'     => $vCliEndEstado,
      'cli_observacao'     => $vCliObs,
      'cli_ativo'          => $vCliAtivo,
    );

    $retInsert = $this->db->insert('tb_cliente', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Cliente inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrClienteDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vCliId = (isset($arrClienteDados["cli_id"])) ? $arrClienteDados["cli_id"]: "";
    if(!$vCliId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível editar! ID do Cliente não informado!";
      return $arrRet;
    }

    $vCliNome = (isset($arrClienteDados["cli_nome"])) ? $arrClienteDados["cli_nome"]: "";
    if( strlen($vCliNome) < 2 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o nome!";
      return $arrRet;
    }

    // cpf duplicado
    $vCliCpfCnpj = isset($arrClienteDados["cli_cpf_cnpj"]) ? $arrClienteDados["cli_cpf_cnpj"]: "";

    $this->load->helper('utils');
    $validCPF = is_cpf($vCliCpfCnpj);
    if(!$validCPF){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "CPF inválido!";
      return $arrRet;
    }

    if($vCliCpfCnpj != ""){
      $this->load->database();
      $this->db->select("cli_id");
      $this->db->from("tb_cliente");
      $this->db->where("cli_cpf_cnpj", $vCliCpfCnpj);
      $this->db->where("cli_cpf_cnpj IS NOT NULL");
      $this->db->where("cli_id <> $vCliId");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vCliId = $row->cli_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um cliente com esse CPF/CNPJ (ID $vCliId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    // rg duplicado
    $vCliRgIe = isset($arrClienteDados["cli_rg_ie"]) ? $arrClienteDados["cli_rg_ie"]: "";

    if($vCliRgIe != ""){
      $this->load->database();
      $this->db->select("cli_id");
      $this->db->from("tb_cliente");
      $this->db->where("cli_rg_ie", $vCliRgIe);
      $this->db->where("cli_rg_ie IS NOT NULL");
      $this->db->where("cli_id <> $vCliId");
      $query = $this->db->get();
      if($query->num_rows() > 0){
        $row    = $query->row();
        $vCliId = $row->cli_id;

        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Já existe um cliente com esse RG/IE (ID $vCliId)! Verifique!";
        return $arrRet;
      }
      $this->db->reset_query();
    }
    // ===================

    $vAtivo = (isset($arrClienteDados["cli_ativo"])) ? $arrClienteDados["cli_ativo"]: "";
    if($vAtivo != 1 && $vAtivo != 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe se o cliente está ativo!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrClienteDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrClienteDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vCliId            = isset($arrClienteDados["cli_id"]) ? $arrClienteDados["cli_id"]: "";
    $vCliNome          = isset($arrClienteDados["cli_nome"]) ? $arrClienteDados["cli_nome"]: "null";
    $vCliCpfCnpj       = isset($arrClienteDados["cli_cpf_cnpj"]) ? $arrClienteDados["cli_cpf_cnpj"]: "null";
    $vCliRgIe          = isset($arrClienteDados["cli_rg_ie"]) ? $arrClienteDados["cli_rg_ie"]: "null";
    $vCliTelDdd        = isset($arrClienteDados["cli_tel_ddd"]) ? $arrClienteDados["cli_tel_ddd"]: "null";
    $vCliTelNumero     = isset($arrClienteDados["cli_tel_numero"]) ? $arrClienteDados["cli_tel_numero"]: "null";
    $vCliCelDdd        = isset($arrClienteDados["cli_cel_ddd"]) ? $arrClienteDados["cli_cel_ddd"]: "null";
    $vCliCelNumero     = isset($arrClienteDados["cli_cel_numero"]) ? $arrClienteDados["cli_cel_numero"]: "null";
    $vCliEndCep        = isset($arrClienteDados["cli_end_cep"]) ? $arrClienteDados["cli_end_cep"]: "null";
    $vCliEndTpLgr      = isset($arrClienteDados["cli_end_tp_lgr"]) ? $arrClienteDados["cli_end_tp_lgr"]: "null";
    $vCliEndLogradouro = isset($arrClienteDados["cli_end_logradouro"]) ? $arrClienteDados["cli_end_logradouro"]: "null";
    $vCliEndNumero     = isset($arrClienteDados["cli_end_numero"]) ? $arrClienteDados["cli_end_numero"]: "null";
    $vCliEndBairro     = isset($arrClienteDados["cli_end_bairro"]) ? $arrClienteDados["cli_end_bairro"]: "null";
    $vCliEndCidade     = isset($arrClienteDados["cli_end_cidade"]) ? $arrClienteDados["cli_end_cidade"]: "null";
    $vCliEndEstado     = isset($arrClienteDados["cli_end_estado"]) ? $arrClienteDados["cli_end_estado"]: "null";
    $vCliObs           = isset($arrClienteDados["cli_observacao"]) ? $arrClienteDados["cli_observacao"]: "null";
    $vCliAtivo         = isset($arrClienteDados["cli_ativo"]) ? $arrClienteDados["cli_ativo"]: "null";

    $data = array(
      'cli_nome'           => $vCliNome,
      'cli_cpf_cnpj'       => $vCliCpfCnpj,
      'cli_rg_ie'          => $vCliRgIe,
      'cli_tel_ddd'        => $vCliTelDdd,
      'cli_tel_numero'     => $vCliTelNumero,
      'cli_cel_ddd'        => $vCliCelDdd,
      'cli_cel_numero'     => $vCliCelNumero,
      'cli_end_cep'        => $vCliEndCep,
      'cli_end_tp_lgr'     => $vCliEndTpLgr,
      'cli_end_logradouro' => $vCliEndLogradouro,
      'cli_end_numero'     => $vCliEndNumero,
      'cli_end_bairro'     => $vCliEndBairro,
      'cli_end_cidade'     => $vCliEndCidade,
      'cli_end_estado'     => $vCliEndEstado,
      'cli_observacao'     => $vCliObs,
      'cli_ativo'          => $vCliAtivo,
    );

    $this->db->where('cli_id', $vCliId);
    $retInsert = $this->db->update('tb_cliente', $data);

    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Cliente alterado com sucesso!";
    }

    return $arrRet;
  }
}
