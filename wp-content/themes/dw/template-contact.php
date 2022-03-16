<?php /* Template Name: Contact page template */ ?>
<?php get_header(); ?>
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <main class="layout contact">
        <h2 class="contact__title"><?= get_the_title(); ?></h2>
        <div class="contact__content">
            <?php the_content(); ?>
        </div>
        <form action="<?= get_home_url(); ?>/wp-admin/admin-post.php"  method="POST" class="contact__form">
            <div class="form__field">
                <labe for="firstname" class="form__label">Votre prénom</labe>
                <input type="text" name="firstname" id="firstname" class="form__input">
            </div>
            <div class="form__field">
                <labe for="lastname" class="form__label">Votre nom</labe>
                <input type="text" name="lastname" id="lastname" class="form__input">
            </div>
            <div class="form__field">
                <labe for="email" class="form__label">Votre mail</labe>
                <input type="email" name="email" id="email" class="form__input">
            </div>
            <div class="form__field">
                <labe for="phone" class="form__label">Votre numero</labe>
                <input type="tel" name="phone" id="phone" class="form__input">
            </div>
            <div class="form__field">
                <labe for="message" class="form__label">Votre message</labe>
                <textarea name="message" id="message" cols="30" rows="10" class="form__input"></textarea>
            </div>

            <div class="form__field">
                <label for="rules" class="form__label">
                    <input type="checkbox" name="rules" id="rules">
                    <span class="form__checklabel">j'accepte les <a href="">conditions générales d'utilisation</a></span>
                </label>
            </div>
            <div class="form__actions">
                <?php wp_nonce_field('nonce_contact_form') ?>
                <input type="hidden" name="action" value="submit_contact_form">
                <button class="form__button" type="submit">Envoyer</button>
            </div>
        </form>
    </main>
    <?php endwhile; endif; ?>
<?php get_footer(); ?>