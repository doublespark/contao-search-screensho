<?php

namespace Doublespark\ContaoSearchScreenshot\Command;

use Contao\CoreBundle\Framework\ContaoFramework;
use Doublespark\ContaoSearchScreenshot\Cron\Automator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScreenshotCronCommand extends Command
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * ScreenshotCronCommand constructor.
     *
     * @param ContaoFramework $framework
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('search-screenshot:generate')->setDescription('Generates search screenshots');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->framework->initialize();

        $output->writeln('Generating screenshots...');

        $automator = new Automator();
        $automator->updateScreenshots();

        $output->writeln('Screenshots generated.');
    }
}