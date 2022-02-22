<?php

namespace App\Command;

use App\Service\CategoryService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateCategoryCommand extends Command
{
    /** @var CategoryService */
    private $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-category')
            ->setDescription('Creates a new category.')
            ->setHelp('This command allows you to add new category in db...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the category.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Category Creator',
            '================',
            '',
        ]);

        $output->writeln(sprintf('Name: %s', $input->getArgument('name')));

        $this->categoryService->create($input->getArgument('name'));

        $output->writeln('<fg=green>Category successfully created!</>');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('<question>Please choose a name: </question>');

            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new Exception('Name can not be empty');
                }

                return $name;
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('name', $answer);
        }
    }
}