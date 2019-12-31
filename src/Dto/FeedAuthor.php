<?php


namespace App\Dto;


use RuntimeException;
use SimpleXMLElement;

class FeedAuthor
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;
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
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
    public function getEmail(): string
    {
        return $this->email;
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
     * @return FeedAuthor
     */
    public static function createFromXml(SimpleXMLElement $elem)
    {
        $feedAuthor = new self();
        if (empty($elem->name)) {
            throw new RuntimeException('no name in the feed->author');
        }
        $feedAuthor->setName($elem->name);

        if (empty($elem->email)) {
            throw new RuntimeException('no email in the feed->author');
        }
        $feedAuthor->setEmail($elem->name);

        if (empty($elem->uri)) {
            throw new RuntimeException('no uri in the entry->author');
        }
        $feedAuthor->setUri($elem->uri);

        return $feedAuthor;
    }
}