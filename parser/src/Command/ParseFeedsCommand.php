<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\News;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Factory\Interfaces\FactoryParserHandleGetterInterface;

/**
 * Command for parsing news feeds.
 */
class ParseFeedsCommand extends Command
{
    private const BATCH_SIZE = 15;

    /**
     * @var FactoryParserHandleGetterInterface
     */
    private FactoryParserHandleGetterInterface $parserHandleFactory;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param FactoryParserHandleGetterInterface $parserHandleFactory Contains news feed parsers, that would be invoked one by one.
     * @param EntityManagerInterface             $entityManager       Entity manager.
     * @param LoggerInterface                    $logger              Logger for logging activities.
     * @param string|null                        $name                The name of the command; passing null means it must be set in configure().
     */
    public function __construct(
        FactoryParserHandleGetterInterface $parserHandleFactory,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);

        $this->parserHandleFactory = $parserHandleFactory;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Invokes web-scrapping.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output);
        $output->writeln(['Parsing news feeds']);

        $handles = $this->parserHandleFactory->getAll();
        $newsRepository = $this->entityManager->getRepository(News::class);

        foreach ($handles as $handle) {
            try {
                $parsedNews = $handle->doParse();
                $progressBar->setMaxSteps(count($parsedNews));
                $progressBar->start();

                while ($news = array_splice($parsedNews, 0, self::BATCH_SIZE)) {
                    try {
                        $duplicates = $newsRepository->findExistingExternalIdsByExternalKeys(array_keys($news));

                        if (count($duplicates) > 0) {
                            $output->writeln(
                                \sprintf(
                                    '<question>%d duplicates found, that won\'t be saved.</question>',
                                    count($duplicates)
                                )
                            );
                        }

                        $this->writeToDatabase(array_diff_key($news, array_flip($duplicates)), $progressBar);
                    } catch (\Throwable $exception) {
                        $progressBar->finish();
                        $this->logger->error($exception->getMessage());
                    }
                }

                $this->entityManager->flush();
                $progressBar->finish();
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param array       $articles
     *                                 Article news for persisting into database.
     * @param ProgressBar $progressBar
     *                                 Console progress bar.
     */
    private function writeToDatabase(array $articles, ProgressBar $progressBar): void
    {
        foreach ($articles as $article) {
            $this->entityManager->persist(
                News::create(
                    $article->getExternalId(),
                    $article->getTitle(),
                    $article->getContent(),
                    $article->getSummary(),
                    $article->getImage()
                )
            );

            $progressBar->advance();
        }

        if (count($articles) > 0) {
            $this->entityManager->flush();
        }
    }
}
