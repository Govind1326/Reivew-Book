@extends('layouts.app')
@section('title')
Change Password
@endsection
@section('main')

    <div class="container">
        <div class="row my-5">
            @include('layouts.sidebar')
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Change Password
                    </div>
                    <div class="card-body">
                        <form action="{{route('account.changePassword')}}" method="POST">
                            @csrf
                        <div class="mb-3">
                            <label for="password_old" class="form-label">Old Password</label>
                            <input type="password" class="form-control @error('password_old') is-invalid @enderror" name="password_old" id="password_old" />
                            @error('password_old')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>   
                        <div class="mb-3">
                            <label for="changepassword" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('changepassword') is-invalid @enderror" name="changepassword" id="changepassword" />
                            @error('changepassword')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>   
                        <button class="btn btn-primary mt-2" disabled id='updateButton'>Change</button>                     
                        </form>
                        <script>
                            $(document).ready(function() {
                                $('#password_old, #changepassword').on('input',function() {
                                    var oldPassword = $('#password_old').val().trim();
                                    var newPassword = $('#changepassword').val().trim();
                                    var $updateButton = $('#updateButton');
                                    // Enable the button only if both fields have values
                                    if (oldPassword !== '' && newPassword !== '') {
                                        $updateButton.prop('disabled', false);
                                    } else {
                                        $updateButton.prop('disabled', true);
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>                
            </div>
        </div>       
    </div>
    @endsection