<?php

namespace ByDN\Cron\Console;

class Schedule implements \ByDN\Framework\App\CommandInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $objectManager;

    /**
     * @var \ByDN\Framework\App\Config
     */
    private $config;

    /**
     * @var \ByDN\Cron\Model\JobFactory
     */
    private $jobFactory;

    /**
     * @var \Cron\CronExpression
     */
    private $cronExpression;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Command arguments
     *
     * @var array
     */
    private $arguments = [];

    /**
     * @param \Psr\Container\ContainerInterface $objectManager
     * @param \Cron\CronExpression $cronExpression
     * @param \ByDN\Framework\App\Config $config
     * @param \ByDN\Cron\Model\JobFactory $jobFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Container\ContainerInterface $objectManager,
        \Cron\CronExpression $cronExpression,
        \ByDN\Framework\App\Config $config,
        \ByDN\Cron\Model\JobFactory $jobFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->objectManager = $objectManager;
        $this->cronExpression = $cronExpression;
        $this->config = $config;
        $this->jobFactory = $jobFactory;
        $this->logger = $logger;
    }

    /**
     * @param $arguments
     * @return void
     * @throws \Exception
     */
    public function run($arguments)
    {
        /** @var \ByDN\Cron\Model\Job $job */
        $job = $this->jobFactory->create();
        $job->setData([
            'job_code' => 'my_code',
            'status' => 'pending',
        ]);
        $job->save();
        $id = $job->getId();

        $job = $this->jobFactory->create()->load($id);
        $job->setExecutedAt((new \DateTime())->format('Y-m-d H:i:s'));
        $job->save();

        $this->logger->critical(print_r($job->getData(), true));
        $job->delete();

//        $this->markMissedSchedules();
//        $this->generateSchedules();
    }

    private function markMissedSchedules()
    {
        $this->logger->info(__METHOD__ . ': ini');
        $this->logger->info(__METHOD__ . ': end');
    }

    private function generateSchedules()
    {
        $this->logger->info(__METHOD__ . ': ini');

        // Cron jobs configuration
        $ahead = $this->config->getData('config/cron.schedule.ahead');

        // Process all cron jobs
        $cronjobs = $this->config->getData('crontab');
        foreach ($cronjobs as $cronjob) {

            // Skip disabled
            if (!isset($cronjob['enabled']) || !$cronjob['enabled']) {
                continue;
            }

            // Get next execution times for this cron and add to database
            $runnningTimes = $this->getRunningTimes($cronjob['schedule'], $ahead);
            foreach ($runnningTimes as $runningTime) {

            }
        }

        $this->logger->info(__METHOD__ . ': end');
    }

    /**
     * Analyze the cron expression and returns matching times in the following $minutesAhead period
     *
     * @param $cronExpression
     * @param $minutesAhead
     * @return array
     */
    private function getRunningTimes($cronExpression, $minutesAhead)
    {
        // Create a DateTime object for the current time
        $start = new \DateTime();

        // Clone the start time and add the specified number of minutes
        $end = clone $start;
        $end->add(new \DateInterval('PT' . $minutesAhead . 'M'));

        // Set the cron expression to analyzer
        $this->cronExpression->setExpression($cronExpression);

        // Iterate through each minute from the start time to the end time
        $matchingTimes = [];
        $interval = new \DateInterval('PT1M');
        $period = new \DatePeriod($start, $interval, $end);
        foreach ($period as $dateTime) {
            if ($this->cronExpression->isDue($dateTime)) {
                $matchingTimes[] = $dateTime->format('Y-m-d H:i:s');
            }
        }

        return $matchingTimes;
    }

    private function addScheduleToDatabase()
    {

    }
}