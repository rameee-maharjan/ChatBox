<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

<script src="{{ asset('vendor/jquery-3.2.1/jquery.min.js')  }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>

<script src="{{ asset('vendor/bootstrap/bootstrap.min.js') }}"></script>

<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>

<script src="{{ asset('vendor/jquery-confirm-3.3.4/jquery-confirm.min.js') }}"></script>

<script src="{{ asset('/js/app.js') }}"></script>

<script type="text/javascript">
    
    loginUser = '{{ authUserId() }}';
    var socketId = Echo.socketId();

    $(function(){
         $('.select2').select2({
            placeholder: "Select"
        });
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function keyGeneration(array_key_list){
        key = '';
        if(array_key_list.length > 0){
            key = array_key_list.join('-');
        }
        return key;
    }

   Echo.join(`online.user`)
    .listen('OnlineUser', (e) => {
        console.log(e);
        // alert(e.status+'-'+e.id);
        if( $('#'+e.id+'-user-id').length ){
            if(e.status == 'online'){
                $('#'+e.id+'-user-id').removeClass('red_icon');
                $('#'+e.id+'-user-id').addClass('green_icon');                    
            }else{
                $('#'+e.id+'-user-id').removeClass('green_icon');
                $('#'+e.id+'-user-id').addClass('red_icon');        
            }
        }
    }).joining((e) => {
        if( $('#'+e.id+'-user-id').length ){
            if(e.status == 'online'){
                $('#'+e.id+'-user-id').removeClass('red_icon');
                $('#'+e.id+'-user-id').addClass('green_icon');                    
            }else{
                $('#'+e.id+'-user-id').removeClass('green_icon');
                $('#'+e.id+'-user-id').addClass('red_icon');        
            }
        }
    });

    function countMessage(){
        var count = $('#appended-message').find('.appended-message').length;
        $('#message-count').html(count);
        return count;
    }

</script>

@include('layout.custom_js')
