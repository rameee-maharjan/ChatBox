<script>

    function getAjaxModal(url, data = {}, modalTitle = '', callback = false, modalclass = '#ajax-modal', ajaxclass = '#modal-ajaxview', method = 'get', modalWidth = 'modal-lg') {
        
        $(modalclass).on('show.bs.modal', function () {
            
            if (modalWidth) {
                $(this).find('.modal-dialog').addClass(modalWidth);
            }
            $(this).find('.modal-content').css({
                'min-height': '200px'
            });
            $(this).find('.modal-title').text(modalTitle);
        });

        $(modalclass).modal('show');

        $.ajax({
            url: url,
            type: method,
            data: data,
            beforeSend: function (xhr) {
                $(ajaxclass).html('');
            },
            success: function (result) {
                if (callback) {
                    callback(result);
                    return;
                }
                $(ajaxclass).html(result);
            }, error: function (err) {
                if(err.status == 401) {
                    window.location.replace('{{ url("login") }}');
                }
                // console.log(error);
            }
        });
    }

     function confirmAlert(callback = '', content = 'Are you sure you want to continue', title = 'Confirmation!', cancel = '', type = 'green', button = 'btn-green') {
        $.confirm({
            title: title,
            content: content,
            type: type,
            buttons: {
                confirm: {
                    icon: 'fa fa-warning',
                    btnClass: button,
                    keys: ['y'],
                    action: function () {
                        if (callback) {
                            callback();
                        } else {
                            return;
                        }
                    }

                },
                cancel: {
                    keys: ['n'],
                    action: function () {
                        if (cancel) {
                            cancel();
                        } else {
                            return;
                        }
                    }

                },

            }
        });
    }

    function ajaxCalls(url,  data = {}, method='get', callback=false, errorCallback=false, successMessage='') {
        $.ajax({
            url: url,
            type: method,
            data: data,
            beforeSend: function (xhr) {
               
            },
            success: function (result) {
                if (callback) {
                    callback(result);
                    return;
                }
                if(successMessage){
                    toastMessage('Success !!!','success', successMessage);
                }

            }, error: function (a) {
                // console.log(a);                
                if(err.status == 401) {
                    window.location.replace('{{ url("login") }}');
                } else {

                    if(errorCallback){
                        errorCallback(err);
                    } 

                    var error = '';
                    if(err.status == 500 || err.status == 400) {
                        errorJson=err.responseJSON;
                        if(errorJson.hasOwnProperty('message')){
                            error = errorJson.message;
                        }
                    }

                    if(error){
                       toastMessage('Error !!!','error', error);
                    }else{
                        if(errorMessage){
                            toastMessage('Error !!!','error', errorMessage);
                        }else{
                            toastMessage('Error !!!','error','Error while performing operation'); 
                        }  
                    }
                }
            }
        });
    }   

    function toastMessage(heading, icon, text, hideAfter = 5000) {
        $.toast().reset('all');
        $.toast({
            heading: false,
            text: text,
            position: 'top-right',
            loader: false,
            loaderBg: false,
            icon: icon,
            hideAfter: hideAfter,
            stack: false,
            showHideTransition: 'slide'
        });
    }    

</script>