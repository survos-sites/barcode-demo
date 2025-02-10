<?php

namespace App\Menu;

use Survos\BootstrapBundle\Event\KnpMenuEvent;
use Survos\BootstrapBundle\Traits\KnpMenuHelperInterface;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AppMenu implements KnpMenuHelperInterface
{
    use KnpMenuHelperTrait;

    // this should be optional, not sure we really need it here.
    public function __construct(
        private ?AuthorizationCheckerInterface $security = null)
    {
    }

    #[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU2)]
    public function navbarMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

//        $this->add($menu, 'app_homepage');
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        $nestedMenu = $this->addSubmenu($menu, 'Credits');

        foreach (['bundles', 'javascript'] as $type) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $this->addMenuItem($nestedMenu, ['uri' => "#$type", 'label' => ucfirst($type)]);
        }
    }

    #[AsEventListener(event: KnpMenuEvent::FOOTER_MENU)]
    public function footerMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $this->add($menu, uri: 'https://github.com');
    }

}
