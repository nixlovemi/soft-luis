var HOME_URL = 'http://127.0.0.1/webapp/';

function moedaParaNumero(valor)
{
    return isNaN(valor) == false ? parseFloat(valor) :   parseFloat(valor.replace("R$","").replace(".","").replace(",","."));
}
function numeroParaMoeda(n, c, d, t)
{
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

// botoes da lista
$(document).on('click', '.dynatableLink', function(){
	var url = $(this).data('url');

	var currentUrl = document.location.href;
	var arrUrl     = currentUrl.split('?');
	if (typeof arrUrl[1] !== 'undefined') {
		url = url + '?' + arrUrl[1];
	}

	document.location.href = url;
});
// ===============

// Tb_Venda ======
$(document).on('click', '#frmAddProdVenda #addProdVenda', function(){
	var vdaId  = $("#frmEditVendaInfo #vdaId").val();
	var frmVdi = $("#frmAddProdVenda").serialize();

	$.ajax({
    type: "POST",
    url: HOME_URL + 'VendaItens/jsonAddProduto',
    data: frmVdi + '&vdaId=' + vdaId,
		dataType: 'json',
		success: function (ret) {
			var erro        = ret.erro;
			var msg         = ret.msg;
			var htmlTbItens = ret.htmlTbProd;

			if(erro){
				$.gritter.add({
					title: 'Alerta',
					text: msg,
				});
			} else {
				$("#frmAddProdVenda #vdiProId").val("");
				$("#frmAddProdVenda #vdiQtde").val("");
				$("#frmAddProdVenda #vdiValor").val("");
				$("#frmAddProdVenda #vdiDesconto").val("");
				$("#htmlTbVendaItens").html(htmlTbItens);
			}
    }
  });
});

$(document).on('change', '#frmAddProdVenda #vdiProId', function(){
	var proId = $(this).val();

	$.ajax({
    type: "POST",
    url: HOME_URL + 'Produto/jsonGetProduto/' + proId,
    data: '',
		dataType: 'json',
		success: function (ret) {
			var erro    = ret.erro;
			var msg     = ret.msg;
			var Produto = ret.Produto;

			if(!erro){
				var valor = Produto.pro_prec_venda;
				var texto = numeroParaMoeda(valor);

				$("#frmAddProdVenda #vdiValor").val( texto );
			} else {
				$("#frmAddProdVenda #vdiValor").val('');
				$("#frmAddProdVenda #vdiDesconto").val('');
			}
    }
  });
});

$(document).on('click', '.TbVendaItem_deletar', function(){
	var vdiId = $(this).data("id");
	var html  = 'Gostaria de remover esse produto da venda?';

	confirmBootbox(html, function(){
    $.ajax({
      type: "POST",
      url: HOME_URL + 'VendaItens/jsonRemoveProduto',
      data: 'vdiId=' + vdiId,
  		dataType: 'json',
  		success: function (ret) {
  			var erro        = ret.erro;
  			var msg         = ret.msg;
  			var htmlTbItens = ret.htmlTbProd;

  			if(erro){
  				$.gritter.add({
  					title: 'Alerta',
  					text: msg,
  				});
  			} else {
  				$("#htmlTbVendaItens").html(htmlTbItens);
  			}
      }
    });
	});
});
// ===============

// Tb_Produto
$(document).on('click', '.TbProduto_deletar', function(){
	var proId = $(this).data("id");
	var html  = 'Gostaria de deletar o produto ID ' + proId + '?';

	confirmBootbox(html, function(){
		document.location.href = HOME_URL + 'Produto/deletar/' + proId;
	});
});
// ==========

// Tb_Cliente
$(document).on('click', '.TbCliente_deletar', function(){
	var cliId = $(this).data("id");
	var html  = 'Gostaria de deletar o cliente ID ' + cliId + '?';

	confirmBootbox(html, function(){
		document.location.href = HOME_URL + 'Cliente/deletar/' + cliId;
	});
});
// ==========

// Tb_Vendedor
$(document).on('click', '.TbVendedor_deletar', function(){
	var venId = $(this).data("id");
	var html  = 'Gostaria de deletar o vendedor ID ' + venId + '?';

	confirmBootbox(html, function(){
		document.location.href = HOME_URL + 'Vendedor/deletar/' + venId;
	});
});
// ==========

$(document).ready(function(){
	$('.dynatable').dynatable({
		inputs: {
			paginationPrev: 'Anterior',
			paginationNext: 'Próximo',
			perPageText: 'Exibir: ',
			recordCountText: 'Exibindo ',
			processingText: 'Processando ...',
		}
	});
	$(".mask_cpf").mask("999.999.999-99");
	$(".mask_cep").mask("99.999-999");
	$(".mask_inteiro").numeric();
	$('.mask_moeda').mask("#.##0,00", {reverse: true});
	//$('.mask_moeda').mask('000.000.000.000.000,00', {reverse: true});

	// === Sidebar navigation === //
	$('.submenu > a').click(function(e)
	{
		e.preventDefault();
		var submenu = $(this).siblings('ul');
		var li = $(this).parents('li');
		var submenus = $('#sidebar li.submenu ul');
		var submenus_parents = $('#sidebar li.submenu');
		if(li.hasClass('open'))
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenu.slideUp();
			} else {
				submenu.fadeOut(250);
			}
			li.removeClass('open');
		} else
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenus.slideUp();
				submenu.slideDown();
			} else {
				submenus.fadeOut(250);
				submenu.fadeIn(250);
			}
			submenus_parents.removeClass('open');
			li.addClass('open');
		}
	});

	var ul = $('#sidebar > ul');

	$('#sidebar > a').click(function(e)
	{
		e.preventDefault();
		var sidebar = $('#sidebar');
		if(sidebar.hasClass('open'))
		{
			sidebar.removeClass('open');
			ul.slideUp(250);
		} else
		{
			sidebar.addClass('open');
			ul.slideDown(250);
		}
	});

	// === Resize window related === //
	$(window).resize(function()
	{
		if($(window).width() > 479)
		{
			ul.css({'display':'block'});
			$('#content-header .btn-group').css({width:'auto'});
		}
		if($(window).width() < 479)
		{
			ul.css({'display':'none'});
			fix_position();
		}
		if($(window).width() > 768)
		{
			$('#user-nav > ul').css({width:'auto',margin:'0'});
			$('#content-header .btn-group').css({width:'auto'});
		}
	});

	if($(window).width() < 468)
	{
		ul.css({'display':'none'});
		fix_position();
	}

	if($(window).width() > 479)
	{
		$('#content-header .btn-group').css({width:'auto'});
		ul.css({'display':'block'});
	}

	// === Tooltips === //
	$('.tip').tooltip();
	$('.tip-left').tooltip({ placement: 'left' });
	$('.tip-right').tooltip({ placement: 'right' });
	$('.tip-top').tooltip({ placement: 'top' });
	$('.tip-bottom').tooltip({ placement: 'bottom' });

	// === Search input typeahead === //
	$('#search input[type=text]').typeahead({
		source: ['Dashboard','Form elements','Common Elements','Validation','Wizard','Buttons','Icons','Interface elements','Support','Calendar','Gallery','Reports','Charts','Graphs','Widgets'],
		items: 4
	});

	// === Fixes the position of buttons group in content header and top user navigation === //
	function fix_position()
	{
		var uwidth = $('#user-nav > ul').width();
		$('#user-nav > ul').css({width:uwidth,'margin-left':'-' + uwidth / 2 + 'px'});

		var cwidth = $('#content-header .btn-group').width();
		$('#content-header .btn-group').css({width:cwidth,'margin-left':'-' + uwidth / 2 + 'px'});
	}

	// === Style switcher === //
	$('#style-switcher i').click(function()
	{
		if($(this).hasClass('open'))
		{
			$(this).parent().animate({marginRight:'-=190'});
			$(this).removeClass('open');
		} else
		{
			$(this).parent().animate({marginRight:'+=190'});
			$(this).addClass('open');
		}
		$(this).toggleClass('icon-arrow-left');
		$(this).toggleClass('icon-arrow-right');
	});

	$('#style-switcher a').click(function()
	{
		var style = $(this).attr('href').replace('#','');
		$('.skin-color').attr('href','css/maruti.'+style+'.css');
		$(this).siblings('a').css({'border-color':'transparent'});
		$(this).css({'border-color':'#aaaaaa'});
	});

	$('.lightbox_trigger').click(function(e) {

		e.preventDefault();

		var image_href = $(this).attr("href");

		if ($('#lightbox').length > 0) {

			$('#imgbox').html('<img src="' + image_href + '" /><p><i class="icon-remove icon-white"></i></p>');

			$('#lightbox').slideDown(500);
		}

		else {
			var lightbox =
			'<div id="lightbox" style="display:none;">' +
			'<div id="imgbox"><img src="' + image_href +'" />' +
			'<p><i class="icon-remove icon-white"></i></p>' +
			'</div>' +
			'</div>';

			$('body').append(lightbox);
			$('#lightbox').slideDown(500);
		}

	});


	$('#lightbox').live('click', function() {
		$('#lightbox').hide(200);
	});

});

