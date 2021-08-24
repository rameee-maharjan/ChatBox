 @if($paginationLinks && $paginationLinks->nextPageUrl()) <a class="load-more-pagination" href="{{ $paginationLinks->nextPageUrl()}}">Load More</a>@endif

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
        <div class="incoming_msg_img chat_img"> <img src="{{ pathUserImage($message->profile_photo_path) }}">
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