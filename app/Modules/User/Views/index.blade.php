@extends('layout.master')
@push('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css\message.css') }}"> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css\user.css') }}"> --}}
<style>
.green_icon {
    background:green;
    position: absolute;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin: 0 0 0 -10px;
}

.red_icon {
    background:red;
    position: absolute;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin: 0 0 0 -10px;
}

a.load-more-pagination{
  margin-left: 45% !important;
}
</style>
@endpush
@section('content')
<div class="inbosx_msg">
    <div class="inbox_people">
        <div class="headind_srch">
            <div class="recent_heading">
                <h4>Users</h4>
            </div>
            <div class="srch_bar">
                <div class="stylish-input-group">
                    <input type="text" class="user-search"  placeholder="Search" >
                    <span class="input-group-addon">
                        <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </span> </div>
                </div>
            </div>
            <div class="user-list-section">
                @foreach($users as $user)
                <div class="chat_list" id="{{ keyGeneration([$user->id, 
                    'individual',
                    'summary-id']) }}">
                    <div class="chat_people" 
                    onclick="getAjaxViewForMessage('{{ $user->id}}', 
                    'individual')">
                        <div class="chat_img"> 
                            @php $online_status = ($user->online_status == 'online')?'green_icon':'red_icon' @endphp
                            <span id="{{ $user->id}}-user-id" class="{{$online_status}}">
                            </span>
                            <img src="{{ userImage($user) }}">  </div>
                        <div class="chat_ib">
                            <h5 class="user-name">  {{ $user->name}} 
                                {{-- <span class="chat_date">asdasd</span> --}}
                            </h5>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                    
                </div>
                @endforeach
            </div>
        </div>
        <div class="mesgs message-content-section">       
           
        </div>
    </div>

    @stop
    @push('scripts')
    @include('Chat::messageScripts')

    <script type="text/javascript">

        $(document).ready(function(){
          $(".user-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".chat_list").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
        
    </script>

    @endpush