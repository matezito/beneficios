(function($){
    $(document).on('click','.solicitar',function(){
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
                user:user
            },
            success: function(res){
                if(res.success){
                    alert(res.data);
                    window.location.reload();
                } else {
                    alert(res.data);
                }
            },
            error: function(res){
                console.log(res);
            }
        });
    });
})(jQuery);