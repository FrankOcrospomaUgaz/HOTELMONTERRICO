<!DOCTYPE html>
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
                    <label for="" class="labelFormato">Recupera tu cuenta</label>
                </div>
                <br>
                <div class="mb-4 text-sm text-gray-600 ">
                    {{ __('¿Olvidaste tu contraseña? Ningún problema. Simplemente háganos saber su dirección de correo electrónico y le enviaremos un enlace para que pueda loguearse.') }}

                </div>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <br>
                    <div class="group">
                        <div class="mt-4 ">

                            <x-jet-input id="email" class="input block mt-1 w-full form-control" type="email" name="email" :value="old('email')" placeholder="Correo electrónico" required autofocus />
                        </div>
                    </div>

                    <div class="group">
                        <input type="submit" class="button" value="ENVIAR">

                    </div>
                    <a class="frase-olvido-contrasena" href="{{ route('login') }}">
                        {{ __('Inciar Sesion') }}
                    </a>
                    <div class="hr"></div>
                </form>
            </div>
        </div>
    </div>
    <!-- CDN para BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

</body>

</html>