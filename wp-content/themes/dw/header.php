<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header class="header">
    <h1 class="header__title"><?= get_bloginfo('name'); ?></h1>
    <p class="header__tagline"><?= get_bloginfo('description'); ?></p>
    <nav class="header__nav nav">
        <h2 class="nav__title">Navigation principale</h2>
        <?php // wp_nav_menu([
        //     'menu' => 'primary',
        //     'container_class' => 'nav__container',
        //     'menu_class' => 'nav__links',
        //     'menu_id' => 'navigation',
        //     'walker' => new PrimaryMenuWalker(),
        // ]); ?>

        <ul class="nav__container">
            <?php foreach(dw_get_menu_items('primary') as $link): ?>
                <li class="<?= $link->getBemClasses('nav__item'); ?>">
                    <a  href="<?= $link->url; ?>"
                        <?= $link->title ? ' title="' . $link->title . '"' : ''; ?>
                        class="nav__link"
                    >
                        <?= $link->label; ?>
                    </a>
                    <?php if($link->hasSubItems()): ?>
                        <ul class="nav__subcontainer">
                            <?php foreach($link->subitems as $sub): ?>
                                <li class="<?= $sub->getBemClasses('nav__subitem'); ?>">
                                    <a  href="<?= $sub->url; ?>"
                                        <?= $sub->title ? ' title="' . $sub->title . '"' : ''; ?>
                                        class="nav__link"
                                    >
                                        <?= $sub->label; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>