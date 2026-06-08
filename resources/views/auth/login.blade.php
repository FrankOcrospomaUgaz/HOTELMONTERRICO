{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <title>Login</title>
</head>

<body>
    <div class="login-wrap">
        <div class="login-html ">

            <div class="login-form">
                <div class="group">
                    <label for="" class="labelFormato">INICIAR SESION</label>
                </div>
                <br>
                <br>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="group">
                        <div class="mt-4">
                            <x-jet-input id="username" class="input block mt-1 w-full form-control" type="text"
                                name="username" :value="old('username')" placeholder="Usuario" required autofocus />
                        </div>
                    </div>
                    <div class="group">
                        <div class="mt-4 ">

                            <x-jet-input id="password" class="input block mt-1 w-full form-control" type="password"
                                name="password" placeholder="Contraseña" required autocomplete="current-password" />
                        </div>
                    </div>

                    <div class="group">
                        <input type="submit" class="button" value="ENVIAR">
                        @if (Route::has('password.request'))
                            <a class="frase-olvido-contrasena" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif

                    </div>

                    <div class="hr"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- CDN para BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>

  

</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <title>Login</title>
    <link type="image/png" href="/proyectoHotel/public/imagesComunes/Monterrico.jpg" rel="icon">
</head>

<body>

    <div class="parallax">
    <div class="login-wrap">
        <H3 for="" class="labelFormato" style="font-size:40px">SUIT MONTERRICOS</H3>
        <div class="login-html ">



            <div class="login-form">
                <br>
                <div class="group">
                    <label for="" class="labelFormato">INICIAR SESION</label>
                </div>
                <br>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="group">

                        <x-jet-input id="username" class="input block mt-1 w-full" type="text" name="username"
                            :value="old('username')" placeholder="Usuario" required autofocus />
                    </div>

                    <div class="group">


                        <x-jet-input id="password" class="input block mt-1 w-full" type="password" name="password"
                            placeholder="Contraseña" required autocomplete="current-password" />

                    </div>
                    <br>
                    <div class="group">
                        <input type="submit" class="button" value="ENVIAR">
                        <br>
                        @if (Route::has('password.request'))
                            <a class="frase-olvido-contrasena" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif

                    </div>
                    <br>
                    <div class="hr"></div>
                </form>
            </div>
        </div>
    </div></div>
    <!-- CDN para BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous">
    </script>
    <!--jQuery [ REQUIRED ]-->
    <script src="plantillaNuevo\js\jquery.min.js"></script>

    <script>
        $(".input").on('input', function() {
            var valorInput = $(this).val();

            if (valorInput !== '') {
                $(this).css('background-color', '#fff');
            } else {
                $(this).css('background-color', 'rgb(255 255 255 / 50%)');
            }

        });

    </script>


</body>

</html>
