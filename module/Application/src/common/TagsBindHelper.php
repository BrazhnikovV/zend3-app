<?php

namespace Application\common;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TagsBindHelper
 * @package Application\common
 */
class TagsBindHelper
{
    /**
     * getAttachedTags - получить коллекцию сущностей тега
     * на основании выбранных тегов и всей существующей коллекциии
     * @param $selectedTags - выбранные теги
     * @param $allTags - все теги
     * @return array|ArrayCollection
     */
    static public function getAttachedTags($selectedTags, $allTags) {

        $tags = new ArrayCollection();

        foreach ( $allTags as $tag ) {
            foreach ( $selectedTags as $selectTag ) {
                if ((int)$selectTag == $tag->getId() ) {
                    $tags[] = $tag;
                }
            }
        }

        return $tags;
    }
}
