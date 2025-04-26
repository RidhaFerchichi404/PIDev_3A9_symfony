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

/* security/login.html.twig */
class __TwigTemplate_7d4862f904ecd6804d294fa5d666f939 extends Template
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

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "front.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login.html.twig"));

        $this->parent = $this->loadTemplate("front.html.twig", "security/login.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
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

        yield "Log in!";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
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

        // line 6
        yield from $this->yieldParentBlock("stylesheets", $context, $blocks);
        yield "
<style>
    :root {
        --dark-color: #0a0a0a;
        --dark-secondary: #111111;
        --dark-tertiary: #1c1c1c;
        --accent-color: #FFA500;
        --accent-hover: #FFD700;
        --accent-transparent: rgba(255, 165, 0, 0.1);
        --text-color: #e0e0e0;
        --text-muted: #909090;
        --error-color: #FF0000;
    }
    
    body {
        background-color: var(--dark-color);
        color: var(--text-color);
    }
    
    .login-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    
    .login-form-container {
        background-color: var(--dark-tertiary);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        width: 100%;
        max-width: 480px;
        transform: translateY(0);
        transition: all 0.3s ease;
        border: 1px solid #2a2a2a;
        animation: fadeIn 0.8s ease;
    }
    
    .login-form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
        border-color: var(--accent-transparent);
    }
    
    .login-form-header {
        padding: 30px;
        background: linear-gradient(45deg, #151515, #1c1c1c);
        border-bottom: 1px solid #2a2a2a;
        text-align: center;
        position: relative;
    }
    
    .login-form-header:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, var(--accent-color), #FF0000);
    }
    
    .login-form-header h1 {
        color: var(--accent-color);
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .login-form-body {
        padding: 40px 30px;
    }
    
    .form-label {
        color: var(--text-color);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-label i {
        margin-right: 8px;
        color: var(--accent-color);
    }
    
    .form-control {
        background-color: #181818;
        border: 1px solid #2a2a2a;
        color: var(--text-color);
        padding: 15px;
        height: auto;
        border-radius: 10px;
        box-shadow: none !important;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .form-control:focus {
        background-color: #1d1d1d;
        border-color: var(--accent-color);
        color: var(--text-color);
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.15) !important;
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }
    
    .input-group-text {
        background: linear-gradient(45deg, #FF8C00, var(--accent-color)) !important;
        border-color: var(--accent-color) !important;
        color: #fff !important;
        border-radius: 10px 0 0 10px;
        width: 50px;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .form-check-input {
        width: 18px;
        height: 18px;
        background-color: #181818;
        border-color: #333;
        border-radius: 4px;
    }
    
    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .form-check-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        padding-left: 5px;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #FF0000, var(--accent-color)) !important;
        border: none !important;
        color: #fff !important;
        padding: 15px !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        border-radius: 50px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3) !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, var(--accent-color), #FFD700) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 20px rgba(255, 165, 0, 0.4) !important;
    }
    
    .alert-danger {
        background-color: rgba(255, 59, 48, 0.15);
        border-color: rgba(255, 59, 48, 0.3);
        color: var(--error-color);
        border-radius: 10px;
        padding: 15px;
        position: relative;
        border-left: 4px solid var(--error-color);
    }
    
    .alert-info {
        background-color: rgba(255, 119, 0, 0.15);
        border-color: rgba(255, 119, 0, 0.3);
        color: var(--text-color);
        border-radius: 10px;
        padding: 15px;
        position: relative;
        border-left: 4px solid var(--accent-color);
    }
    
    .text-primary, a {
        color: var(--accent-color) !important;
        transition: all 0.3s ease;
    }
    
    a:hover {
        color: var(--accent-hover) !important;
        text-decoration: none;
    }
    
    .login-divider {
        display: flex;
        align-items: center;
        margin: 25px 0;
    }
    
    .login-divider::before,
    .login-divider::after {
        content: \"\";
        flex: 1;
        border-bottom: 1px solid #333;
    }
    
    .login-divider span {
        padding: 0 15px;
        color: var(--text-muted);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
    
    .login-footer {
        text-align: center;
        color: var(--text-muted);
        margin-top: 25px;
        font-size: 0.95rem;
    }
    
    .login-footer a {
        font-weight: 600;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 248
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

        // line 249
        yield "<div class=\"login-container\">
    <div class=\"login-form-container\">
        <div class=\"login-form-header\">
            <h1>Sign In</h1>
        </div>
        <div class=\"login-form-body\">
            <form method=\"post\" action=\"";
        // line 255
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
        yield "\">
                ";
        // line 256
        if ((isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 256, $this->source); })())) {
            // line 257
            yield "                    <div class=\"alert alert-danger mb-4\">
                        <i class=\"fas fa-exclamation-circle me-2\"></i>
                        ";
            // line 259
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 259, $this->source); })()), "messageKey", [], "any", false, false, false, 259), CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 259, $this->source); })()), "messageData", [], "any", false, false, false, 259), "security"), "html", null, true);
            yield "
                    </div>
                ";
        }
        // line 262
        yield "
                ";
        // line 263
        if (CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 263, $this->source); })()), "user", [], "any", false, false, false, 263)) {
            // line 264
            yield "                    <div class=\"alert alert-info mb-4\">
                        <i class=\"fas fa-info-circle me-2\"></i>
                        You are logged in as ";
            // line 266
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 266, $this->source); })()), "user", [], "any", false, false, false, 266), "userIdentifier", [], "any", false, false, false, 266), "html", null, true);
            yield ", <a href=\"";
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
            yield "\" class=\"fw-bold\">Logout</a>
                    </div>
                ";
        }
        // line 269
        yield "
                <div class=\"mb-4\">
                    <label for=\"email\" class=\"form-label\">
                        <i class=\"fas fa-at\"></i> Email Address
                    </label>
                    <div class=\"input-group\">
                        <span class=\"input-group-text\">
                            <i class=\"fas fa-envelope\"></i>
                        </span>
                        <input type=\"email\" value=\"";
        // line 278
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["last_username"]) || array_key_exists("last_username", $context) ? $context["last_username"] : (function () { throw new RuntimeError('Variable "last_username" does not exist.', 278, $this->source); })()), "html", null, true);
        yield "\" name=\"email\" id=\"email\" class=\"form-control\" placeholder=\"Enter your email\" autocomplete=\"email\" required autofocus>
                    </div>
                </div>
                
                <div class=\"mb-4\">
                    <label for=\"password\" class=\"form-label\">
                        <i class=\"fas fa-key\"></i> Password
                    </label>
                    <div class=\"input-group\">
                        <span class=\"input-group-text\">
                            <i class=\"fas fa-lock\"></i>
                        </span>
                        <input type=\"password\" name=\"password\" id=\"password\" class=\"form-control\" placeholder=\"Enter your password\" autocomplete=\"current-password\" required>
                    </div>
                </div>

                <input type=\"hidden\" name=\"_csrf_token\" value=\"";
        // line 294
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("authenticate"), "html", null, true);
        yield "\">

                <div class=\"d-flex justify-content-between align-items-center mb-4\">
                    <div class=\"form-check\">
                        <input type=\"checkbox\" name=\"_remember_me\" id=\"remember_me\" class=\"form-check-input\">
                        <label class=\"form-check-label\" for=\"remember_me\">Remember me</label>
                    </div>
                    <a href=\"";
        // line 301
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_forgot_password");
        yield "\" class=\"text-primary\">Forgot password?</a>
                </div>

                <div class=\"mb-4\">
                    <div class=\"g-recaptcha\" data-sitekey=\"6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI\"></div>
                </div>

                <button class=\"btn btn-primary w-100 py-3 mb-3\" type=\"submit\">
                    <i class=\"fas fa-sign-in-alt me-2\"></i> Sign in
                </button>
                
                <div class=\"login-divider\">
                    <span>OR</span>
                </div>
                
                <div class=\"login-footer\">
                    <p class=\"mb-0\">Don't have an account? <a href=\"";
        // line 317
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_register");
        yield "\" class=\"fw-bold\">Register now</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "security/login.html.twig";
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
        return array (  466 => 317,  447 => 301,  437 => 294,  418 => 278,  407 => 269,  399 => 266,  395 => 264,  393 => 263,  390 => 262,  384 => 259,  380 => 257,  378 => 256,  374 => 255,  366 => 249,  353 => 248,  101 => 6,  88 => 5,  65 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'front.html.twig' %}