// bootbox
/**
* Abre o modal Bootbox.
*
* @param {text} response [HTML com o conteudo da modal]
* @param {bool} bold [se TRUE vai colocar um span container no response com style bold]
* @param {text/function} on_open [funcao pra executar qdo o modal abrir][se funcao retornar false o modal nao fecha]
* @param {text/function} on_click [gera um btn OK e adiciona funcao pra executar qdo clicar][se funcao retornar false o modal nao fecha]
* @param {text/function} on_close [funcao pra executar qdo o modal abrir][se funcao retornar false o modal nao fecha]
*/
function openBootbox(response, bold, on_open, on_click, on_close) {
	bold = typeof bold !== 'undefined' ? bold : false;
	on_open = typeof on_open !== 'undefined' ? on_open : false;
	on_click = typeof on_click !== 'undefined' ? on_click : false;
	on_close = typeof on_close !== 'undefined' ? on_close : false;
	response = (bold) ? '<span class="bootbox-confirm">' + response + '</span>' : response;

	var buttons = {};
	if (on_click === false) {
		buttons = {
			btn_fechar: {
				label: "Fechar",
				className: "btn-danger",
				callback: function () {
					if (on_close !== false) {
						on_close();
					}
				}
			}
		};
	} else {
		buttons = {
			btn_ok: {
				label: "OK",
				className: "btn-success",
				callback: function () {
					if (on_click !== false) {
						var ret = on_click();

						// Se o retorno da funcao callback for false nao fecha o bootbox
						if (ret === false)
						return false;

						// Para que as chamadas sem retorno fechem o bootbox
						// Default sempre true
						return true;
					}
				}
			},
			btn_fechar: {
				label: "Fechar",
				className: "btn-danger",
				callback: function () {
					if (on_close !== false) {
						on_close();
					}
				}
			}
		};
	}

	window.bootModal = bootbox.dialog({
		message: response,
		size: 'large',
		locale: 'pt',
		closeButton: 'false',
		onEscape: function () {
			if (on_close !== false) {
				on_close();
			}
		},
		buttons: buttons,
	}).on("shown.bs.modal", function () {
		if (on_open !== false) {
			on_open();
		}
	});
}

function confirmBootbox(html, callback){
	var buttons = {};
	buttons = {
		btn_ok: {
			label: "Confirmar",
			className: "btn-success",
			callback: function () {
				var ret = callback();

				// Se o retorno da funcao callback for false nao fecha o bootbox
				if (ret === false)
				return false;

				// Para que as chamadas sem retorno fechem o bootbox
				// Default sempre true
				return true;
			}
		},
		cancel: {
			label: "Fechar",
			className: "btn-danger",
			callback: function () { }
		}
	};

	bootbox.dialog({
		message: html,
		size: 'large',
		locale: 'pt',
		closeButton: 'false',
		buttons: buttons,
	})
}
// =======
