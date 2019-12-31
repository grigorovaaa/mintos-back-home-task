<?php
namespace App\DataFixtures\FunctionalTest;

use App\Entity\ExternalFeed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use DateTime;

class TheRegisterFeedFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $externalFeed = new ExternalFeed();
        $externalFeed->setExternalId(1);
        $externalFeed->setSource(ExternalFeed::SOURCE_THE_REGISTER);
        $externalFeed->setUpdated((new DateTime())->getTimestamp());

        $jsonFeed = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'the_register_feed.json');
        $externalFeed->setBody($jsonFeed);
        $manager->persist($externalFeed);

        $manager->flush();
    }
}
