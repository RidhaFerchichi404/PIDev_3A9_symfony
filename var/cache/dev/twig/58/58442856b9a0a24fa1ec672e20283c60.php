<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* front.html.twig */
class __TwigTemplate_bb2aa31b60fce91682cb2531893d4f9a extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
            'javascripts' => [$this, 'block_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "front.html.twig"));

        // line 1
        yield "<!DOCTYPE html>
<html lang=\"zxx\">

<head>
    <meta charset=\"UTF-8\">
    <meta name=\"description\" content=\"Sportify Template\">
    <meta name=\"keywords\" content=\"Sportify, Gym, Fitness, HTML Template\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>";
        // line 10
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        yield "</title>

    <script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>

    <!-- Google Fonts -->
    <link href=\"https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap\" rel=\"stylesheet\">
    <link href=\"https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap\" rel=\"stylesheet\">

    <!-- CSS Styles -->
    <link rel=\"stylesheet\" href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/bootstrap.min.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 20
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/font-awesome.min.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/flaticon.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/owl.carousel.min.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/barfiller.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/magnific-popup.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/slicknav.min.css"), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("css/style.css"), "html", null, true);
        yield "\">
    
    <!-- FontAwesome if not included already -->
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css\" rel=\"stylesheet\">
    
    <style>
        :root {
            --dark-color: #0a0a0a;
            --dark-secondary: #151515;
            --accent-color: #ff7700;
            --accent-hover: #ff9133;
            --text-color: #d2d2d2;
            --text-muted: #7a7a7a;
            --error-color: #ff3b30;
        }
        
        body {
            background-color: var(--dark-color);
            color: var(--text-color);
        }
        
        /* Header styling */
        .header-section {
            background-color: var(--dark-secondary);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .nav-menu a {
            color: var(--text-color) !important;
        }
        
        .nav-menu a:hover, .nav-menu .active a {
            color: var(--accent-color) !important;
        }
        
        .nav-menu .dropdown {
            background-color: var(--dark-secondary);
            border: 1px solid #222;
        }
        
        /* Buttons styling */
        .primary-btn {
            background-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .primary-btn:hover {
            background-color: var(--accent-hover) !important;
        }
        
        /* Footer styling */
        .footer-section {
            background-color: var(--dark-secondary) !important;
            color: var(--text-color);
        }
        
        .footer-section h4, .footer-section h6 {
            color: var(--accent-color) !important;
        }
        
        .footer-section a {
            color: var(--text-color) !important;
        }
        
        .footer-section a:hover {
            color: var(--accent-color) !important;
        }
        
        /* Card and section styling */
        .card, .section-title, .team-item, .class-item, .blog-item {
            background-color: var(--dark-secondary) !important;
            color: var(--text-color) !important;
            border: 1px solid #222 !important;
        }
        
        .card-title, h1, h2, h3, h4, h5, h6 {
            color: var(--accent-color) !important;
        }
        
        .btn-primary {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--accent-hover) !important;
            border-color: var(--accent-hover) !important;
        }
        
        .btn-outline-primary {
            color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .text-primary {
            color: var(--accent-color) !important;
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        /* Table styling */
        .table {
            color: var(--text-color);
        }
        
        .table thead th {
            background-color: var(--dark-secondary);
            border-color: #222;
        }
        
        .table td, .table th {
            border-color: #222;
        }
        
        /* Form elements */
        .form-control {
            background-color: #181818;
            border: 1px solid #333;
            color: var(--text-color);
        }
        
        .form-control:focus {
            background-color: #1a1a1a;
            border-color: var(--accent-color);
            color: var(--text-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 119, 0, 0.25);
        }
        
        .input-group-text {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        /* Hero section */
        .hero-section .hi-text span {
            color: var(--accent-color);
        }
        
        .hero-section .hi-text h1 {
            color: var(--text-color) !important;
        }
        
        .hero-section .hi-text h1 strong {
            color: var(--accent-color) !important;
        }
    </style>
    
    ";
        // line 182
        yield from $this->unwrap()->yieldBlock('stylesheets', $context, $blocks);
        // line 183
        yield "</head>

<body>
    <div id=\"preloder\">
        <div class=\"loader\"></div>
    </div>

    <!-- Header Section Begin -->
    <header class=\"header-section\">
        <div class=\"container-fluid\">
            <div class=\"row\">
                <div class=\"col-lg-3\">
                    <div class=\"logo\">
                        <img src=\"";
        // line 196
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("img/logo.png"), "html", null, true);
        yield "\" alt=\"Sportify Logo\">
                    </div>
                </div>
                <div class=\"col-lg-6\">
                    <nav class=\"nav-menu\">
                        <ul>
                            <li><a href=\"#\">About Us</a></li>
                            <li><a href=\"#salles-de-sport\">Gym</a></li>
                            <li><a href=\"#\">Services</a></li>
                            <li><a href=\"#\">Our Team</a></li>
                            <li><a href=\"#\">Pages</a>
                                <ul class=\"dropdown\">
                                    <li><a href=\"#\">About us</a></li>
                                    <li><a href=\"#\">Classes timetable</a></li>
                                    <li><a href=\"#\">Bmi calculator</a></li>
                                    <li><a href=\"#\">Our team</a></li>
                                    <li><a href=\"#\">Gallery</a></li>
                                    <li><a href=\"#\">Our blog</a></li>
                                    <li><a href=\"#\">404</a></li>
                                </ul>
                            </li>
                            <li>
                            <ul class=\"d-flex\" style=\"list-style: none; margin: 0; padding: 0; gap: 10px;\">
                                ";
        // line 219
        if (CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 219, $this->source); })()), "user", [], "any", false, false, false, 219)) {
            // line 220
            yield "                                    <li><a href=\"";
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_profile_index");
            yield "\">Mon profil</a></li>
                                    <li><a href=\"";
            // line 221
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
            yield "\">Déconnexion</a></li>
                                ";
        } else {
            // line 223
            yield "                                    <li><a href=\"";
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
            yield "\">Connexion</a></li>
                                    <li><a href=\"";
            // line 224
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_register");
            yield "\">S'inscrire</a></li>
                                ";
        }
        // line 226
        yield "                            </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class=\"col-lg-3\">
                    <div class=\"top-option d-flex align-items-center justify-content-end\">
                        <div class=\"to-search search-switch me-3\">
                            <i class=\"fa fa-search\"></i>
                        </div>
                      
                       
                    </div>
                </div>
            </div>
            <div class=\"canvas-open\">
                <i class=\"fa fa-bars\"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class=\"hero-section\">
        <div class=\"hs-slider owl-carousel\">
            <div class=\"hs-item set-bg\" data-setbg=\"";
        // line 251
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("img/hero/hero-1.jpg"), "html", null, true);
        yield "\">
                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"col-lg-6 offset-lg-6\">
                            <div class=\"hi-text\">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> training hard</h1>
                                <a href=\"#\" class=\"primary-btn\">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"hs-item set-bg\" data-setbg=\"";
        // line 264
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("img/hero/hero-2.jpg"), "html", null, true);
        yield "\">
                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"col-lg-6 offset-lg-6\">
                            <div class=\"hi-text\">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> training hard</h1>
                                <a href=\"#\" class=\"primary-btn\">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Content Block Symfony -->
    <section class=\"spad\">
        <div class=\"container\">
            ";
        // line 284
        yield from $this->unwrap()->yieldBlock('body', $context, $blocks);
        // line 285
        yield "        </div>
    </section>

    <!-- Footer Section Begin -->
    <section class=\"footer-section\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-lg-4\">
                    <div class=\"fs-about\">
                        <div class=\"fa-logo\">
                            <a href=\"#\"><img src=\"";
        // line 295
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("img/logo.png"), "html", null, true);
        yield "\" alt=\"\"></a>
                        </div>
                        <p>Bienvenue chez Sportify, votre salle de sport moderne à Tunis. Remise en forme, coaching, équipements professionnels et plus encore.</p>
                        <div class=\"fa-social\">
                            <a href=\"#\"><i class=\"fa fa-facebook\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-twitter\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-youtube-play\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-instagram\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-envelope-o\"></i></a>
                        </div>
                    </div>
                </div>
                <div class=\"col-lg-2 col-md-3 col-sm-6\">
                    <div class=\"fs-widget\">
                        <h4>Liens utiles</h4>
                        <ul>
                            <li><a href=\"#\">À propos</a></li>
                            <li><a href=\"#\">Blog</a></li>
                            <li><a href=\"#\">Cours</a></li>
                            <li><a href=\"#\">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class=\"col-lg-2 col-md-3 col-sm-6\">
                    <div class=\"fs-widget\">
                        <h4>Support</h4>
                        <ul>
                            <li><a href=\"#\">Connexion</a></li>
                            <li><a href=\"#\">Mon compte</a></li>
                            <li><a href=\"#\">S'abonner</a></li>
                            <li><a href=\"#\">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class=\"col-lg-4 col-md-6\">
                    <div class=\"fs-widget\">
                        <h4>Conseils & Guides</h4>
                        <div class=\"fw-recent\">
                            <h6><a href=\"#\">L'activité physique réduit le stress et l'anxiété</a></h6>
                            <ul>
                                <li>3 min de lecture</li>
                                <li>20 Commentaires</li>
                            </ul>
                        </div>
                        <div class=\"fw-recent\">
                            <h6><a href=\"#\">Exercices pour perdre du ventre efficacement</a></h6>
                            <ul>
                                <li>4 min de lecture</li>
                                <li>12 Commentaires</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-12 text-center\">
                    <div class=\"copyright-text\">
                        <p>
                            Copyright &copy;";
        // line 353
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "Y"), "html", null, true);
        yield " Tous droits réservés | Ce template est fait avec <i class=\"fa fa-heart\" aria-hidden=\"true\"></i> par <a href=\"https://colorlib.com\" target=\"_blank\">Colorlib</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Section End -->

    <!-- JS Plugins -->
    <script src=\"";
        // line 363
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/jquery-3.3.1.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 364
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/bootstrap.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 365
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/jquery.magnific-popup.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 366
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/masonry.pkgd.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 367
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/jquery.barfiller.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 368
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/jquery.slicknav.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 369
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/owl.carousel.min.js"), "html", null, true);
        yield "\"></script>
    <script src=\"";
        // line 370
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/main.js"), "html", null, true);
        yield "\"></script>
    
    <!-- Custom JavaScript -->
    <script src=\"";
        // line 373
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/custom.js"), "html", null, true);
        yield "\"></script>
    
    <!-- Google reCAPTCHA -->
    <script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>
    
    ";
        // line 378
        yield from $this->unwrap()->yieldBlock('javascripts', $context, $blocks);
        // line 379
        yield "</body>
