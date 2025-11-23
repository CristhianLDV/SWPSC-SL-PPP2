<!doctype html>
<html lang="es">
    <head>
        <title>Acceso denegado — Permisos insuficientes</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <div id="fondo">
                <div class="fondo-bg"></div>
               
                <div class="error">
                    <div>
                        <i class="{{ $iconError }}"></i>
                    </div>
                    <div class="error-404">
                        <h1>{{ $numError }}</h1>
                    </div>
                    <h2>{{ $messageError }}</h2>
                    <a href="{{ filament()->getHomeUrl() }}" class="btn-inicio">Inicio</a>

                  {{--   <a href="#" class="btn-contacto">Contactos</a> --}}
                    <div class="container">
                        <div class="row mt-3">
                            {{-- <div class="col">
                                <span>Desing by <strong><a href="https://neocenter.shop" class="text-white text-decoration-none">Neocenter</a></strong></span>
                            </div> --}}
                        </div>
                    </div>
                </div>
                
            </div>
           
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
