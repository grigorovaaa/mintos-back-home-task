<?php


namespace App\Dto;


use RuntimeException;
use SimpleXMLElement;

class FeedEntryAuthor
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $uri;

    private function __construct()
    {
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param SimpleXMLElement $elem
     * @return FeedEntryAuthor
     */
    public static function createFromXml(SimpleXMLElement $elem)
    {
        $entryAuthor = new self();
        if (empty($elem->name)) {
            throw new RuntimeException('no name in the entry->author');
        }
        $entryAuthor->setName($elem->name);

        if (empty($elem->uri)) {
            throw new RuntimeException('no uri in the entry->author');
        }
        $entryAuthor->setUri($elem->uri);

        return $entryAuthor;
    }
}