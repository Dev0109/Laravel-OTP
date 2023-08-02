<div class="main-card">
    <div class="header">
        <div class="row">
            <div class="col-md-12">
                <select class="form-control" id="userlist" name="userlist">
                    @if($users)
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="body">
        <div id="userTestFrame">

        </div>
    </div>
</div>