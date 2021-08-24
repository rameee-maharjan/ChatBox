@extends('layout.master')
@push('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css\message.css') }}"> --}}
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css\user.css') }}"> --}}
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
                <h4>Groups</h4>
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
                @foreach($groups as $group)
                <div class="chat_list" id="{{ keyGeneration([$group->id, 
                    'group',
                    'summary-id']) }}">
                    <div class="chat_people" 
                    onclick="getAjaxViewForMessage('{{ $group->id}}', 
                    'group')">
                        <div class="chat_img"> <img src="{{ pathUserImage($group->profile_photo_path) }}" >  </div>
                        <div class="chat_ib">
                            <h5 class="user-name">  {{ $group->name}} 
                                {{-- <span class="chat_date">asdasd</span> --}}
                            </h5>
                            <p>{{ $group->users_count }} Members</p>
                        </div>
                    </div>
                    @if( $group->canEditDelete(authUser()) || $group->canLeave(authUser()) )
                    <div class="group-edit">
                        <div class="dropdown">
                              <span class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Edit
                              </span>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                @if($group->canEditDelete(authUser()) )
                                <button class="dropdown-item" data-route="{{ route('users.groups.edit', $group->id) }}" data-method='group-edit' data-group="{{ $group->id }}" type="button">Edit</button>
                                <button class="dropdown-item" data-route="{{ route('users.groups.delete', $group->id) }}" data-method='group-delete' data-group="{{ $group->id }}" type="button">Delete</button>
                                @endif
                                @if($group->canLeave(authUser()))
                                <button class="dropdown-item" data-route="{{ route('users.groups.leave', $group->id) }}" data-method='group-leave' data-group="{{ $group->leave }}" type="button">Leave</button>
                                @endif
                              </div>
                            </div>
                    </div>
                    @endif
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

            $('.user-list-section').find('.inbox_people').first().trigger('click');

            $(".user-search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".chat_list").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('[data-method=group-edit]').click(function(event) {
                var group = $(this).data('group');
                var url = $(this).data('route');
                getAjaxModal(url, {}, 'Update Group');
            });

            $('[data-method=group-delete]').click(function(event) {
                var url = $(this).data('route');
                var callback = function(){
                    toastMessage('Success !!!','success', 'Group deleted successfully'); 
                    location.href = '{{ route('users.groups.index') }}';
                }
                var confirm = function(){
                    ajaxCalls(url, {}, 'post', callback) ;
                }
                confirmAlert(confirm);
                    
            });

            $('[data-method=group-leave]').click(function(event) {
                var url = $(this).data('route');
                var callback = function(){
                    toastMessage('Success !!!','success', 'Group leaved successfully'); 
                    location.href = '{{ route('users.groups.index') }}';
                }
                var confirm = function(){
                    ajaxCalls(url, {}, 'post', callback) ;
                }
                confirmAlert(confirm);
                    
            });

        });

    </script>

    @endpush