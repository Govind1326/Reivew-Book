@extends('layouts.app')
@section('title')
User Profile 
@endsection
@section('main')

    <div class="container">
        <div class="row my-5">
            @include('layouts.sidebar')
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Profile
                    </div>
                    <div class="card-body">
                        <form action="{{route('account.updateProfile')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror" name="name" id="name" />
                            @error('name')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Email</label>
                            <input type="email" value="{{$user->email}}" class="form-control @error('email') is-invalid @enderror"name="email" id="email"/>
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Image</label>
                            <input type="file" name="image" id="image" value="" class="form-control @error('image') is-invalid @enderror">
                            {{-- <img src="../{{$user->image}}" height="300" alt="Luna John" > --}}
                            @error('image')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>   
                        <button class="btn btn-primary mt-2" disabled id='updateButton'>Update</button>                     
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let originalName = "{{ $user->name }}";
                                let originalEmail = "{{ $user->email }}";
                                let updateButton = document.getElementById('updateButton');
                        
                                let nameInput = document.getElementById('name');
                                let emailInput = document.getElementById('email');
                                let imageInput = document.getElementById('image');                        
                                function checkForChanges() {
                                    let nameChanged = nameInput.value !== originalName;
                                    let emailChanged = emailInput.value !== originalEmail;
                                    let imageChanged = imageInput.value !== '';
                        
                                    if (nameChanged || emailChanged || imageChanged) {
                                        updateButton.disabled = false;
                                    } else {
                                        updateButton.disabled = true;
                                    }
                                }
                        
                                nameInput.addEventListener('input', checkForChanges);
                                emailInput.addEventListener('input', checkForChanges);
                                imageInput.addEventListener('change', checkForChanges);
                            });
                        </script>
                    </div>
                </div>                
            </div>
        </div>       
    </div>
    @endsection