<?php


namespace App\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class RedirectionListener
{
    protected $router;
    protected $session;
    protected $securityContext;
    protected $tokenStorage;

    /**
     * RedirectionListener constructor.
     * @param ContainerInterface $container
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(ContainerInterface $container, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->router = $container->get('router');
        $this->session = $session;
//        $this->securityContext = $container->get('security.');
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param RequestEvent $requestEvent
     */
    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        $route = $requestEvent->getRequest()->attributes->get('_route');

        if($route === 'delivery' || $route === 'validation'){
            if($this->session->has('panier')){
                if(count($this->session->get('panier')) === 0){
                    $requestEvent->setResponse(new RedirectResponse($this->router->generate('cart')));
                }
            }

//            if(!is_object($event->getAuthenticationToken()->getUser())){
//                $this->session->getFlashBag()->add('notification','vous devez vous identifier');
                //$this->session->set('message','vous devez être identifier.');
                //$requestEvent->setResponse(new RedirectResponse($this->router->generate('user_connexion')));
//            }
        }
    }
}
