<?php

namespace App\Controller;

use App\Entity\ExternalFeed;
use App\Repository\ExternalFeedKeyWordRepository;
use App\Repository\ExternalFeedRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


class RssController extends AbstractFOSRestController
{
    /**
     * @var ExternalFeedRepository
     */
    private $externalFeedRepository;
    /**
     * @var ExternalFeedKeyWordRepository
     */
    private $externalFeedKeyWordRepository;

    /**
     * ImportTheRegisterFeedDataCommand constructor.
     * @param ExternalFeedRepository $externalFeedRepository
     * @param ExternalFeedKeyWordRepository $externalFeedKeyWordRepository
     */
    public function __construct(
        ExternalFeedRepository $externalFeedRepository,
        ExternalFeedKeyWordRepository $externalFeedKeyWordRepository
    )
    {
        $this->externalFeedRepository = $externalFeedRepository;
        $this->externalFeedKeyWordRepository = $externalFeedKeyWordRepository;
    }

    /**
     * @Rest\Get("/rss/feed", name="rss_feed")
     * @return View
     * @throws \Exception
     */
    public function showFeed()
    {
        $externalFeed = $this->externalFeedRepository->findOneLastBySource(ExternalFeed::SOURCE_THE_REGISTER);

        $feedArray = json_decode($externalFeed->getBody(), 1);

        return View::create([
            'feed' => $feedArray
        ], Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/rss/keywords", name="rss_keywords")
     * @return View
     * @throws \Exception
     */
    public function showKeyWords()
    {
        $externalFeedKeyWords = $this->externalFeedRepository->findOneLastBySource(ExternalFeed::SOURCE_THE_REGISTER)->getExternalFeedKeyWords();
        $words = [];
        foreach ($externalFeedKeyWords as $externalFeedKeyWord) {
            $words[$externalFeedKeyWord->getWord()] = $externalFeedKeyWord->getCount();
        }

        return View::create([
            'list' => $words
        ], Response::HTTP_OK);
    }
}
