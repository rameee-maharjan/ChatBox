<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Chat Box</title>
        @include('layout.meta')
        @stack('css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @livewireStyles

        
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <div class="messaging">
                @section('content')
                @show
            </div>
        </div>

        <div class="footer">

            <div class="modal fade" id="create-group-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="{{ route('users.groups.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Create New Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Group Name </label>
                                            <input type="text" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Members </label>
                                            <select class="select2 form-control" name="users[]"  style="width: 100%" multiple>
                                                @foreach($globalUserLists as $groupUserData)
                                                <option value="{{ $groupUserData->id }}">{{ $groupUserData->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="file" name="profile_photo_path">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Create Group</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ajax-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">                        
                        <div class="modal-header">
                            <h5 class="modal-title" id=""></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-ajaxview">
                        </div>                        
                    </div>
                </div>
            </div>

        </div>        
        @include('layout.scripts')
        @stack('scripts')
    </body>
</html>