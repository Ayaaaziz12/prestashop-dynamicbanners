<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'dynamicbanners/classes/Banner.php';

class AdminBannersController extends ModuleAdminController
{  
    public function __construct()
    {
        $this->table = 'dynamic_banners';
        $this->className = 'Banner';
        $this->lang = false;
        $this->bootstrap = true;
        $this->identifier = 'id_banner';

        parent::__construct();

        // Liste des colonnes dans le tableau admin
        $this->fields_list = [
            'id_banner' => ['title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'],
            'title' => ['title' => $this->l('Titre')],
            'positions' => ['title' => $this->l('Position')],
            'priority' => ['title' => $this->l('Ordre')],
            'status' => ['title' => $this->l('Actif'), 'type' => 'bool', 'active' => 'status'],
            'date_from' => ['title' => $this->l('Début')],
            'date_to' => ['title' => $this->l('Fin')],
        ];

        // Actions ligne par ligne
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        // Actions groupées (sélection multiple) - CORRIGÉ
        $this->bulk_actions = [
            'enable' => [
                'text' => $this->l('Activer la sélection'),
                'icon' => 'icon-power-off text-success',
                'confirm' => $this->l('Voulez-vous activer ces bannières sélectionnées ?'),
            ],
            'disable' => [
                'text' => $this->l('Désactiver la sélection'),
                'icon' => 'icon-power-off text-danger',
                'confirm' => $this->l('Voulez-vous désactiver ces bannières sélectionnées ?'),
            ],
            'delete' => [
                'text' => $this->l('Supprimer la sélection'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Êtes-vous sûr de vouloir supprimer ces bannières ?'),
            ],
        ];
    }

    // CORRECTION: Méthodes d'actions groupées selon convention PrestaShop
    public function processBulkEnable()
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id_banner) {
                $banner = new Banner((int)$id_banner);
                if (Validate::isLoadedObject($banner)) {
                    $banner->status = 1;
                    $banner->update();
                }
            }
            $this->confirmations[] = $this->l('Bannières activées avec succès');
        } else {
            $this->errors[] = $this->l('Aucune bannière sélectionnée');
        }
    }

    public function processBulkDisable()
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id_banner) {
                $banner = new Banner((int)$id_banner);
                if (Validate::isLoadedObject($banner)) {
                    $banner->status = 0;
                    $banner->update();
                }
            }
            $this->confirmations[] = $this->l('Bannières désactivées avec succès');
        } else {
            $this->errors[] = $this->l('Aucune bannière sélectionnée');
        }
    }

    // Formulaire d'ajout / édition
    public function renderForm()
{
    if (!($obj = $this->loadObject(true))) {
        return;
    }

    // Si édition, convertir la chaîne CSV en tableau pour le formulaire
    if ($obj->id && !empty($obj->positions)) {
        $selected_positions = explode(',', $obj->positions);
        $this->fields_value['positions[]'] = $selected_positions;
    }

    $this->fields_form = [
        'legend' => [
            'title' => $this->l('Bannière'),
            'icon' => 'icon-picture',
        ],
        'input' => [
            [
                'type' => 'text', 
                'label' => $this->l('Titre'), 
                'name' => 'title', 
                'required' => true,
                'lang' => false
            ],
            [
                'type' => 'file', 
                'label' => $this->l('Image'), 
                'name' => 'image',
                'display_image' => true,
                'desc' => $this->l('Format JPG, PNG, GIF. Taille recommandée: 1200x400px')
            ],
            [
                'type' => 'text', 
                'label' => $this->l('Lien'), 
                'name' => 'link',
                'desc' => $this->l('URL vers laquelle la bannière redirige')
            ],
            [
                'type' => 'select',
                'label' => $this->l('Positions'),
                'name' => 'positions[]',
                'multiple' => true,
                'required' => true,
                'options' => [
                    'query' => [
                        ['id' => 'home', 'name' => $this->l('Accueil')],
                        ['id' => 'header', 'name' => $this->l('Header')],
                        ['id' => 'footer', 'name' => $this->l('Pied de page')],
                        ['id' => 'cart', 'name' => $this->l('Panier')],
                
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
                'desc' => $this->l('Maintenez CTRL pour sélectionner plusieurs positions')
            ],
            [
                'type' => 'datetime', 
                'label' => $this->l('Date de début'), 
                'name' => 'date_from',
                'desc' => $this->l('Date à laquelle la bannière devient active')
            ],
            [
                'type' => 'datetime', 
                'label' => $this->l('Date de fin'), 
                'name' => 'date_to',
                'desc' => $this->l('Date à laquelle la bannière se désactive')
            ],
            [
                'type' => 'switch', 
                'label' => $this->l('Actif'), 
                'name' => 'status', 
                'is_bool' => true,
                'values' => [
                    ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Oui')],
                    ['id' => 'active_off', 'value' => 0, 'label' => $this->l('Non')],
                ]
            ],
            [
                'type' => 'text', 
                'label' => $this->l('Ordre (priorité)'), 
                'name' => 'priority',
                'desc' => $this->l('Plus le nombre est bas, plus la bannière apparaît en haut')
            ],
        ],
        'submit' => [
            'title' => $this->l('Enregistrer'),
        ],
    ];

    // Afficher l'image actuelle si édition
    if ($obj = $this->loadObject(true)) {
        if (!empty($obj->image)) {
            $image_url = _MODULE_DIR_ . 'dynamicbanners/img/' . $obj->image;
            $this->fields_value['image_preview'] = '<img src="' . $image_url . '" style="max-width: 300px; max-height: 150px;" class="img-thumbnail" />';
        }
    }

    return parent::renderForm();
}

    public function processAdd()
{
    // Convertir le tableau positions en string CSV avant la validation
    $this->processPositions();
    
    $this->handleImageUpload();
    return parent::processAdd();
}
    public function processUpdate()
{
    // Convertir le tableau positions en string CSV avant la validation
    $this->processPositions();
    
    $this->handleImageUpload();
    return parent::processUpdate();
}
/**
 * Convertit le tableau positions en chaîne CSV
 */
private function processPositions()
{
    if (Tools::getValue('positions') && is_array(Tools::getValue('positions'))) {
        $positions = Tools::getValue('positions');
        $_POST['positions'] = implode(',', $positions);
    }
}


    private function handleImageUpload()
    {
        if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
            // Vérification du type de fichier
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                $this->errors[] = $this->l('Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.');
                return false;
            }

            // Création du dossier si nécessaire
            $img_dir = _PS_MODULE_DIR_ . 'dynamicbanners/img/';
            if (!is_dir($img_dir)) {
                mkdir($img_dir, 0755, true);
            }

            // Génération du nom de fichier
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $extension;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $img_dir . $file_name)) {
                $_POST['image'] = $file_name;
                
                // Suppression de l'ancienne image si modification
                if (Tools::getValue('id_banner')) {
                    $old_banner = new Banner((int)Tools::getValue('id_banner'));
                    if (!empty($old_banner->image) && file_exists($img_dir . $old_banner->image)) {
                        unlink($img_dir . $old_banner->image);
                    }
                }
            } else {
                $this->errors[] = $this->l('Erreur lors de l\'upload de l\'image');
            }
        }
    }
}