<?php

declare(strict_types=1);

App::uses('BackgroundJobsTool', 'Tools');

class StartWorkerShell extends AppShell
{
    /** @var BackgroundJobsTool */
    private $BackgroundJobsTool;

    /** @var Worker */
    private $worker;

    /** @var int */
    private $maxExecutionTime;

    private const DEFAULT_MAX_EXECUTION_TIME = 86400; // 1 day

    public $tasks = ['ConfigLoad'];

    public function initialize(): void
    {
        parent::initialize();
        $this->BackgroundJobsTool = new BackgroundJobsTool(Configure::read('SimpleBackgroundJobs'));
    }

    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser
            ->addArgument('queue', [
                'help' => sprintf(
                    'Name of the queue to process. Must be one of [%]',
                    implode(', ', $this->BackgroundJobsTool->getQueues())
                ),
                'choices' => $this->BackgroundJobsTool->getQueues(),
                'required' => true
            ])
            ->addOption(
                'maxExecutionTime',
                [
                    'help' => 'Worker maximum execution time (seconds) before it self-destruct.',
                    'default' => self::DEFAULT_MAX_EXECUTION_TIME,
                    'required' => false
                ]
            );

        return $parser;
    }

    public function main(): void
    {
        $this->worker = new Worker(
            [
                'pid' => getmypid(),
                'queue' => $this->args[0],
                'user' => $this->whoami()
            ]
        );

        $this->maxExecutionTime = (int)$this->params['maxExecutionTime'];

        CakeLog::info("[WORKER PID: {$this->worker->pid()}][{$this->worker->queue()}] - starting to process background jobs...");

        while (true) {
            $this->ConfigLoad->execute();
            $this->checkMaxExecutionTime();

            $job = $this->BackgroundJobsTool->dequeue($this->worker->queue());

            if ($job) {
                CakeLog::info("[WORKER PID: {$this->worker->pid()}][{$this->worker->queue()}] - launching job with id: {$job->id()} ...");

                try {
                    $this->BackgroundJobsTool->run($job);
                } catch (Exception $exception) {
                    CakeLog::error("[WORKER PID: {$this->worker->pid()}][{$this->worker->queue()}] - job id: {$job->id()} failed with exception: {$exception->getMessage()}");
                    $job->status  = BackgroundJob::STATUS_FAILED;
                    $this->BackgroundJobsTool->update($job);
                }
            }
        }
    }

    /**
     * Checks if worker maximum execution time is reached, and exits if so.
     *
     * @return void
     */
    private function checkMaxExecutionTime(): void
    {
        if ((time() - $this->worker->createdAt()) > $this->maxExecutionTime) {
            CakeLog::info("[WORKER PID: {$this->worker->pid()}][{$this->worker->queue()}] - worker max execution time reached, exiting gracefully worker...");
            exit;
        }
    }

    private function whoami(): string
    {
        if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
            return posix_getpwuid(posix_geteuid())['name'];
        } else {
            return trim(shell_exec('whoami'));
        }
    }
}