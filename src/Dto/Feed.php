<?php


namespace App\Dto;

use DateTime;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Feed
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $link;
    /**
     * @var string
     */
    private $rights;
    /**
     * @var FeedAuthor
     */
    private $author;
    /**
     * @var string
     */
    private $icon;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $logo;
    /**
     * @var int
     */
    private $updated;
    /**
     * @var FeedEntry[]
     */
    private $entryCollection;


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
     * @return string
     */
    public function getUpdated(): string
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
     * @param FeedAuthor $author
     */
    public function setAuthor(FeedAuthor $author): void
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
     * @param string $rights
     */
    public function setRights(string $rights): void
    {
        $this->rights = $rights;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @param FeedEntry $entry
     */
    public function appendEntryCollection(FeedEntry $entry): void
    {
        $this->entryCollection[] = $entry;
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
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getRights(): string
    {
        return $this->rights;
    }

    /**
     * @return FeedAuthor
     */
    public function getAuthor(): FeedAuthor
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @return FeedEntry[]
     */
    public function getEntryCollection(): array
    {
        return $this->entryCollection;
    }

    /**
     * @param SimpleXMLElement $elem
     * @return Feed
     * @throws RuntimeException
     * @throws Exception
     */
    public static function createFromXml(SimpleXMLElement $elem)
    {
        $feed = new self();

        if (empty($elem->id)) {
            throw new RuntimeException('no id in the feed');
        }
        $feed->setId($elem->id);

        if (empty($elem->title)) {
            throw new RuntimeException('no title in the feed');
        }
        $feed->setTitle($elem->title);

        if (! isset($elem->link)) {
            throw new RuntimeException('no link in the feed');
        }
        $href = '';
        foreach ($elem->link as $link) {
            if (empty($link->attributes()['href'])) {
                throw new RuntimeException('no link[0]->attributes->href in the feed');
            }
            $href = $link->attributes()['href'];
        }

        $feed->setLink($href);

        if (empty($elem->rights)) {
            throw new RuntimeException('no rights in the feed');
        }
        $feed->setRights($elem->rights);

        if (empty($elem->author)) {
            throw new RuntimeException('no author in the feed');
        }
        $feed->setAuthor(FeedAuthor::createFromXml($elem->author));

        if (empty($elem->icon)) {
            throw new RuntimeException('no icon in the feed');
        }
        $feed->setIcon($elem->icon);

        if (empty($elem->subtitle)) {
            throw new RuntimeException('no subtitle in the feed');
        }
        $feed->setSubtitle($elem->subtitle);

        if (empty($elem->logo)) {
            throw new RuntimeException('no logo in the feed');
        }
        $feed->setLogo($elem->logo);

        if (empty($elem->updated)) {
            throw new RuntimeException('no updated in the feed');
        }
        $updated = (new DateTime($elem->updated))->getTimestamp();
        if ($updated === null) {
            throw new InvalidArgumentException("updated ({$elem->updated}) should be datetime");
        }
        $feed->setUpdated($updated);


        if (empty($elem->entry)) {
            throw new RuntimeException('no entries in the feed');
        }
        foreach ($elem->entry as $entryXml) {
            $feed->appendEntryCollection(FeedEntry::createFromXml($entryXml));
        }

        return $feed;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($this, 'json');
    }
}