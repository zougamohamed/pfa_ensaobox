<?php

namespace PFA\EnsaoboxBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security)
    {
        $this->router   = $router;
        $this->security = $security;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            $response = new RedirectResponse($this->router->generate('pfa_ensaobox_admin'));
        }

        elseif ($this->security->isGranted('ROLE_PROFESSOR'))
        {
            $response = new RedirectResponse($this->router->generate('pfa_ensaobox_ajouter_files'));
        }

        elseif ($this->security->isGranted('ROLE_ETUDIANT'))
        {
            $response = new RedirectResponse($this->router->generate('pfa_ensaobox_homepage'));
        }

        return $response;
    }
}

