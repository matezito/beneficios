(function($){
    $(document).ready(function(){
        $('.select-dates').on('click',function(){
            var btn = $(this).data('button');
            $(btn).attr('disabled',false);
        });
    });

    $(document).on('click','.solicitar',function(){
        var date = $('.select-dates').val();
        var bene_id = $(this).data('id');
        var user = $(this).data('user');
        
        $.ajax({
            type:'post',
            url:ajax_add_beneficio.url,
            data: {
                action: ajax_add_beneficio.action,
                _ajax_nonce: ajax_add_beneficio._ajax_nonce,
                fav_delete: ajax_add_beneficio.fav_delete,
                add_beneficio: ajax_add_beneficio.add_beneficio,
                bene_id:bene_id,
                user:user,
                date:date
            },
            success: function(res){
                console.log(res);
                if(res.success){
                    alert(res.data);
                    window.location.reload();
                } else if(res.data === '001') {
                    
                   $('#solicitar-'+bene_id).slideUp(400,function(){
                    $('#dni-'+bene_id).slideDown();
                    $('.dni-field').not($('#dni-'+bene_id)).hide();
                   });
                }
            },
            error: function(res){
                console.log(res);
            }
        });
    });

    $(document).ready(function(){
        $('.dni-button').on('click',function(){
            var input = $(this).data('id');
            var bene_id = $(input).data('id');
            var user = $(input).data('user');
            var date = $('.select-dates').val();
            var dni = $(input).val();

            $.ajax({
                type:'post',
                url:ajax_add_beneficio.url,
                data: {
                    action: ajax_add_beneficio.action,
                    _ajax_nonce: ajax_add_beneficio._ajax_nonce,
                    fav_delete: ajax_add_beneficio.fav_delete,
                    add_beneficio: ajax_add_beneficio.add_beneficio,
                    bene_id:bene_id,
                    user:user,
                    date:date,
                    dni:dni
                },
                success: function(res){
                    if(res.success){
                        alert(res.data);
                        window.location.reload();
                    } else if(res.data === '001') {
                        alert(res.data);
                    }
                },
                error: function(res){
                    console.log(res);
                }
            });
 
        });
    })
})(jQuery);