{% block title %}Log in!{% endblock %}

{% block stylesheets %}
{{ parent() }}
<style>
    :root {
        --dark-color: #0a0a0a;
        --dark-secondary: #111111;
        --dark-tertiary: #1c1c1c;
        --accent-color: #FFA500;
        --accent-hover: #FFD700;
        --accent-transparent: rgba(255, 165, 0, 0.1);
        --text-color: #e0e0e0;
        --text-muted: #909090;
        --error-color: #FF0000;
    }
    
    body {
        background-color: var(--dark-color);
        color: var(--text-color);
    }
    
    .login-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    
    .login-form-container {
        background-color: var(--dark-tertiary);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        width: 100%;
        max-width: 480px;
        transform: translateY(0);
        transition: all 0.3s ease;
        border: 1px solid #2a2a2a;
        animation: fadeIn 0.8s ease;
    }
    
    .login-form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
        border-color: var(--accent-transparent);
    }
    
    .login-form-header {
        padding: 30px;
        background: linear-gradient(45deg, #151515, #1c1c1c);
        border-bottom: 1px solid #2a2a2a;
        text-align: center;
        position: relative;
    }
    
    .login-form-header:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, var(--accent-color), #FF0000);
    }
    
    .login-form-header h1 {
        color: var(--accent-color);
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .login-form-body {
        padding: 40px 30px;
    }
    
    .form-label {
        color: var(--text-color);
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-label i {
        margin-right: 8px;
        color: var(--accent-color);
    }
    
    .form-control {
        background-color: #181818;
        border: 1px solid #2a2a2a;
        color: var(--text-color);
        padding: 15px;
        height: auto;
        border-radius: 10px;
        box-shadow: none !important;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .form-control:focus {
        background-color: #1d1d1d;
        border-color: var(--accent-color);
        color: var(--text-color);
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.15) !important;
    }
    
    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }
    
    .input-group-text {
        background: linear-gradient(45deg, #FF8C00, var(--accent-color)) !important;
        border-color: var(--accent-color) !important;
        color: #fff !important;
        border-radius: 10px 0 0 10px;
        width: 50px;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .form-check-input {
        width: 18px;
        height: 18px;
        background-color: #181818;
        border-color: #333;
        border-radius: 4px;
    }
    
    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .form-check-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        padding-left: 5px;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #FF0000, var(--accent-color)) !important;
        border: none !important;
        color: #fff !important;
        padding: 15px !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        border-radius: 50px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3) !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, var(--accent-color), #FFD700) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 20px rgba(255, 165, 0, 0.4) !important;
    }
    
    .alert-danger {
        background-color: rgba(255, 59, 48, 0.15);
        border-color: rgba(255, 59, 48, 0.3);
        color: var(--error-color);
        border-radius: 10px;
        padding: 15px;
        position: relative;
        border-left: 4px solid var(--error-color);
    }
    
    .alert-info {
        background-color: rgba(255, 119, 0, 0.15);
        border-color: rgba(255, 119, 0, 0.3);
        color: var(--text-color);
        border-radius: 10px;
        padding: 15px;
        position: relative;
        border-left: 4px solid var(--accent-color);
    }
    
    .text-primary, a {
        color: var(--accent-color) !important;
        transition: all 0.3s ease;
    }
    
    a:hover {
        color: var(--accent-hover) !important;
        text-decoration: none;
    }
    
    .login-divider {
        display: flex;
        align-items: center;
        margin: 25px 0;
    }
    
    .login-divider::before,
    .login-divider::after {
        content: \"\";
        flex: 1;
        border-bottom: 1px solid #333;
    }
    
    .login-divider span {
        padding: 0 15px;
        color: var(--text-muted);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
    
    .login-footer {
        text-align: center;
        color: var(--text-muted);
        margin-top: 25px;
        font-size: 0.95rem;
    }
    
    .login-footer a {
        font-weight: 600;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
{% endblock %}

{% block body %}
<div class=\"login-container\">
    <div class=\"login-form-container\">
        <div class=\"login-form-header\">
            <h1>Sign In</h1>
        </div>
        <div class=\"login-form-body\">
            <form method=\"post\" action=\"{{ path('app_login') }}\">
                {% if error %}
                    <div class=\"alert alert-danger mb-4\">
                        <i class=\"fas fa-exclamation-circle me-2\"></i>
                        {{ error.messageKey|trans(error.messageData, 'security') }}
                    </div>
                {% endif %}

                {% if app.user %}
                    <div class=\"alert alert-info mb-4\">
                        <i class=\"fas fa-info-circle me-2\"></i>
                        You are logged in as {{ app.user.userIdentifier }}, <a href=\"{{ path('app_logout') }}\" class=\"fw-bold\">Logout</a>
                    </div>
                {% endif %}

                <div class=\"mb-4\">
                    <label for=\"email\" class=\"form-label\">
                        <i class=\"fas fa-at\"></i> Email Address
                    </label>
                    <div class=\"input-group\">
                        <span class=\"input-group-text\">
                            <i class=\"fas fa-envelope\"></i>
                        </span>
                        <input type=\"email\" value=\"{{ last_username }}\" name=\"email\" id=\"email\" class=\"form-control\" placeholder=\"Enter your email\" autocomplete=\"email\" required autofocus>
                    </div>
                </div>
                
                <div class=\"mb-4\">
                    <label for=\"password\" class=\"form-label\">
                        <i class=\"fas fa-key\"></i> Password
                    </label>
                    <div class=\"input-group\">
                        <span class=\"input-group-text\">
                            <i class=\"fas fa-lock\"></i>
                        </span>
                        <input type=\"password\" name=\"password\" id=\"password\" class=\"form-control\" placeholder=\"Enter your password\" autocomplete=\"current-password\" required>
                    </div>
                </div>

                <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token('authenticate') }}\">

                <div class=\"d-flex justify-content-between align-items-center mb-4\">
                    <div class=\"form-check\">
                        <input type=\"checkbox\" name=\"_remember_me\" id=\"remember_me\" class=\"form-check-input\">
                        <label class=\"form-check-label\" for=\"remember_me\">Remember me</label>
                    </div>
                    <a href=\"{{ path('app_forgot_password') }}\" class=\"text-primary\">Forgot password?</a>
                </div>

                <div class=\"mb-4\">
                    <div class=\"g-recaptcha\" data-sitekey=\"6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI\"></div>
                </div>

                <button class=\"btn btn-primary w-100 py-3 mb-3\" type=\"submit\">
                    <i class=\"fas fa-sign-in-alt me-2\"></i> Sign in
                </button>
                
                <div class=\"login-divider\">
                    <span>OR</span>
                </div>
                
                <div class=\"login-footer\">
                    <p class=\"mb-0\">Don't have an account? <a href=\"{{ path('app_register') }}\" class=\"fw-bold\">Register now</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %} ", "security/login.html.twig", "C:\\Users\\ridha\\Desktop\\esprit\\3eme\\Sem2\\PiDev\\symfonyPiDev\\ProjectS\\ProjetS\\templates\\security\\login.html.twig");
    }
}
