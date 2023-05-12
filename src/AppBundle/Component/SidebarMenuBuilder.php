<?php
namespace AppBundle\Component;

use SbS\AdminLTEBundle\Model\MenuItemInterface;
use SbS\AdminLTEBundle\Model\SidebarMenuItemModel;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\User;

class SidebarMenuBuilder  {
  /**
   * @var Security
   */
  private $security;

  public function __construct(Security $security)
  {
     $this->security = $security;
  }

  public function getMenu() {

        $user = $this->security->getUser();
        $roles = $user->getRoles();

          if(in_array("ROLE_ADMIN", $roles)){
            $item_dashbord = (new SidebarMenuItemModel('Tableau de bord'))
            ->setRoute('homepage')
            ->setIcon('fas fa-tachometer-alt');

            $item_depot = (new SidebarMenuItemModel('Dépôt de stockage'))
            ->setRoute('storage_depot_index')
            ->setIcon('fas fa-warehouse');

            $item_Fournisseurs = (new SidebarMenuItemModel('Fournisseurs'))
            ->setRoute('suppliers_index')
            ->setIcon('fas fa-industry');

            $Utilisateur = (new SidebarMenuItemModel('Administrateur'))
              ->setRoute('user_index')
              ->setIcon('fas fa-user');

            $categories = (new SidebarMenuItemModel('Catégories des produits'))
              ->setRoute('category_index')
              ->setIcon('fas fa-list-alt');
            
            $attributes = (new SidebarMenuItemModel('Attributs des produits'))
              ->setRoute('attribut_index')
              ->setIcon('fas fa-list-alt');

            $products = (new SidebarMenuItemModel('Produits'))
              ->setRoute('product_index')
              ->setIcon('fas fa-list-alt');
            
            $contacts = (new SidebarMenuItemModel('Contacts'))
              ->setRoute('contact_index')
              ->setIcon('fas fa-list-alt');
            return [
              $item_dashbord,
              $item_depot,
              $item_Fournisseurs,
              $Utilisateur,
              $categories,
              $attributes,
              $products,
              // $contacts
            ];
          }elseif (in_array("ROLE_SUPPLIERS", $roles)) {
            $item_dashbord = (new SidebarMenuItemModel('Tableau de bord'))
            ->setRoute('homepage')
            ->setIcon('fas fa-tachometer-alt');
            return [
              $item_dashbord,
            ];
          }else{
            return [];
          }




}

}
