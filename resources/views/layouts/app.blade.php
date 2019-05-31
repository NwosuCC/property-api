<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/modal-scripts.js') }}"></script>
    {{--@stack('actions-scripts')--}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Font-awesome -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto ml-4 pt-1">
                  <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      Go to <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="{{ route('category.index') }}">
                        {{ __('Categories') }}
                      </a>

                      <a class="dropdown-item" href="{{ route('tenant.index') }}">
                        {{ __('Tenants') }}
                      </a>

                      <a class="dropdown-item" href="{{ route('applicant.index') }}">
                        {{ __('Applicants') }}
                      </a>
                    </div>
                  </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                      @if(url()->current() !== route('login'))
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                          </li>
                      @endif
                      {{--@if (Route::has('register') && url()->current() !== route('register'))
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                          </li>
                      @endif--}}
                    <!-- Authenticated User Menu -->
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

  {{-- Flash Message --}}
    @if($flash = get_flash())
        <div id="flash-message" class="alert alert-{{$flash['type']?:'success'}}" role="alert" style="position: fixed; top: 3px; right:15px; min-width: 300px; border-radius: 1px;">
            {{ __($flash['message']) }}
        </div>
        <script>
          //removeFlashDiv();
          setTimeout(function () {
            document.getElementById('flash-message').setAttribute('style', "display:none");
          }, 5000);
        </script>
    @endif

    {{-- Grab Errors into JS for Modal Form (MF) Plugin :: Possible only in blade.php file --}}
    @if($errors)
      <script>
        /* Called from modal-scripts.js MF.ext */
        const MFExt = (() => {
          return {
            getPHPErrors: () => {
              return'@php if($errors_str = json_encode( $errors->toArray() )) echo $errors_str; @endphp';
            },
            getFormOld: () => {
              return'@php if($old_str = json_encode( old() )) echo $old_str; @endphp';
            }
          };
        })();
      </script>
    @endif

    {{-- Finally, render Page --}}
    <main class="py-4">
        @yield('content')
    </main>

</div>
</body>
</html>
