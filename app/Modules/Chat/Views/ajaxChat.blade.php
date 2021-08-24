<div class="chat-user-title">
    <h4 style="margin-bottom: 25px">
        <div style="float: left; width:5%">
        <img class="h-8 w-8 rounded-full object-cover" height="50px"
            src="{{ userImage($chat_user_object) }}">  
        </div>
        <div style="font-weight: bolder">
            {{ $chat_user_object->name }}
        </div>
    </h4>
    <div class="clearfix"></div>
    <hr>
</div>

<div class="msg_history individual">

@if($paginationLinks && $paginationLinks->nextPageUrl()) <a  class="load-more-pagination" href="{{ $paginationLinks->nextPageUrl()}}">Load More</a>@endif


   {{-- @if($message_type == 'individual') --}}
    @foreach($messages as $message)
    @if($message->sender_id == authUserId())
    <div class="outgoing_msg">
        <div class="sent_msg">
            <p>{{ $message->message }}</p>
            <span class="time_date"> {{ chatDateFormat($message->created_at) }}</span>
        </div>
    </div>
    @else
    <div class="incoming_msg">
        <div class="incoming_msg_img "> <img class="h-8 w-8 rounded-full object-cover" src="{{ pathUserImage($message->profile_photo_path) }}">
        </div>
        <div class="received_msg">
            <div class="received_withd_msg">
                @if($message_type == 'group')
                <span>{{ $message->sender }}</span>
                @endif
                <p>{{ $message->message }}</p>
                <span class="time_date"> {{ chatDateFormat($message->created_at) }} </span>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    {{-- @elseif($message_type == 'group')
        @foreach($messages as $message)
            
            <div class="incoming_msg">
                <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                </div>
                <div class="received_msg">
                    <div class="received_withd_msg">
                        <span>{{ $message->sender }}</span>
                        <p>{{ $message->message }}</p>
                        <span class="time_date"> {{ chatDateFormat($message->created_at) }} </span>
                    </div>
                </div>
            </div>
            
        @endforeach
    @endif --}}
</div>
 <div class="type_msg">
    <div class="input_msg_write">
        <form method="post" id="chat-message-form">
            <input type="text" name="message" class="write_msg" placeholder="Type a message" />
            <input type="hidden" name="message_type" value="{{ $message_type }}">
            <input type="hidden" name="user_id" value="{{ $user_id }}">
            <input type="hidden" name="element" value="{{ $user_id.'-'.$message_type.'-id' }}">
            <button type="submit" class="msg_send_btn chat-message-submit" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
        </form>
    </div>
</div>