<div class="container master-edit-register"><br>
    <div class="col-md-6 col-md-offset-3">
        <h2 class="text-center">FORM EDIT USER</h2>
        <hr>
        @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <h3 id="message"></h3>
        <form action="#" id="editRegisterForm" method="post">
            @csrf
            <div class="form-group">
                <label><i class="fa fa-envelope"></i> Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-user"></i> Username</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-key"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <div class="form-group">
                <label><i class="fa fa-address-book"></i> Role</label>
                <input type="text" name="roles" id="roles" class="form-control" value="{{ $user->roles }}" readonly>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user"></i> Update</button>
            <hr>
            <p class="text-center">Kembali ke <a href="#">Dashboard</a></p>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    $(document).on('submit', '#editRegisterForm', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('updateregister') }}', // Route to handle form submission
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#message').html('<p>' + response.pesan + '</p>');
                if (response.pesan === 'Update Berhasil.') {
                    loadEditForm(); // Reload form after successful update
                }
            },
            error: function(response) {
                $('#message').html('<p>' + response.responseJSON.pesan + '</p>');
            }
        });
    });
});
</script>

