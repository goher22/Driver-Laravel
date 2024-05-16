'use strict';

require('./bootstrap');
require('./form.authy.min');

 $(document).ready(function(){
 	$("#menu-toggle").on('click', function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
    });

    $(".checkall").on('click', function(){
    	var status = $(this).attr('checked');
    	$(this).parent().parent().parent().find('input').attr('checked',!status);
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('[data-toggle="popover"]').popover()
    
    $('.popover-dismiss').popover({
	  trigger: 'focus'
	})

    $(".deleteBtn").on('click', function(){
        var form = $(this).parent().parent().find('form');
        var message = $(this).attr('data-confirm-message');

        bootbox.confirm({ 
            message: message,
            buttons: {
                confirm: {
                    className: 'btn-primary'
                },
                cancel: {
                    className: 'btn-danger'
                }
            },
            locale: window.appLocale,
            callback: function(result){ 
                if(result) form.submit(); 
            }
        })
    });

    $(".confirmBtn").on('click', function(event){
        event.preventDefault();

        var message = $(this).attr('data-confirm-message');
        var url = $(this).attr('href');

        bootbox.confirm({ 
            message: message,
            buttons: {
                confirm: {
                    className: 'btn-primary'
                },
                cancel: {
                    className: 'btn-danger'
                }
            },
            locale: window.appLocale,
            callback: function(result){ 
                if(result) window.location = url;
            }
        })
    });

    $("select[name=MAIL_DRIVER]").change(function(){
        var val = $(this).val();
        if(val == 'smtp'){
            $("#smtp_fields").removeClass('d-none');
        } else {
            $("#smtp_fields").addClass('d-none');
        }
    });

    $(".social-auth-provider").on('click', function(){
        var name = $(this).attr('name');
        var checked = $(this).is(':checked');
        if(checked) {
            $("#"+name+"-settings").removeClass('d-none');
        } else {
            $("#"+name+"-settings").addClass('d-none');
        }
    });

 });
