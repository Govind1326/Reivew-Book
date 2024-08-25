<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    </head>
    <body class="bg-light">
        <header>
            <div class="container-fluid shadow-lg header">
                <div class="container">
                    <div class="d-flex justify-content-between">
                        <h2 class="text-center"><a href="/" class="h3 text-white text-decoration-none">Book Review App</a></h3>
                        <div class="d-flex align-items-center navigation">
                            @php $path = request()->path(); @endphp
                            @if(!Auth::user())
                            <a href="{{route('account.register')}}" class="text-white ps-2">Register</a>
                            <a href="{{route('account.login')}}" class="text-white ps-2">Login</a>
                            @elseif(Auth::user())
                            {{-- <a href="{{route('account.profile')}}" class="text-white ps-2">{{Auth::user()->name}}</a> --}}
                            <button class="btn btn-transparent text-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{Auth::user()->name}}
                              </button>
                              <ul class="dropdown-menu">
                                <li><a href="{{route('account.profile')}}" class="text-dark ps-2"><i class="fa fa-user" aria-hidden="true"></i> Account</a></li>
                                <hr>
                                <li><a id="logout" class="text-dark ps-2"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
                              </ul>
                            {{-- <a href="{{route('account.logout')}}" class="text-white ps-2"><i class="fa fa-sign-out" aria-hidden="true"> logout</i></a> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
        @yield('main')
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        @yield('script')
        <script>
            $(document).ready(function(){
                $('#logout').click(function(){
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
        @yield('common_script')
    </body>
</html>