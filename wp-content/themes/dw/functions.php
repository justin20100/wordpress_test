<?php

// Charger les fichiers nécessaires
require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');
require_once(__DIR__ . '/Menus/PrimaryMenuItem.php');

// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter('use_block_editor_for_post', '__return_false');

// Activer les images sur les articles
add_theme_support('post-thumbnails');

// Enregistrer un seul custom post-type pour "nos voyages"
register_post_type('trip', [
    'label' => 'Voyages',
    'labels' => [
        'name' => 'Voyages',
        'singular_name' => 'Voyage',
    ],
    'description' => 'Tous les articles qui décrivent un voyage',
    'public' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-palmtree',
    'supports' => ['title','editor','thumbnail'],
    'rewrite' => ['slug' => 'voyages'],
]);

// enregistrer un custom post-type pour les messages de contact
register_post_type('message', [
    'label' => 'Message de contact',
    'labels' => [
        'name' => 'Messages de contact',
        'singular_name' => 'Messages de contact',
    ],
    'description' => 'Les messages envoye par le formulaire de contact',
    'public' => false,
    'show_ui' => true,
    'menu_position' => 15,
    'menu_icon' => 'dashicons-buddicons-pm',
    'capabilities' => [
        'create_posts'=> false,
        'read_post'=>true,
        'read_post_private'=>true,
    ],
]);
// Récupérer les trips via une requête Wordpress
function dw_get_trips($count = 20)
{
    // 1. on instancie l'objet WP_Query
    $trips = new WP_Query([
        'post_type' => 'trip',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => $count,
    ]);

    // 2. on retourne l'objet WP_Query
    return $trips;
}

// Enregistrer les zones de menus

register_nav_menu('primary', 'Navigation principale (haut de page)');
register_nav_menu('footer', 'Navigation de pied de page');

// Fonction pour récupérer les éléments d'un menu sous forme d'un tableau d'objets

function dw_get_menu_items($location)
{
    $items = [];

    // Récupérer le menu Wordpress pour $location
    $locations = get_nav_menu_locations();

    if($locations[$location] ?? false) {
        $menu = $locations[$location];

        // Récupérer tous les éléments du menu récupéré
        $posts = wp_get_nav_menu_items($menu);

        // Formater chaque élément dans une instance de classe personnalisée
        // Boucler sur chaque $post
        foreach($posts as $post) {
            // Transformer le WP_Post en une instance de notre classe personnalisée
            $item = new PrimaryMenuItem($post);

            // Ajouter cette instance à $items ou à l'item parent si sous-menu
            if($item->isSubItem()) {
                // Ajouter $item comme "enfant" de l'item parent.
                foreach($items as $parent) {
                    if($parent->isParentFor($item)) {
                        $parent->addSubItem($item);
                    }
                }
            } else {
                // Ajouter au tableau d'éléments de niveau 0.
                $items[] = $item;
            }
        }
    }

    // Retourner un tableau d'éléments du menu formatés
    return $items;
}

// gerer l'envoi de formulaire personnalisé

add_action( 'admin_post_submit_contact_form', 'dw_handle_submit_contact_form' );

function dw_handle_submit_contact_form ()
{
    $nonce = $_POST['_wpnonce'];
    if (wp_verify_nonce($nonce,'nonce_submit_contact')){
        $firstname = sanitize_text_field($_POST['firstname']);
        $lastname = sanitize_text_field($_POST['firstname']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_text_field($_POST['message']);
        $rules = sanitize_text_field($_POST['rules'] ?? '');

        if ($firstname && $lastname && $email && $message){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                if ($rules === '1'){
                    $id = wp_insert_post([
                        'post_title'=>'Messages de ' . $firstname . ' ' . $lastname,
                        'post_type'=>'message',
                        'post_content'=> $message,
                        'post_status'=> 'publish'
                    ]);
                    //generer un eail contenant l'url vers le post en question
                    $feedback = 'Bonjur, vous avez un nouveau messages.';
                    $feedback .= 'y acceder : ' . get_edit_post_link($id);
                    //envoyer l'email a l'admin
                    wp_mail(get_bloginfo('admin_email'),'Nouveau message', );
                }else{
                    // to do : retourner une valeur de validation
                }
            }else{
                // to do email non valide
            }
        }else {
            // to do retourner une valeur de validation 'champs requis
        }
    }
    // to do :  retourner un message d'erreur 'not authorizeed'



}