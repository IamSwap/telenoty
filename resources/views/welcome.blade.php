<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" sizes="57x57" href="/images/apple-touch-icon-57x57.png"/>
        <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png"/>
        <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png"/>
        <link rel="apple-touch-icon" sizes="144x144" href="/images/apple-touch-icon-144x144.png"/>
        <link rel="apple-touch-icon" href="/images/apple-touch-icon-57x57.png"/>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>TeleNoty - Receive Laravel Forge deployment notifications on Telegram</title>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

         <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="welcome-page">
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light green-bt py-3">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ url('images/telenoty-logo-light.svg') }}" alt="TeleNoty" width="150px">
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            <li class="nav-item mr-3">
                                <a class="nav-link" href="{{ url('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-register" href="{{ url('register') }}">{{ __('Register') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8 col-md-10 my-5">
                        <div class="card">
                            <div class="card-body bg-white">
                                <h4 class="mb-3">Hello! 🎉</h4>

                                <p>Today, I am very happy to present you <strong>TeleNoty!</strong> A free tool to get your <a href="https://forge.laravel.com" target="_blank">Laravel Forge's</a> deployment notifications on <a href="https://telegram.org" target="_blank">Telegram</a>.</p>

                                <p>Laravel forge supports <a href="https://slack.com" target="_blank">Slack</a> notifications but currently lacks the ability to send notifications to Telegram. So until Taylor (Creator for Laravel Forge) &amp; team adds official implementation, please feel free to use TeleNoty for your team.</p>

                                <p>Also, I have made TeleNoty to look like Forge's interface. So that Forge user can feel it at home. Hope Taylor &amp; team won't mind bringing Forge's design to TeleNoty.</p>

                                <p>TeleNoty is an open source app &amp; source is available to browse on <a href="https://github.com/iamswap/telenoty" target="_blank">GitHub</a>. If you find any bugs, please report them by opening an issue on GitHub or feel free to submit pull request.</p>

                                <p>Hope you will find it useful. Happy coding :)</p>
                                <p>Thanks :) <br>
                                    Swapnil Bhavsar
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 border-top py-5">
                <div class="container  text-center">
                    <p>A free tool by created by <strong>Swapnil Bhavsar</strong> (<a href="https://twitter.com/swapnil_bhavsar" target="_blank">@swapnil_bhavsar</a>) &amp; <strong>Jim Shannon</strong> (<a href="https://twitter.com/jshannon63" target="_blank">@jshannon63</a>) for Laravel community!</p>
                    <p class="mb-0 font-italic" style="font-size: 1.2rem;">Made with ❤ using Laravel framework</p>
                </div>
            </footer>
        </div>
    </body>
</html>
