<form method="post" action="{{ route('users.groups.update', $group->id) }}" enctype="multipart/form-data">
    {{ csrf_field() }}<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Group Name </label>
                <input type="text" name="name" value="{{$group->name }}" class="form-control" required="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Members </label>
                <select required class="group-select2 form-control col-md-12" name="users[]"  style="width: 100%" multiple >
                    <option value="">Select Members</option>
                    @foreach($globalUserLists as $groupUserData)
                    <option value="{{ $groupUserData->id }}" {{ in_array($groupUserData->id, $user_groups) ? 'selected': ''}}>{{ $groupUserData->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <input type="file" name="profile_photo_path">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
    </div>
</form>
<script type="text/javascript">
    $('.group-select2').select2({
        placeholder: "Select group members",
        allowClear: true
    });
</script>