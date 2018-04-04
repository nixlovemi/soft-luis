$(document).ready(function(){
	var HOME_URL = 'http://127.0.0.1/webapp/';

	// Tb_Produto
	$('.TbProduto_deletar').click(function(){
		var proId = $(this).data("id");
		var html  = 'Gostaria de deletar o produto ID ' + proId + '?';

		confirmBootbox(html, function(){
			document.location.href = HOME_URL + 'Produto/deletar/' + proId;
		});
	});
	// ==========



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
