<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    
    private $httpClient;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private UserPasswordHasherInterface $passwordHasher,
        HttpClientInterface $httpClient
    ) {
        $this->httpClient = $httpClient;
    }

    /**
     * Override this method to check if the authenticator supports the given request
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token');
        $recaptchaToken = $request->request->get('g-recaptcha-response');

        // Validate reCAPTCHA if token is present
        if ($recaptchaToken) {
            try {
                $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                        'response' => $recaptchaToken
                    ]
                ]);
                
                $data = $response->toArray();
                
                if (!($data['success'] ?? false)) {
                    throw new CustomUserMessageAuthenticationException('Vérification reCAPTCHA échouée. Veuillez réessayer.');
                }
            } catch (\Exception $e) {
                // En production, il serait préférable de logger l'erreur plutôt que de bloquer l'authentification
                error_log('Erreur lors de la vérification reCAPTCHA: ' . $e->getMessage());
                // La vérification reCAPTCHA échoue silencieusement en cas d'erreur
            }
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Log ajouté pour le débogage
        error_log("Tentative d'authentification pour l'email: $email");

        return new Passport(
            new UserBadge($email, function ($userIdentifier) use ($password) {
                $user = $this->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Adresse email introuvable.');
                }

                // Check if the user is active
                if (!$user->getIsactive()) {
                    throw new CustomUserMessageAuthenticationException('Votre compte est inactif.');
                }

                // Verify password manually
                if (!$this->passwordHasher->isPasswordValid($user, $password)) {
                    throw new CustomUserMessageAuthenticationException('Mot de passe incorrect.');
                }

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirect based on user role
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_user_index'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('app_front_index'));
        }
    }

    /**
     * Override to customize the message on authentication failure
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Log ajouté pour le débogage
        error_log("Échec d'authentification: " . $exception->getMessage());
        
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
} 