<?php


namespace App\Command;


use App\Dto\Feed;
use App\Entity\ExternalFeed;
use App\Entity\ExternalFeedKeyWord;
use App\Repository\ExternalFeedRepository;
use App\Service\FrequentWordsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTheRegisterFeedDataCommand extends Command
{
    const FEED_URL = 'https://www.theregister.co.uk/software/headlines.atom';

    protected static $defaultName = 'app:import-the-register-feed';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ExternalFeedRepository
     */
    private $externalFeedRepository;
    /**
     * @var FrequentWordsService
     */
    private $frequentWordsService;


    /**
     * ImportTheRegisterFeedDataCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param ExternalFeedRepository $externalFeedRepository
     * @param FrequentWordsService $frequentWordsService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ExternalFeedRepository $externalFeedRepository,
        FrequentWordsService $frequentWordsService
    )
    {
        $this->entityManager = $entityManager;
        $this->externalFeedRepository = $externalFeedRepository;
        $this->frequentWordsService = $frequentWordsService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('imports data from TheRegister feed')

            ->setHelp('This command will fill store with TheRegister feed');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = simplexml_load_file(static::FEED_URL);
        $feed = Feed::createFromXml($content);

        $externalFeed = $this->externalFeedRepository->findOneBy([
            'source' => ExternalFeed::SOURCE_THE_REGISTER,
            'external_id' => $feed->getId(),
        ]);

        if (! is_null($externalFeed)) {
            $output->writeln('TheRegister feed didn\'t change');
            return 0;
        }

        $externalFeed = new ExternalFeed();

        $externalFeed->setExternalId($feed->getId());
        $externalFeed->setSource(ExternalFeed::SOURCE_THE_REGISTER);
        $externalFeed->setUpdated($feed->getUpdated());
        $externalFeed->setBody($feed->toJson());

        $this->entityManager->persist($externalFeed);

        $words = $this->frequentWordsService->getWords($feed);
        foreach ($words as $word => $count) {
            $externalFeedKeyWord = new ExternalFeedKeyWord();

            $externalFeedKeyWord->setWord($word);
            $externalFeedKeyWord->setCount($count);
            $externalFeedKeyWord->setExternalFeed($externalFeed);

            $this->entityManager->persist($externalFeedKeyWord);
        }

        $this->entityManager->flush();

        $output->writeln('TheRegister feed saved');
    }
}