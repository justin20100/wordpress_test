<?php

class PrimaryMenuItem
{
    protected $post;

    public $url;
    public $title;
    public $label;
    public $subitems = [];

    function __construct($post)
    {
        $this->post = $post;

        $this->url = $post->url;
        $this->label = $post->title;
        $this->title = $post->attr_title;
    }

    public function hasSubItems()
    {
        // Regarder s'il y a des éléments dans $this->subitems. Si c'est le cas,
        // cet $item possède effectivement un sous-menu.
        return ! empty($this->subitems);
    }

    public function isSubItem()
    {
        // Regarder si cet $item possède un identifiant de post parent.
        // Si c'est le cas, cet $item est un sous-item.
        return boolval($this->getParentId());
    }

    public function isParentFor(PrimaryMenuItem $instance)
    {
        // Regarder si l'élément fourni possède une référence de "parent"
        // qui correspond à l'identifiant de cet $item.
        return ($this->post->ID == $instance->getParentId());
    }

    public function getParentId()
    {
        // Retourner l'identifiant (référence) de l'item parent.
        return $this->post->menu_item_parent;
    }

    public function addSubItem(PrimaryMenuItem $instance)
    {
        // Ajouter l'instance "enfant" fournie au tableau de sous-éléments.
        $this->subitems[] = $instance;
    }

    public function getBemClasses($base)
    {
        $modifiers = [];

        $icon = get_field('icon', $this->post);

        if($icon) {
            $modifiers[] = $icon;
        }

        if($this->post->object_id == get_queried_object_id()) {
            $modifiers[] = 'current';
        }

        if(in_array('menu-item-type-custom', $this->post->classes)) {
            $modifiers[] = 'custom';
        }

        $value = $base;

        foreach ($modifiers as $modifier) {
            $value .= ' ' . $base . '--' . $modifier;
        }

        return $value;
    }
}