@extends('layout.master')
@push('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css\message.css') }}"> --}}
<style>
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
                <h4>Recent</h4>
            </div>
            <div class="srch_bar">
                <div class="stylish-input-group">
                    <input type="text" class="search-bar user-search"  placeholder="Search" >
                    <span class="input-group-addon">
                        <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </span> </div>
                </div>
            </div>
            <div class="inbox_chat">
                @foreach($messages as $message)
                <div class="chat_list @if($message->is_read == '0' && $message->msg_direction == 'in') unread @endif" id="{{ keyGeneration([$message->receiver_id, 
                    $message->message_type,
                    'summary-id']) }}">
                    <div class="chat_people" 
                    onclick="getAjaxViewForMessage('{{ $message->receiver_id}}', 
                    '{{ $message->message_type}}')">
                        <div class="chat_img"> <img class="h-8 w-8 rounded-full object-cover" src="{{ pathUserImage($message->profile_photo_path) }}"> </div>
                        <div class="chat_ib">
                            <h5>{{ $message->receiver_name }} <span class="chat_date">{{ dateInText($message->created_at, true) }}</span></h5>
                            <p>{!! ( $message->msg_direction == 'out' ) ? 'You : '.Str::limit($message->message, 25, '...') : Str::limit($message->message, 25, '...')!!}</p>
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
        
        $(".user-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".chat_list").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

    </script>
    @endpush