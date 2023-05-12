<?php
namespace AppBundle\EventListener;

use AppBundle\Component\SidebarMenuBuilder;
use SbS\AdminLTEBundle\Event\SidebarMenuEvent;

class SidebarMenuEventListener {

    protected $builder;

    public function __construct(SidebarMenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function onShowMenu(SidebarMenuEvent $event)
    {
        foreach ($this->builder->getMenu() as $item) {
            $event->addItem($item);
        }
    }
}
