<script>
    $(function(){

        $(document).on('click','.chat_list',  function(){
            $('.chat_list.active').removeClass('active');
            $(this).addClass('active');
        });

    });

    function getAjaxViewForMessage(user_id, message_type, tabElement='.message-content-section', method = 'post') 
    {
        var viewId = '#'+user_id+'-'+message_type+'-id';  
        var viewIdRaw = user_id+'-'+message_type+'-id';  
        var url = '{{ route('chat.user.message') }}';
        var data = { 'user_id' : user_id, message_type : message_type };
        
        $(document).find(tabElement).find('.message-div').hide();

        if(!$(viewId).length){
            var append_div = '<div class="message-div" id="'+viewIdRaw+'"></div>';
            $(tabElement).prepend(append_div);
        }

        var is_empty = $(viewId).is(':empty');
        $(viewId).show();

        if(is_empty){         
            $.ajax({
                url: url,
                type: method,
                data: data,
                beforeSend: function (xhr) {
                    if($(viewId).is(':empty')){
                        $(viewId).html('<div class="loader-img" style="text-align:center;opacity:0.5;" ><img id="img-load" src="{{url('loader.gif')}}"/></div>');
                    }else{
                        is_empty = false;
                    }
                },
                success: function (result) {
                    $(viewId).html(result)
                    $(document).find(viewId).find('.msg_history').scrollTop($(document).find(viewId).find('.msg_history')[0].scrollHeight);
                    
                }, error: function (a) {
                    // console.log(a);
                   
                }
            });
        }

        $.ajax({
            url: '{{ route('readMessage') }}',
            type: 'post',
            data: { 'user_id' : user_id, 'message_type' : message_type }     
        })   

        var message_class = (message_type == 'individual') ? '#individual-appended-'+user_id : '#group-appended-'+user_id;
        if(message_type == 'individual'){
            $('#'+user_id+'-individual-summary-id').removeClass('unread');
        }else if(message_type == 'group'){
            $('#'+user_id+'-group-summary-id').removeClass('unread');
        }
        $('#appended-message').find(message_class).remove();

        if(!countMessage()){
            $('#message-count').removeClass('active');
            $('#message-count').html('');
        }
    }

    Echo.private(`individual.message.${loginUser}`)
        .listen('IndividualMessage', (e) => {
            console.log(e);
            // alert('as');
            e = e['id-'+loginUser];  
            if( $('.inbox_chat').length ){
                $(document).find('#'+e.summary_element).remove();
                $(document).find('.inbox_chat').prepend(e.summary_text);
            }        
            
            if($('#'+e.element).length){
                if(!$('#'+e.message_id).length){
                    $(document).find('#'+e.element).find('.msg_history.individual').append(e.text)
                }
                if(e.message_direction == 'outgoing'){
                    $(document).find('#'+e.message_id).addClass('delivered');
                }
                $(document).find('#'+e.element).find('.msg_history').scrollTop($(document).find('#'+e.element).find('.msg_history')[0].scrollHeight);
            }

            if(e.message_direction == 'incoming'){
                if(!$('#individual-appended-'+e.user).length){
                    var append_msg = '<span class="appended-message" id="individual-appended-'+e.user+'" ></span>';
                    $('#appended-message').append(append_msg);
                    $('#message-count').addClass('active');
                    countMessage();
                }
            }
        });

        Echo.private(`group.message.${loginUser}`)
        .listen('GroupMessage', (e) => {
            // console.log(e);
            // alert('id-'+loginUser);
            if('id-'+loginUser in e){
                e = e['id-'+loginUser];  
            } else {
                e = e['id-receiver'];  
            }
            console.log(e);
            if( $('.inbox_chat').length ){
                $(document).find('#'+e.summary_element).remove();
                $(document).find('.inbox_chat').prepend(e.summary_text);
            }
            
            if($('#'+e.element).length){
                if(!$('#'+e.message_id).length){

                    $(document).find('#'+e.element).find('.msg_history.individual').append(e.text)
                }                
                $(document).find('#'+e.message_id).addClass('delivered');                
                $(document).find('#'+e.element).find('.msg_history').scrollTop($(document).find('#'+e.element).find('.msg_history')[0].scrollHeight);
            }

            if(e.message_direction == 'incoming'){
                if(!$('#group-appended-'+e.user).length){
                    var append_msg = '<span class="appended-message" id="group-appended-'+e.user+'" ></span>';
                    $('#appended-message').append(append_msg);
                    $('#message-count').addClass('active');
                    countMessage();
                }
            }
          
        });

        // $(document).on('submit','#chat-message-form', function(event) {
        //     event.preventDefault();
        // });

        $(document).on('submit','#chat-message-form', function(event) {
            event.preventDefault();
            var obj = $(this);
            url = "{{ route('chat.user.message.store')}}";
            var data = $(this).closest('form').serialize();
            var message = obj.closest('form').find('[name=message]').val();
            
            var success = function(res){
                var elementId = obj.closest('form').find('[name=element]').val();
                $(document).find('#'+elementId).find('.msg_history.individual').append(res.text);
                $(document).find(obj).find('[name=message]').val('');
            }
            if(message){
                ajaxCalls(url,  data , 'post', success);
            }
        });

        $(document).on('click','a.load-more-pagination',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var obj = $(this);       
            var success = function(res){     
            console.log(res);  
                $(document).find(obj).closest('.message-content-section').find('.msg_history').prepend(res);                
                $(obj).remove();                
            }
            ajaxCalls(url,  {} , 'post', success);
        })

</script>