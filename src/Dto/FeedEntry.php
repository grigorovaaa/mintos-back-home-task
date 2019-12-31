<?php


namespace App\Dto;


use DateTime;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;

class FeedEntry
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var int
     */
    private $updated;
    /**
     * @var FeedEntryAuthor
     */
    private $author;
    /**
     * @var string
     */
    private $link;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $summary;

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUpdated(): int
    {
        return $this->updated;
    }

    /**
     * @param int $updated
     */
    public function setUpdated(int $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @param FeedEntryAuthor $author
     */
    public function setAuthor(FeedEntryAuthor $author): void
    {
        $this->author = $author;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return FeedEntryAuthor
     */
    public function getAuthor(): FeedEntryAuthor
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param SimpleXMLElement $elem
     * @return FeedEntry
     * @throws Exception
     */
    public static function createFromXml(SimpleXMLElement $elem)
    {
        $entry = new self();

        if (empty($elem->id)) {
            throw new RuntimeException('no id in the entry');
        }
        $entry->setId($elem->id);

        if (empty($elem->updated)) {
            throw new RuntimeException('no updated in the entry');
        }
        $updated = (new DateTime($elem->updated))->getTimestamp();
        if ($updated === null) {
            throw new InvalidArgumentException("updated ({$elem->updated}) should be datetime");
        }
        $entry->setUpdated($updated);

        if (empty($elem->author)) {
            throw new RuntimeException('no author in the entry');
        }
        $entry->setAuthor(FeedEntryAuthor::createFromXml($elem->author));

        if (! isset($elem->link)) {
            throw new RuntimeException('no link in the entry');
        }
        if (empty($elem->link->attributes()['href'])) {
            throw new RuntimeException('no link->href in the entry');
        }
        $entry->setLink($elem->link->attributes()['href']);

        if (empty($elem->title)) {
            throw new RuntimeException('no title in the entry');
        }
        $entry->setTitle($elem->title);

        if (empty($elem->summary)) {
            throw new RuntimeException('no summary in the entry');
        }

        $entry->setSummary($elem->summary);

        return $entry;
    }
}