<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . 'dynamicbanners/classes/Banner.php';
class DynamicBanners extends Module
{
    public function __construct()
    {
        $this->name = 'dynamicbanners';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Aya Aziz';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Bannières Dynamiques');
        $this->description = $this->l('Permet de gérer et afficher des bannières dynamiques sur différentes zones du site.');
    }

    /**
     * Installation du module
     */
    public function install()
    {
        include(dirname(__FILE__) . '/install/sql/install.sql');

        return parent::install()
            && $this->installTab('AdminParentModulesSf', 'AdminBanners', 'Gestion des Bannières')
            && $this->registerHook('displayHome')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooter')
            && $this->registerHook('displayShoppingCart')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    /**
     * Désinstallation du module
     */
    public function uninstall()
    {
        // Supprimer la table
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dynamic_banners`');

        // Supprimer l'onglet d'admin
        $id_tab = (int) Tab::getIdFromClassName('AdminBanners');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }

        return parent::uninstall();
    }

    /**
     * Création de l'onglet dans le Back Office
     */
    private function installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tab->id_parent = (int) Tab::getIdFromClassName($parent);
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->active = 1;
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        return $tab->add();
    }

    /**
     * Charger CSS/JS front
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        $this->context->controller->registerStylesheet(
            'module-dynamicbanners',
            'modules/' . $this->name . '/views/css/dynamicbanners.css',
            ['media' => 'all', 'priority' => 150]
        );
    }

    /**
     * Récupérer les bannières selon la position
     */
    private function getBannersByPosition($position)
    {
        $now = date('Y-m-d H:i:s');
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('dynamic_banners');
        $sql->where("FIND_IN_SET('" . pSQL($position) . "', positions)");
        $sql->where("status = 1");
        $sql->where("(date_from IS NULL OR date_from <= '" . pSQL($now) . "')");
        $sql->where("(date_to IS NULL OR date_to >= '" . pSQL($now) . "')");
        $sql->orderBy('priority ASC, id_banner DESC');

        return Db::getInstance()->executeS($sql);
    }

    /**
     * Hook - Accueil
     */
    public function hookDisplayHome($params)
    {
        $banners = $this->getBannersByPosition('home');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayHome.tpl');
        }
    }

    /**
     * Hook - Footer
     */
    public function hookDisplayFooter($params)
    {
        $banners = $this->getBannersByPosition('footer');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayFooter.tpl');
        }
    }

    /**
     * Hook - Header (en-tête)
     */
    public function hookDisplayHeader($params)
    {
        $banners = $this->getBannersByPosition('header');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayHeader.tpl');
        }
    }

    /**
     * Hook - Panier
     */
    public function hookDisplayShoppingCart($params)
    {
        $banners = $this->getBannersByPosition('cart');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayCart.tpl');
        }
    }

    /**
     * Optionnel : Hook générique pour d'autres positions
     */
    public function hookDisplayLeftColumn($params)
    {
        $banners = $this->getBannersByPosition('left');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayLeft.tpl');
        }
    }

    public function hookDisplayRightColumn($params)
    {
        $banners = $this->getBannersByPosition('right');
        if ($banners) {
            $this->context->smarty->assign([
                'banners' => $banners,
                'image_path' => _MODULE_DIR_ . 'dynamicbanners/img/'  // CHEMIN UNIFIÉ
            ]);
            return $this->display(__FILE__, 'views/templates/hook/displayRight.tpl');
        }
    }
    
    public function getContent()
    {
        $html = '<div class="panel">';
        $html .= '<h3><i class="icon icon-picture"></i> ' . $this->l('Bannières Dynamiques') . '</h3>';
        $html .= '<p>' . $this->l('Gérez vos bannières dynamiques depuis cette interface.') . '</p>';
        $html .= '<br>';
        $link = $this->context->link->getAdminLink('AdminBanners');
        $html .= '<a href="' . $link . '" class="btn btn-primary">';
        $html .= '<i class="icon icon-cogs"></i> ' . $this->l('Ouvrir la gestion des bannières');
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }
}