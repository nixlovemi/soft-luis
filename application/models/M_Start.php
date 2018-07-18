<?php
class M_Start extends CI_Model {
  public function getArrInfoStart(){
    $arrRet = [];
    $arrRet["erro"]  = false;
    $arrRet["msg"]   = "";
    $arrRet["array"] = array();

    $dtIni        = date("Y-m") . "-01 00:00:00";
    $dtFim        = date("Y-m-t") . " 23:59:59";
    $vdaStatusFin = 2;

    $this->load->database();

    // qt venda e tot venda
    $arrRet["array"]["qtd_venda"] = 0;
    $arrRet["array"]["tot_venda"] = 0;

    $vSql1 = "SELECT COALESCE(COUNT(*), 0) AS qt_venda
                    , COALESCE(SUM(vda_vlr_itens), 0) AS tot_venda
              FROM tb_venda
              WHERE vda_status IN ($vdaStatusFin)
              AND vda_data BETWEEN '$dtIni' AND '$dtFim'";
    $qry1  = $this->db->query($vSql1);
    $row1  = $qry1->row();

    if(isset($row1)){
      $arrRet["array"]["qtd_venda"] = $row1->qt_venda;
      $arrRet["array"]["tot_venda"] = $row1->tot_venda;
    }
    // ====================

    // contas a receber ===
    $arrRet["array"]["cts_receber"] = 0;

    $vSql2 = "SELECT COALESCE(SUM(ctr_valor), 0) AS tot_receber
              FROM tb_cont_receber
              WHERE ctr_dtpagamento IS NULL
              AND ctr_dtvencimento BETWEEN '$dtIni' AND '$dtFim'";
    $qry2  = $this->db->query($vSql2);
    $row2  = $qry2->row();

    if(isset($row2)){
      $arrRet["array"]["cts_receber"] = $row2->tot_receber;
    }
    // ====================

    // contas a pagar =====
    $arrRet["array"]["cts_pagar"] = 0;

    $vSql3 = "SELECT COALESCE(SUM(ctp_valor), 0) AS tot_pagar
              FROM tb_cont_pagar
              WHERE ctp_dtpagamento IS NULL
              AND ctp_dtvencimento BETWEEN '$dtIni' AND '$dtFim'";
    $qry3  = $this->db->query($vSql3);
    $row3  = $qry3->row();

    if(isset($row3)){
      $arrRet["array"]["cts_pagar"] = $row3->tot_pagar;
    }
    // ====================

    return $arrRet;
  }

  public function sendBackup(){
    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "Erro ao fazer Backup.";

    $this->load->dbutil();

    /*
    $bkpcf = array(
        'tables'        => array('tabela', 'tabela_2'),   // Lista de tabelas que serão adicionadas ao backup
        'ignore'        => array(),                       // Lista de tabelas que serão omitidas do backup
        'format'        => 'txt',                         // Formato do backup: gzip, zip, txt
        'filename'      => 'backup.sql',                  // Nome do arquivo (utilizado somente se o formato for ZIP)
        'add_drop'      => TRUE,                          // Adição de DROP TABLE
        'add_insert'    => TRUE,                          // Adição de INSERT (útil caso queira fazer o backup não só da estrutura, mas também dos dados)
        'newline'       => "\n"                           // Caracter usado para definir quebra de linha
    );
    */
    $prefs = array(
        'format'      => 'zip',
        'filename'    => 'my_db_backup.sql',
        'add_insert'  => TRUE,
    );
    $backup = $this->dbutil->backup($prefs);

    $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
    $save = APPPATH . 'cache/'.$db_name;

    $this->load->helper('file');
    write_file($save, $backup);

    if(file_exists($save)){
      // manda email
      $this->load->library('email');

      $this->email->from('luiscesartruculo@hotmail.com', 'Soft Luis');
      $this->email->to('nixlovemi@gmail.com', 'Leandro Nix');

      $this->email->subject('Backup Soft Luis - ' . date("d/m/Y H:i"));
      $this->email->message('Segue anexo do backup');
      $this->email->attach($save);

      $ret = $this->email->send();
      if($ret == false){
        // erro na hora de enviar email
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Erro ao enviar email do Backup.";
        return $arrRet;
      } else {
        // deu certo
        $arrRet["erro"] = false;
        $arrRet["msg"]  = "Backup enviado com sucesso!";
        return $arrRet;
      }
      // ===========
    } else {
      // erro no bkp
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao salvar arquivo do Backup.";
      return $arrRet;
    }

    delete_files($save);

    // $this->load->helper('download');
    // force_download($db_name, $backup);

    return $arrRet;
  }
}