</html>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    // line 10
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Sportify | Accueil";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 182
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 284
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 378
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "front.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  605 => 378,  583 => 284,  561 => 182,  538 => 10,  525 => 379,  523 => 378,  515 => 373,  509 => 370,  505 => 369,  501 => 368,  497 => 367,  493 => 366,  489 => 365,  485 => 364,  481 => 363,  468 => 353,  407 => 295,  395 => 285,  393 => 284,  370 => 264,  354 => 251,  327 => 226,  322 => 224,  317 => 223,  312 => 221,  307 => 220,  305 => 219,  279 => 196,  264 => 183,  262 => 182,  103 => 26,  99 => 25,  95 => 24,  91 => 23,  87 => 22,  83 => 21,  79 => 20,  75 => 19,  63 => 10,  52 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"zxx\">

<head>
    <meta charset=\"UTF-8\">
    <meta name=\"description\" content=\"Sportify Template\">
    <meta name=\"keywords\" content=\"Sportify, Gym, Fitness, HTML Template\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>{% block title %}Sportify | Accueil{% endblock %}</title>

    <script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>

    <!-- Google Fonts -->
    <link href=\"https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap\" rel=\"stylesheet\">
    <link href=\"https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap\" rel=\"stylesheet\">

    <!-- CSS Styles -->
    <link rel=\"stylesheet\" href=\"{{ asset('css/bootstrap.min.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/font-awesome.min.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/flaticon.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/owl.carousel.min.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/barfiller.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/magnific-popup.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/slicknav.min.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('css/style.css') }}\">
    
    <!-- FontAwesome if not included already -->
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css\" rel=\"stylesheet\">
    
    <style>
        :root {
            --dark-color: #0a0a0a;
            --dark-secondary: #151515;
            --accent-color: #ff7700;
            --accent-hover: #ff9133;
            --text-color: #d2d2d2;
            --text-muted: #7a7a7a;
            --error-color: #ff3b30;
        }
        
        body {
            background-color: var(--dark-color);
            color: var(--text-color);
        }
        
        /* Header styling */
        .header-section {
            background-color: var(--dark-secondary);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .nav-menu a {
            color: var(--text-color) !important;
        }
        
        .nav-menu a:hover, .nav-menu .active a {
            color: var(--accent-color) !important;
        }
        
        .nav-menu .dropdown {
            background-color: var(--dark-secondary);
            border: 1px solid #222;
        }
        
        /* Buttons styling */
        .primary-btn {
            background-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .primary-btn:hover {
            background-color: var(--accent-hover) !important;
        }
        
        /* Footer styling */
        .footer-section {
            background-color: var(--dark-secondary) !important;
            color: var(--text-color);
        }
        
        .footer-section h4, .footer-section h6 {
            color: var(--accent-color) !important;
        }
        
        .footer-section a {
            color: var(--text-color) !important;
        }
        
        .footer-section a:hover {
            color: var(--accent-color) !important;
        }
        
        /* Card and section styling */
        .card, .section-title, .team-item, .class-item, .blog-item {
            background-color: var(--dark-secondary) !important;
            color: var(--text-color) !important;
            border: 1px solid #222 !important;
        }
        
        .card-title, h1, h2, h3, h4, h5, h6 {
            color: var(--accent-color) !important;
        }
        
        .btn-primary {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--accent-hover) !important;
            border-color: var(--accent-hover) !important;
        }
        
        .btn-outline-primary {
            color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
        }
        
        .text-primary {
            color: var(--accent-color) !important;
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        /* Table styling */
        .table {
            color: var(--text-color);
        }
        
        .table thead th {
            background-color: var(--dark-secondary);
            border-color: #222;
        }
        
        .table td, .table th {
            border-color: #222;
        }
        
        /* Form elements */
        .form-control {
            background-color: #181818;
            border: 1px solid #333;
            color: var(--text-color);
        }
        
        .form-control:focus {
            background-color: #1a1a1a;
            border-color: var(--accent-color);
            color: var(--text-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 119, 0, 0.25);
        }
        
        .input-group-text {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        /* Hero section */
        .hero-section .hi-text span {
            color: var(--accent-color);
        }
        
        .hero-section .hi-text h1 {
            color: var(--text-color) !important;
        }
        
        .hero-section .hi-text h1 strong {
            color: var(--accent-color) !important;
        }
    </style>
    
    {% block stylesheets %}{% endblock %}
</head>

<body>
    <div id=\"preloder\">
        <div class=\"loader\"></div>
    </div>

    <!-- Header Section Begin -->
    <header class=\"header-section\">
        <div class=\"container-fluid\">
            <div class=\"row\">
                <div class=\"col-lg-3\">
                    <div class=\"logo\">
                        <img src=\"{{ asset('img/logo.png') }}\" alt=\"Sportify Logo\">
                    </div>
                </div>
                <div class=\"col-lg-6\">
                    <nav class=\"nav-menu\">
                        <ul>
                            <li><a href=\"#\">About Us</a></li>
                            <li><a href=\"#salles-de-sport\">Gym</a></li>
                            <li><a href=\"#\">Services</a></li>
                            <li><a href=\"#\">Our Team</a></li>
                            <li><a href=\"#\">Pages</a>
                                <ul class=\"dropdown\">
                                    <li><a href=\"#\">About us</a></li>
                                    <li><a href=\"#\">Classes timetable</a></li>
                                    <li><a href=\"#\">Bmi calculator</a></li>
                                    <li><a href=\"#\">Our team</a></li>
                                    <li><a href=\"#\">Gallery</a></li>
                                    <li><a href=\"#\">Our blog</a></li>
                                    <li><a href=\"#\">404</a></li>
                                </ul>
                            </li>
                            <li>
                            <ul class=\"d-flex\" style=\"list-style: none; margin: 0; padding: 0; gap: 10px;\">
                                {% if app.user %}
                                    <li><a href=\"{{ path('app_profile_index') }}\">Mon profil</a></li>
                                    <li><a href=\"{{ path('app_logout') }}\">Déconnexion</a></li>
                                {% else %}
                                    <li><a href=\"{{ path('app_login') }}\">Connexion</a></li>
                                    <li><a href=\"{{ path('app_register') }}\">S'inscrire</a></li>
                                {% endif %}
                            </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class=\"col-lg-3\">
                    <div class=\"top-option d-flex align-items-center justify-content-end\">
                        <div class=\"to-search search-switch me-3\">
                            <i class=\"fa fa-search\"></i>
                        </div>
                      
                       
                    </div>
                </div>
            </div>
            <div class=\"canvas-open\">
                <i class=\"fa fa-bars\"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class=\"hero-section\">
        <div class=\"hs-slider owl-carousel\">
            <div class=\"hs-item set-bg\" data-setbg=\"{{ asset('img/hero/hero-1.jpg') }}\">
                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"col-lg-6 offset-lg-6\">
                            <div class=\"hi-text\">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> training hard</h1>
                                <a href=\"#\" class=\"primary-btn\">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"hs-item set-bg\" data-setbg=\"{{ asset('img/hero/hero-2.jpg') }}\">
                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"col-lg-6 offset-lg-6\">
                            <div class=\"hi-text\">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> training hard</h1>
                                <a href=\"#\" class=\"primary-btn\">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Content Block Symfony -->
    <section class=\"spad\">
        <div class=\"container\">
            {% block body %}{% endblock %}
        </div>
    </section>

    <!-- Footer Section Begin -->
    <section class=\"footer-section\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-lg-4\">
                    <div class=\"fs-about\">
                        <div class=\"fa-logo\">
                            <a href=\"#\"><img src=\"{{ asset('img/logo.png') }}\" alt=\"\"></a>
                        </div>
                        <p>Bienvenue chez Sportify, votre salle de sport moderne à Tunis. Remise en forme, coaching, équipements professionnels et plus encore.</p>
                        <div class=\"fa-social\">
                            <a href=\"#\"><i class=\"fa fa-facebook\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-twitter\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-youtube-play\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-instagram\"></i></a>
                            <a href=\"#\"><i class=\"fa fa-envelope-o\"></i></a>
                        </div>
                    </div>
                </div>
                <div class=\"col-lg-2 col-md-3 col-sm-6\">
                    <div class=\"fs-widget\">
                        <h4>Liens utiles</h4>
                        <ul>
                            <li><a href=\"#\">À propos</a></li>
                            <li><a href=\"#\">Blog</a></li>
                            <li><a href=\"#\">Cours</a></li>
                            <li><a href=\"#\">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class=\"col-lg-2 col-md-3 col-sm-6\">
                    <div class=\"fs-widget\">
                        <h4>Support</h4>
                        <ul>
                            <li><a href=\"#\">Connexion</a></li>
                            <li><a href=\"#\">Mon compte</a></li>
                            <li><a href=\"#\">S'abonner</a></li>
                            <li><a href=\"#\">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class=\"col-lg-4 col-md-6\">
                    <div class=\"fs-widget\">
                        <h4>Conseils & Guides</h4>
                        <div class=\"fw-recent\">
                            <h6><a href=\"#\">L'activité physique réduit le stress et l'anxiété</a></h6>
                            <ul>
                                <li>3 min de lecture</li>
                                <li>20 Commentaires</li>
                            </ul>
                        </div>
                        <div class=\"fw-recent\">
                            <h6><a href=\"#\">Exercices pour perdre du ventre efficacement</a></h6>
                            <ul>
                                <li>4 min de lecture</li>
                                <li>12 Commentaires</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-12 text-center\">
                    <div class=\"copyright-text\">
                        <p>
                            Copyright &copy;{{ \"now\"|date(\"Y\") }} Tous droits réservés | Ce template est fait avec <i class=\"fa fa-heart\" aria-hidden=\"true\"></i> par <a href=\"https://colorlib.com\" target=\"_blank\">Colorlib</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Section End -->

    <!-- JS Plugins -->
    <script src=\"{{ asset('js/jquery-3.3.1.min.js') }}\"></script>
    <script src=\"{{ asset('js/bootstrap.min.js') }}\"></script>
    <script src=\"{{ asset('js/jquery.magnific-popup.min.js') }}\"></script>
    <script src=\"{{ asset('js/masonry.pkgd.min.js') }}\"></script>
    <script src=\"{{ asset('js/jquery.barfiller.js') }}\"></script>
    <script src=\"{{ asset('js/jquery.slicknav.js') }}\"></script>
    <script src=\"{{ asset('js/owl.carousel.min.js') }}\"></script>
    <script src=\"{{ asset('js/main.js') }}\"></script>
    
    <!-- Custom JavaScript -->
    <script src=\"{{ asset('js/custom.js') }}\"></script>
    
    <!-- Google reCAPTCHA -->
    <script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>
    
    {% block javascripts %}{% endblock %}
</body>
</html>
", "front.html.twig", "C:\\Users\\ridha\\Desktop\\esprit\\3eme\\Sem2\\PiDev\\symfonyPiDev\\ProjectS\\ProjetS\\templates\\front.html.twig");
    }
}
