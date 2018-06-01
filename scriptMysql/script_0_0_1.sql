CREATE TABLE tb_venda_mostruario (
   vdm_id INT NOT NULL AUTO_INCREMENT,
   vdm_ven_id INT NOT NULL,
   vdm_dtentrega DATE NOT NULL,
   vdm_dtacerto DATE,
   vdm_deletado BOOLEAN,
   PRIMARY KEY (vdm_id),
   FOREIGN KEY (vdm_ven_id) REFERENCES tb_vendedor(ven_id)
);

CREATE TABLE tb_venda_mostruario_itens (
   vmi_id INT NOT NULL AUTO_INCREMENT,
   vmi_vdm_id INT NOT NULL,
   vmi_pro_id INT NOT NULL,
   vmi_qtde INT NOT NULL,
   vmi_valor DOUBLE PRECISION NOT NULL,
   vmi_desconto DOUBLE PRECISION DEFAULT 0,
   PRIMARY KEY (vmi_id),
   FOREIGN KEY (vmi_vdm_id) REFERENCES tb_venda_mostruario(vdm_id),
   FOREIGN KEY (vmi_pro_id) REFERENCES tb_produto(pro_id)
);

ALTER TABLE tb_venda_mostruario CHANGE COLUMN vdm_deletado vdm_deletado BOOLEAN NOT NULL DEFAULT 0;

CREATE TABLE tb_venda_mostruario_itens_ret (
   vmir_id INT NOT NULL AUTO_INCREMENT,
   vmir_vdm_id INT NOT NULL,
   vmir_pro_id INT NOT NULL,
   vmir_qtde INT NOT NULL,
   PRIMARY KEY (vmir_id),
   FOREIGN KEY (vmir_vdm_id) REFERENCES tb_venda_mostruario(vdm_id),
   FOREIGN KEY (vmir_pro_id) REFERENCES tb_produto(pro_id)
);

ALTER TABLE tb_venda_mostruario_itens_ret ADD vmir_valor DOUBLE PRECISION NOT NULL;

ALTER TABLE tb_venda_mostruario ADD vdm_vda_id INTEGER;
ALTER TABLE tb_venda_mostruario ADD FOREIGN KEY (vdm_vda_id) REFERENCES tb_venda(vda_id);

INSERT INTO tb_menu(men_descricao, men_controller, men_action, men_ativo, men_order, men_icon)
VALUES ('Relatórios', 'Relatorio', 'index', 1, 1, '<i class="icon icon-print"></i>');
