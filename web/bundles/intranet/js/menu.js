/* Desktop Version */

$('.openMenu').click(function (){
    $(".subMenuSystem").slideToggle();
});

$('.viewContainer').click(function (){ 
    $('.subMenuSystem').removeAttr('style');
});

var name = $.trim($('#nameLogged').html());
var arr_name = name.split(' ');
$('#nameLogged').html(arr_name[0]);

/* Mobile Version */

var width = window.innerWidth;

if (width <= 599) {
    
    var name = $.trim($('.fullname').html());
    var arr_name = name.split(' ');
    $('.fullname').html(arr_name[0]);
    
    $('.topLink').click(function(ev){      
        var label = $(this).attr('label');
        var menuItem = label.split('menu');
        
        $('#subMenu' + menuItem[1]).slideToggle(300,function (){
            $('.topLink').each(function(){
                var labelName = $(this).attr('label');
                var menuItemName = labelName.split('menu');
                
                if (!$('#subMenu' + menuItemName[1]).is(":hidden") && label !== labelName) {
                    $('#subMenu' + menuItemName[1]).slideToggle(200);
                }
            }); 
        });        
    });
    
    $('.topSubLink').click(function(ev){        
        var menuItem = $(this).attr('label');
        var menuItem = menuItem.split('submenu');
        var id = 'levelSubMenu' + menuItem[1];
        
        $("#" + id).slideToggle();
    });
    
    $('#menuSwitcher').click(function (){
        $('#menu').animate({
            'marginLeft': '0%'
        }, 500);
    });
    
    $('#closeMenu, .viewContainer, .footer').click(function (){
        $('#menu').animate({
            'marginLeft': '-50%'
        }, 500);
    });
}

/* Mibew */
Mibew.ChatPopup.init({
			"id":"589353bf35e2913b",
			"url":"http:\/\/chat.educacao.itajai.sc.gov.br\/new\/index.php\/chat?locale=pt-br",
			"preferIFrame":true,
			"modSecurity":false,
			"width":640,
			"height":480,
			"resizable":true,
			"styleLoader":"http:\/\/chat.educacao.itajai.sc.gov.br\/new\/index.php\/chat\/style\/popup"
			});
