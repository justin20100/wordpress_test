<?php

class ContactFormController extends BaseFormController
{
    protected function getNonceKey() : string
    {
        return 'nonce_submit_contact';
    }

    protected function getSanitizableAttributes() : array
    {
        return [
            'firstname' => TextSanitizer::class,
            'lastname' => TextSanitizer::class,
            'email' => EmailSanitizer::class,
            'phone' => TextSanitizer::class,
            'message' => TextSanitizer::class,
            'rules' => TextSanitizer::class,
        ];
    }

    protected function getValidatableAttributes() : array
    {
        return [
            'firstname' => [RequiredValidator::class],
            'lastname' => [RequiredValidator::class],
            'email' => [RequiredValidator::class, EmailValidator::class],
            'message' => [RequiredValidator::class],
            'rules' => [AcceptedValidator::class],
        ];
    }

    protected function redirectWithErrors($errors)
    {
        // C'est pas OK, on place les erreurs de validation dans la session
        $_SESSION['contact_form_feedback'] = [
            'success' => false,
            'data' => $this->data,
            'errors' => $errors,
        ];

        // On redirige l'utilisateur vers le formulaire pour y afficher le feedback d'erreurs.
        return wp_safe_redirect(($this->data['_wp_http_referer'] ?? '') . '#contact', 302);
    }

    protected function handle()
    {
        // Traitement
        $id = wp_insert_post([
            'post_title' => 'Message de ' . $this->data['firstname'] . ' ' . $this->data['lastname'],
            'post_type' => 'message',
            'post_content' => $this->data['message'],
            'post_status' => 'publish'
        ]);

        // Générer un email contenant l'URL vers le post en question
        $feedback = 'Bonjour, Vous avez un nouveau message.<br />';
        $feedback .= 'Y accéder : ' . get_edit_post_link($id);

        // Envoyer l'email à l'admin
        wp_mail(get_bloginfo('admin_email'), 'Nouveau message !', $feedback);
    }

    protected function redirectWithSuccess()
    {
        // Ajouter le feedback positif à la session
        $_SESSION['contact_form_feedback'] = [
            'success' => true,
        ];

        return wp_safe_redirect($this->data['_wp_http_referer'] . '#contact', 302);
    }
}