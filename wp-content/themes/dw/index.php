<?php get_header(); ?>

    <main class="layout">
        <section class="layout__latest latest">
            <h2 class="latest__title">Mes derniers articles</h2>
            <div class="latest__container">
                <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <article class="post">
                        <a href="<?= get_the_permalink(); ?>" class="post__link">Lire l'article "<?= get_the_title(); ?>"</a>
                        <div class="post__card">
                            <header class="post__head">
                                <h3 class="post__title"><?= get_the_title(); ?></h3>
                                <p class="post__meta">Publié par <?= get_the_author(); ?> le <time class="post__date" datetime="<?= get_the_date('c'); ?>"><?= get_the_date(); ?></time></p>
                            </header>
                            <figure class="post__fig">
                                <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'post__thumb']); ?>
                            </figure>
                            <div class="post__excerpt">
                                <p><?= get_the_excerpt(); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endwhile; else: ?>
                    <!-- Il n'y a pas d'articles à afficher -->
                <?php endif; ?>
            </div>
        </section>

        <section class="layout__trips trips">
            <h2 class="trips__title">Mes derniers voyages</h2>
            <div class="trips__container">
                <?php if(($trips = dw_get_trips(3))->have_posts()): while($trips->have_posts()): $trips->the_post(); ?>
                    <article class="trip">
                        <a href="<?= get_the_permalink(); ?>" class="trip__link">Lire le récit de voyage "<?= get_the_title(); ?>"</a>
                        <div class="trip__card">
                            <header class="trip__head">
                                <h3 class="trip__title"><?= get_the_title(); ?></h3>
                                <p class="trip__date"><time class="trip__time" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))); ?>">
                                        <?= ucfirst(date_i18n('F, Y', strtotime(get_field('departure_date', false, false)))); ?>
                                    </time></p>
                            </header>
                            <figure class="trip__fig">
                                <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'trip__thumb']); ?>
                            </figure>
                        </div>
                    </article>
                <?php endwhile; else: ?>
                    <p class="trips__empty">Il n'y a pas de voyages à vous raconter...</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

<?php get_footer(); ?>