<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
    <title>DriverBoss-GPS</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ asset('home/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/et-line-font.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/nivo-lightbox.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/nivo_themes/default/default.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/style.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css'>
    <style>
        /* Responsive Styles */
        .navbar-toggle {
            display: none;
        }
        @media (max-width: 467px) {
            .navbar-toggle {
                display: block;
            }
            .navbar-collapse {
                display: none;
            }
            .navbar-collapse.in {
                display: block !important;
            }
        }
        @media (max-width: 691px) {
            .navbar-header {
                float: none;
            }
            .navbar-toggle {
                display: block;
                float: right;
            }
            .navbar-collapse {
                border-top: 1px solid transparent;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            .navbar-collapse.collapse {
                display: none!important;
            }
            .navbar-nav {
                float: none!important;
                margin-top: 10.5px;
            }
            .navbar-nav > li {
                float: none;
            }
            .navbar-nav > li > a {
                padding-top: 10px;
                padding-bottom: 10px;
            }
        }
        @media (max-width: 768px) {
            #home .container .row .col-md-16,
            #work .container .row .col-md-4,
            #contact .container .row .col-md-6,
            #contact .container .row .col-md-12 {
                text-align: center;
                margin-bottom: 20px;
            }
        }
        /* Adjust font sizes for smaller screens */
        @media (max-width: 468px) {
            h1.heading {
                font-size: 2em;
            }
            h2.heading {
                font-size: 1.75em;
            }
            h3 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body data-spy="scroll" data-target=".navbar-collapse" data-offset="70">

    <!-- preloader section -->
    <div class="preloader">
        <div class="sk-spinner sk-spinner-circle">
            <div class="sk-circle1 sk-circle"></div>
            <div class="sk-circle2 sk-circle"></div>
            <div class="sk-circle3 sk-circle"></div>
            <div class="sk-circle4 sk-circle"></div>
            <div class="sk-circle5 sk-circle"></div>
            <div class="sk-circle6 sk-circle"></div>
            <div class="sk-circle7 sk-circle"></div>
            <div class="sk-circle8 sk-circle"></div>
            <div class="sk-circle9 sk-circle"></div>
            <div class="sk-circle10 sk-circle"></div>
            <div class="sk-circle11 sk-circle"></div>
            <div class="sk-circle12 sk-circle"></div>
        </div>
    </div>

    <!-- navigation section -->
    <section class="navbar navbar-fixed-top custom-navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>
                <a href="#home" class="navbar-brand">
                    <img src="{{ asset('home/images/logo.png')}}" alt="Logo" width="140" height="45">
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#home" class="smoothScroll">Home</a></li>
                    <li><a href="#work" class="smoothScroll">Servicios</a></li>
                    <li><a href="#contact" class="smoothScroll">Contactanos</a></li>
                    @if($auth)
                        <li><a href="{{route('dashboard')}}" class="smoothScroll">Dashboard</a></li>
                    @else
                        <li><a href="{{route('login')}}" class="smoothScroll">Login</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    <!-- home section -->
    <section id="home">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <h3>ADMINISTRA Y MONITOREA</h3>
                    <h2>TU FLOTA DE AUTOS</h2>
                    <hr>
                    <a href="#work" class="smoothScroll btn btn-danger">Servicios</a>
                    <a href="#contact" class="smoothScroll btn btn-default">Contactanos</a>
                </div>
            </div>
        </div>
    </section>

    <!-- work section -->
    <section id="work">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <div class="section-title">
                        <h1 class="heading bold">SERVICIOS</h1>
                        <hr>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.6s">
                    <i class="icon-cloud medium-icon"></i>
                    <h3>Informes y análisis</h3>
                    <hr>
                    <p>Proporciona informes y estadísticas en tiempo real o informes diarios y semanales predefinidos, datos efectivo para la toma de decisiones comerciales.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.9s">
                    <i class="icon-mobile medium-icon"></i>
                    <h3>Monitoreo GPS</h3>
                    <hr>
                    <p>Garantizar el cumplimiento de rutas especificadas y zonas predeterminadas, mejorando la eficiencia operativa y la seguridad de los activos.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="1s">
                    <i class="icon-laptop medium-icon"></i>
                    <h3>Monitoreo Conductor</h3>
                    <hr>
                    <p>Incluyendo exceso de velocidad, aceleraciones y frenadas bruscas, curvas cerradas y ralentí innecesario, para promover hábitos de conducción más seguros y reducir los riesgos operativos.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="1.3s">
                    <i class="icon-compass medium-icon"></i>
                    <h3>Notificaciones en vivo</h3>
                    <hr>
                    <p>Garantice la seguridad proporcionando monitoreo y alertas en tiempo real sobre posibles riesgos o incidentes, mejorando la seguridad de activos y vehículos.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="1.6s">
                    <i class="icon-chat medium-icon"></i>
                    <h3>Administracion</h3>
                    <hr>
                    <p>Administrar subcuentas, dispositivos, tarjetas SIM IOT y duración del servicio, todo dentro de una sola plataforma.</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="1.9s">
                    <i class="icon-browser medium-icon"></i>
                    <h3>Análisis de datos</h3>
                    <hr>
                    <p>análisis de datos inteligente para mejorar la eficiencia, como estadísticas de kilometraje para reducir los costos de combustible, etc.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- contact section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <div class="section-title">
                        <h1 class="heading bold">Contactanos</h1>
                        <hr>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h2 class="heading bold">¿ Quieres adquirir nuestros servicio ?</h2>
                    <p>Contacta a nuestro equipo de ventas, escribenos y te enviaremos una Cotización en menos de 24 horas.</p>
                    <div class="contact-info">
                        <div class="col-md-6 col-sm-4">
                            <h3><i class="icon-envelope medium-icon wow bounceIn" data-wow-delay="0.6s"></i> E-Mail</h3>
                            <p>ventas@autocitypanama.com</p>
                        </div>
                        <div class="col-md-6 col-sm-4">
                            <h3><i class="icon-phone medium-icon wow bounceIn" data-wow-delay="0.6s"></i> Telefono</h3>
                            <p>507-6276-2174</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <form action="#" method="get" class="wow fadeInUp" data-wow-delay="0.6s">
                        <div class="col-md-6 col-sm-6">
                            <input type="text" class="form-control" placeholder="Nombre" name="Nombre" required>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <input type="email" class="form-control" placeholder="Email" name="email" required>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <textarea class="form-control" placeholder="Descripción" rows="7" name="Descripción" required></textarea>
                        </div>
                        <div class="col-md-offset-4 col-md-8 col-sm-offset-4 col-sm-8">
                            <input type="submit" class="form-control" value="ENVIAR">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <img src="{{ asset('home/images/logo.png')}}" alt="Logo" width="250" height="90">
                    <p>Copyright © Admin Tracker | Design:<a href="https://tangerinexpress.com" target="_parent">Tangerine Express 2024</a></p>
                    <hr>
                    <ul class="social-icon">
                        <li><a href="#" class="fa fa-facebook wow fadeIn" data-wow-delay="0.3s"></a></li>
                        <li><a href="#" class="fa fa-twitter wow fadeIn" data-wow-delay="0.6s"></a></li>
                        <li><a href="#" class="fa fa-instagram wow fadeIn" data-wow-delay="0.3s"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('home/js/jquery.js')}}"></script>
    <script src="{{ asset('home/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('home/js/smoothscroll.js')}}"></script>
    <script src="{{ asset('home/js/isotope.js')}}"></script>
    <script src="{{ asset('home/js/imagesloaded.min.js')}}"></script>
    <script src="{{ asset('home/js/nivo-lightbox.min.js')}}"></script>
    <script src="{{ asset('home/js/jquery.backstretch.min.js')}}"></script>
    <script src="{{ asset('home/js/wow.min.js')}}"></script>
    <script src="{{ asset('home/js/custom.js')}}"></script>

</body>
</html>