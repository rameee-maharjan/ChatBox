<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\MOdels\User;
use App\Modules\UserGroups\Models\Group;
use App\Modules\UserGroups\Models\GroupMessageHistory;
use App\Modules\Chat\Models\MessageHistory;

class GroupUsersComposer
{
    
    public function compose(View $view)
    {
       $list = Group::allGroupUsersList( authUser() ); 
       
       $message_unread_count = MessageHistory::unreadMessageCount(authUserId());
       $message_unread_array = MessageHistory::unreadMessage(authUserId());

       $group_message_unread_count = GroupMessageHistory::unreadMessageCount(authUserId());
       $group_message_unread_array = GroupMessageHistory::unreadMessage(authUserId());
       $total_unread_count = ($message_unread_count + $group_message_unread_count)?($message_unread_count + $group_message_unread_count):'';
       $view->with('globalUserLists', $list)
            ->with('globalTotalUnreadCount', $total_unread_count)
            ->with('globalMessageUnreadArray', $message_unread_array)
            ->with('globalGroupMessageUnreadArray', $group_message_unread_array)

            ;
    }
}
