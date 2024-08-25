<div class="col-md-3">
    <div class="card border-0 shadow-lg">
        <div class="card-header  text-white">
            {{-- Welcome, {{ implode(' ', array_slice(explode(' ', Auth::user()->name), 0, 2))}}             --}}
            Welcome, {{ @ucfirst(Auth::user()->role)}}            
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <img src="../{{Auth::user()->image}}" class="img-fluid rounded-circle" alt="Profile image">                            
            </div>
            <div class="h5 text-center">
                <strong>{{ implode(' ', array_slice(explode(' ', Auth::user()->name), 0, 2))}}</strong>
                <p class="h6 mt-2 text-muted">({{$reviewCount<2?$reviewCount.' Review':$reviewCount.' Reviews'}})</p>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-lg mt-3">
        <div class="card-header  text-white">
            Navigation
        </div>
        <div class="card-body sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/">Home</a>                               
                </li>
                @if (Auth::user()->role=='admin')
                <li class="nav-item">
                    <a href="{{route('account.users')}}">Users</a>                               
                </li>
                <li class="nav-item">
                    <a href="{{route('books.index')}}">Books</a>                               
                </li>
                <li class="nav-item">
                    <a href="{{route('reviews.index')}}">Reviews</a>                               
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{route('account.profile')}}">Profile</a>                               
                </li>
                <li class="nav-item">
                    <a href="{{route('myreviews.index')}}">My Reviews</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('account.passwordPage')}}">Change Password</a>
                </li> 
                <li class="nav-item">
                    {{-- <a href="{{route('account.logout')}}" id="logout">Logout</a> --}}
                    <a id="sidebarlogout">Logout</a>
                </li>                           
            </ul>
        </div>
    </div>
</div>
@section('common_script')
<script>
    $(document).ready(function(){
        $('#sidebarlogout').click(function(){
            if(confirm("Are you sure you want to logout🥲?"))
            $.ajax({
                url:"{{route('account.logout')}}",
                data:"",
                type:"GET",
                success:function(){
                    window.location.href = "{{ route('account.login') }}";
                }
            });
        });
    });
</script>
@endsection