@extends('layouts.app')
@section('title')
Users
@endsection
@section('main')   
    <div class="container">
        <div class="row my-5">
            @include('layouts.sidebar')
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Users
                    </div>
                    <div class="card-body pb-0">
                        <table class="table table-striped mt-3">
                            <thead class="table-primary">
                                <tr>
                                    <th>User id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created at</th>                                  
                                    <th>Deleted</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                                <tbody>
                                    @if ($allusers->isNotEmpty())
                                    @foreach ($allusers as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->created_at->format('M j, Y')}}</td>
                                        {{-- <td>{{$user->deleted}}</td> --}}
                                        {{-- <td class="{{$review->status?'text-success':'text-danger'}}" >{{$review->status?'Active':'Inactive'}}</td> --}}
                                        <form action="" method="post" id="user_form_{{ $user->id }}">
                                            @csrf
                                            <input type="hidden" name="id" name="id" value="{{$user->id}}">
                                        <td>
                                            <select name="deleted" class="deleted-select form-control {{$user->deleted?'text-success':'text-primary'}}" {{Auth::user()->id==$user->id?'disabled':''}} id="deleted-select" data-user-id="{{ $user->id }}">
                                                <option value="1" class="text-success" {{ $user->deleted ? 'selected' : '' }}>Yes</option>
                                                <option value="0" class="text-primary" {{ !$user->deleted ? 'selected' : '' }}>No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="role" class="role-select form-control {{$user->role=='admin'?'text-success':'text-primary'}}" {{Auth::user()->id==$user->id?'disabled':''}} id="role-select" data-user-id="{{ $user->id }}">
                                                <option value="admin" class="text-success" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="user" class="text-primary" {{ $user->role=='user' ? 'selected' : '' }}>User</option>
                                            </select>
                                        </td>
                                        </form>
                                        <td align="center">
                                            <a onclick="deleteu({{$user->id}})" class="btn btn-danger btn-sm {{Auth::user()->id==$user->id?'disabled':''}}"><i class="{{Auth::user()->id==$user->id?'fa fa-ban':'fa-solid fa-trash'}}"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <td colspan="6">User not found.</td>
                                    @endif
                                </tbody>
                            </thead>
                        </table>   
                        <nav aria-label="Page navigation " >
                            {{$allusers->links()}}
                          </nav>                  
                    </div>
                </div>                
            </div>
        </div>       
    </div>
    @endsection
    @section('script')
    <script>
        $(document).ready(function(){
            $('.role-select, .deleted-select').on('change', function () {
                var userId = $(this).data('user-id');
                var form = $('#user_form_' + userId);
            $.ajax({
                url: '{{ route("user.update") }}',
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href = '{{ route("account.users") }}';
                        },
                error: function(response) {
                    window.location.href = '{{ route("account.users") }}';
                        }
            });
        });
    });
            function deleteu(id){
                if (confirm('Are you sure you want to delete?')) {
                    $.ajax({
                        url: '{{ route("user.delete") }}',
                        type: 'DELETE',
                        data: { id: id },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("account.users") }}';
                        },
                        error: function(response) {
                            window.location.href = '{{ route("account.users") }}';
                        }
                    });
                }
            }
    </script>
    @endsection