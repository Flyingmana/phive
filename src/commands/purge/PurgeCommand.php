<?php
namespace PharIo\Phive;

use PharIo\Phive\Cli;

class PurgeCommand implements CLI\Command {

    /**
     * @var PurgeCommandConfig
     */
    private $config;

    /**
     * @var PharRepository
     */
    private $repository;

    /**
     * @var CLI\Output
     */
    private $output;

    /**
     * @param PurgeCommandConfig $config
     * @param PharRepository     $repository
     * @param CLI\Output         $output
     */
    public function __construct(
        PurgeCommandConfig $config, PharRepository $repository, CLI\Output $output
    ) {
        $this->config = $config;
        $this->repository = $repository;
        $this->output = $output;
    }

    public function execute() {

        foreach ($this->repository->getUnusedPhars() as $unusedPhar) {
            $this->repository->removePhar($unusedPhar);
            $this->output->writeInfo(
                sprintf(
                    'Phar %s %s has been deleted.',
                    $unusedPhar->getName(),
                    $unusedPhar->getVersion()->getVersionString()
                )
            );
        }

    }

}
