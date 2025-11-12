# 🧩 Module PrestaShop – Dynamic Banners (Bannières Dynamiques)

## 📘 Description du projet
Ce module PrestaShop permet d’ajouter, gérer et afficher **des bannières dynamiques** sur différentes zones du site e-commerce.  
L’administrateur peut créer des bannières depuis le back-office, définir leur **titre**, **image**, **lien**, **position**, **dates de validité**, et **statut (actif/inactif)**.  
Les bannières sont ensuite affichées automatiquement sur le front-office selon les positions choisies et les dates configurées.

> Projet réalisé dans le cadre d’un **stage de 1 mois** sur le thème :  
> **"Développement d’un module PrestaShop – Bannières Dynamiques"**

---

## 🧭 Objectifs pédagogiques
- Découvrir l’architecture et le cycle de vie d’un module PrestaShop.
- Développer une interface CRUD (Create, Read, Update, Delete) dans le back-office.
- Gérer l’affichage conditionnel de données sur le front-office à l’aide des hooks.
- Manipuler la base de données PrestaShop via la classe `Db` et les modèles personnalisés.
- Créer une interface utilisateur responsive et moderne.
- Respecter les bonnes pratiques de développement et la structure MVC de PrestaShop.

---

## ⚙️ Fonctionnalités principales

### 🖥️ Côté Back-office :
- Interface complète pour gérer les bannières :
  - Ajouter, modifier et supprimer une bannière.
  - Définir un titre, une image, un lien cliquable.
  - Sélectionner la position d’affichage (Accueil, Footer, Panier, Catégorie…).
  - Choisir une période d’affichage (date de début et de fin).
  - Activer ou désactiver une bannière.
- Upload sécurisé d’images avec vérification d’extension et de taille.
- Gestion de l’ordre d’affichage (priorité).

### 🌐 Côté Front-office :
- Affichage automatique des bannières selon :
  - La position choisie (hook).
  - Les dates de validité.
  - Le statut (actif/inactif).
- Hooks supportés :
  - `displayHome`
  - `displayFooter`
  - `displayHeader`
  - `displayShoppingCartFooter`
  - `actionFrontControllerSetMedia` (chargement CSS/JS)
- Interface responsive (images adaptatives).
- Support du lazy loading pour les images.

---

## 🧩 Structure du module

modules/dynamicbanners/
├── dynamicbanners.php          # Fichier principal
├── logo.png                    # Icône du module
├── index.php                   # Sécurité
├── config.xml                  # Métadonnées
│
├── controllers/                # Contrôleurs
│   ├── admin/
│   │   └── AdminDynamicBannersController.php
│   └── front/
│
├── views/                      # Vues et assets
│   ├── css/
│   │   └── dynamicbanners.css
│   ├── js/
│   │   └── admin.js
│   ├── templates/
│   │   ├── admin/
│   │   └── hook/
│   │       ├── home.tpl
│   │       ├── header.tpl
│   │       ├── footer.tpl
│   │       └── cart.tpl
│   └── img/
│
├── uploads/                    # Bannières uploadées
│   └── index.php
│
├── sql/                       # Base de données
│   └── install.sql
│
└── translations/              # Langues
    └── fr.php ; ar.php


---

## 🗃️ Structure de la base de données

Table : `ps_dynamic_banners`

| Champ        | Type         | Description                        |
|---------------|--------------|------------------------------------|
| id_banner     | INT (PK)     | Identifiant unique                 |
| title         | VARCHAR(255) | Titre de la bannière               |
| image         | VARCHAR(255) | Nom du fichier image               |
| url           | VARCHAR(255) | Lien de redirection                |
| position      | VARCHAR(50)  | Zone d’affichage (home, footer...) |
| start_date    | DATETIME     | Date de début                      |
| end_date      | DATETIME     | Date de fin                        |
| status        | TINYINT(1)   | 1 = actif, 0 = inactif             |
| sort_order    | INT          | Ordre d’affichage                  |
| created_at    | DATETIME     | Date de création                   |
| updated_at    | DATETIME     | Date de modification               |

---

## 🚀 Installation du module

1. Télécharger le dossier du module **dynamicbanners/**.
2. Créer une archive ZIP :  
   → Sélectionner tout le contenu du dossier et compresser en `dynamicbanners.zip`.
3. Depuis le **Back-office PrestaShop** :
   - Aller dans **Modules > Module Manager > Upload a module**
   - Importer le fichier `dynamicbanners.zip`
   - Cliquer sur **Installer**
4. Le module crée automatiquement la table `ps_dynamic_banners` dans la base de données.
5. Accéder à l’onglet **Bannières Dynamiques** dans le menu Modules ou Marketing.

---

## 🧠 Utilisation

### ➕ Ajouter une bannière :
1. Aller dans **Modules > Bannières Dynamiques > Ajouter une bannière**.
2. Remplir les champs requis :
   - Titre
   - Image (upload)
   - Lien
   - Position d’affichage
   - Dates de début/fin
   - Statut
3. Enregistrer → La bannière s’affichera automatiquement sur le front-office.

### ✏️ Modifier / Supprimer :
- Utiliser les boutons d’action dans la liste des bannières.

### 🔎 Affichage sur le site :
- Les bannières actives apparaissent automatiquement selon leur position et leurs dates de validité.
- Exemples :
  - `displayHome.tpl` → page d’accueil.
  - `displayFooter.tpl` → pied de page.
  - `displayShoppingCartFooter.tpl` → bas du panier.

---

## 🧰 Technologies et outils utilisés
- **PrestaShop 1.7.x**
- **PHP 7.4+**
- **MySQL**
- **HTML / Smarty / CSS / JavaScript**
- **Bootstrap (ou Tailwind pour le style responsive)**
- **Laragon / WAMP pour l’environnement local**
- **GitHub / Git pour le versionning**

---

## 🧪 Tests effectués

| Test | Résultat attendu | Statut |
|------|------------------|--------|
| Installation du module | Table créée + module actif | ✅ |
| Ajout bannière valide | Affichage correct sur home | ✅ |
| Bannière future | Non affichée avant date début | ✅ |
| Bannière expirée | Non affichée | ✅ |
| Statut désactivé | Non affichée | ✅ |
| Upload fichier non image | Message d’erreur | ✅ |
| Tri des bannières | Respect de l’ordre défini | ✅ |

---

## 🧾 Auteur
👤 **Aya Aziz**  
---

## 📄 Licence
Ce projet est réalisé à des fins **pédagogiques** dans le cadre d’un stage.  
Toute réutilisation partielle ou totale doit mentionner l’auteur original.  

---

## 🏁 Statut du projet
✅ **En cours – Semaine 3 : développement du front-office**

---

## 🔗 Ressources utiles
- [Documentation officielle PrestaShop Modules](https://devdocs.prestashop-project.org/8/modules/)
- [Liste des hooks PrestaShop](https://devdocs.prestashop-project.org/1.7/themes/reference/hooks/)
- [Smarty Template Engine](https://www.smarty.net/docsv2/en/)
