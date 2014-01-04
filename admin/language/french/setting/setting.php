<?php
// Heading
$_['heading_title']              = 'Paramètres';

// Text
$_['text_success']               = 'Succès: Vous avez sauvegardé les paramètres correctement !';
$_['text_mail']                  = 'Mail';
$_['text_smtp']                  = 'SMTP';
$_['text_image_manager']         = 'Gestionnaire d\'images';

// Entry
$_['entry_store']                = 'Nom de la boutique:';
$_['entry_title']                = 'Titre de la page:';
$_['entry_meta_description']     = 'Balise méta "description":';
$_['entry_logo']                 = 'Logo de la boutique:';
$_['entry_welcome']              = 'Message de bienvenue:';
$_['entry_owner']                = 'Propriétaire de la boutique:';
$_['entry_address']              = 'Adresse:';
$_['entry_email']                = 'E-Mail:';
$_['entry_telephone']            = 'Téléphone:';
$_['entry_fax']                  = 'Fax:';
$_['entry_template']             = 'Template (thème) de la boutique:';
$_['entry_country']              = 'Pays:';
$_['entry_zone']                 = 'Région / État:';
$_['entry_language']             = 'Langue:';
$_['entry_admin_language']       = 'Langue de l\'administration:';
$_['entry_currency']             = 'Devise:';
$_['entry_currency_auto']        = 'Actualisation automatique du taux des devises: <br /><span class="help">Actualiser automatiquement et quotidiennement les taux des devises.</span>';
$_['entry_tax']                  = 'Afficher les prix <acronym title="Toutes Taxes Comprises">TTC</acronym>:';
$_['entry_weight_class']         = 'Unité de poids:';
$_['entry_measurement_class']    = 'Unité de longueur:';
$_['entry_alert_mail']           = 'Alerte e-mail:<br /><span class="help">Envoie un e-mail au propriétaire de la boutique à chaque nouvelle commande créée.</span>';
$_['entry_customer_group']       = 'Groupe de clients: <br /><span class="help">Groupe de clients par défaut.</span>';
$_['entry_customer_price']       = 'Afficher les prix si identifié: <br /><span class="help">Affichage des prix uniquement si le visiteur est identifié.</span>';
$_['entry_customer_approval']    = 'Approuver les nouveaux clients: <br /><span class="help">Empêcher les nouveaux clients de se connecter tant que leur compte n\'a pas été approuvé.</span>';
$_['entry_guest_checkout']       = 'Encaisser si visiteur:<br /><span class="help">Permettre aux visiteurs de passer à la caisse sans créer de compte. Indisponible lorsqu\'un produit à télécharger est dans le panier.</span>';
$_['entry_account']              = 'Accepter conditions avant création compte: <br /><span class="help">Forcer les gens à accepter les conditions de vente avant de créer un compte.</span>';
$_['entry_checkout']             = 'Accepter conditions avant encaissement: <br /><span class="help">Forcer les gens à accepter les conditions de vente avant l\'encaissement.</span>';
$_['entry_order_status']         = 'État par défaut de la commande: <br /><span class="help">Paramètrer l\'état par défaut d\'une commande lors de son traitement.</span>';
$_['entry_stock_display']        = 'Afficher le stock: <br /><span class="help">Afficher les quantités disponibles en stock sur la page du produit.</span>';
$_['entry_stock_check']          = 'Informer si rupture de stock: <br /><span class="help">Afficher un message sur la page du panier si le produit est en rupture de stock.</span>';
$_['entry_stock_checkout']       = 'Commander avec rupture de stock: <br /><span class="help">Permettre aux clients de passer commande même si le produit commandé n\'est pas en stock.</span>';
$_['entry_stock_subtract']       = 'Soustraire du stock: <br /><span class="help">Soustraire du stock la quantité d\'un produit commandé lorsque la commande est traitée.</span>';
$_['entry_stock_status']         = 'État du stock:';
$_['entry_download']             = 'Autoriser les téléchargements:';
$_['entry_download_status']      = 'État de la commande pour permettre de télécharger: <br /><span class="help">Paramétrer l\'état que les commandes clients doivent atteindre avant qu\'il ne soit permis d\'accèder à leurs produits téléchargeables.</span>';
$_['entry_icon']                 = 'Icône:';
$_['entry_image_thumb']          = 'Taille des vignettes des produits:';
$_['entry_image_popup']          = 'Taille des popups des produits:';
$_['entry_image_category']       = 'Taille des images des listes de catégories:';
$_['entry_image_product']        = 'Taille des images des listes de produits:';
$_['entry_image_additional']     = 'Taille des images supplémentaires de produit:';
$_['entry_image_related']        = 'Taille des images des produits apparentés:';
$_['entry_image_cart']           = 'Taille des images des produits dans le panier:';
$_['entry_mail_protocol']        = 'Protocole de mail: <span class="help">Choisissez uniquement \'Mail\' sauf si votre hébergeur a désactivé la fonction php "mail()".';
$_['entry_smtp_host']            = 'Serveur SMTP:';
$_['entry_smtp_username']        = 'Nom d\'utilisateur du SMTP:';
$_['entry_smtp_password']        = 'Mot de passe du SMTP:';
$_['entry_smtp_port']            = 'Port du SMTP:';
$_['entry_smtp_timeout']         = 'Latence du SMTP:';
$_['entry_ssl']                  = 'Utiliser le SSL: <br /><span class="help">Pour utiliser le SSL, vérifiez avec votre hébergeur si un certificat SSL est installé et ensuite ajoutez l\'adresse du SSL à votre fichier de configuration.</span>';
$_['entry_encryption']           = 'Clé de cryptage: <br /><span class="help">Veuillez fournir une clé secrète qui sera utilisée pour crypter les informations privées lors du traitement des commandes.</span>';
$_['entry_seo_url']              = 'Utiliser SEO d\'URL:<br /><span class="help">Pour utiliser les URL SEO, le "mod-rewrite" des URLs pour Apache doit être installé et vous devez renommer le htaccess.txt en .htaccess.</span>';
$_['entry_compression']          = 'Niveau de compression en sortie: <br /><span class="help">GZIP pour un transfert plus efficace à vos clients. Le niveau de compression doit être compris entre 0 et 9.</span>';
$_['entry_error_display']        = 'Afficher les erreurs à l\'écran:';
$_['entry_error_log']            = 'Écrire les erreurs dans un journal:';
$_['entry_error_filename']       = 'Nom du fichier du journal des erreurs:';

// Error
$_['error_permission']           = 'Attention: Vous n\'avez pas la permission de modifier les paramètres !';
$_['error_store']                = 'Le nom de la boutique doit être composé de 3 à 32 caractères !';
$_['error_title']                = 'Le titre de la page doit être composé de 3 à 32 caractères !';
$_['error_owner']                = 'Le propiétaire de la boutique doit être composé de 3 à 64 caractères !';
$_['error_address']              = 'L\'adresse de la boutique doit être composée de 10 à 128 caractères !';
$_['error_email']                = 'L\'adresse e-mail ne semble pas valide !';
$_['error_telephone']            = 'Le téléphone doit être composé de 3 à 32 caractères !';
$_['error_error_filename']       = 'Nom du fichier du journal des erreurs requis !';
?>