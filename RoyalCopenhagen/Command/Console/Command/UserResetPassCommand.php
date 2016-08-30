<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace RoyalCopenhagen\Command\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;

/**
 * Class GreetingCommand
 */
class UserResetPassCommand extends Command
{

    /** @var AccountManagementInterface */
    private $customerAccountManagement;
    protected $_import_limit = 1;

    /**
     * @param AccountManagementInterface $customerAccountManagement
     */
    public function __construct(
        AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\App\State $state
    ) {
        $this->customerAccountManagement = $customerAccountManagement;
        $state->setAreaCode('frontend'); // or 'adminhtml', depending on your needs
        parent::__construct();
    }

    /**
     * Name argument
     */
    const PATH_ARGUMENT = 'path';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('royal:user:resetpass')
            ->setDescription('User reset password')
            ->setDefinition([
                new InputArgument(
                    self::PATH_ARGUMENT,
                    InputArgument::REQUIRED,
                    'The path of order CVS file.'
                ),
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Get the path of order Csv file.
        $input = $input->getArgument(self::PATH_ARGUMENT);
        $path = BP . '/' . $input;
        $time_start = microtime(true);
        $output->writeln('<info>' . 'Reading list from ' . $path . '</info>');
        $output->writeln('<info>' . 'Processing ......' . '</info>');

        $this->readCSV($path, $output);
        $time_end = microtime(true);

        //Check executed time.
        $time = $time_end - $time_start;
        $output->writeln('');
        $output->writeln('<info>' . 'Executed time: ' . $time . '<info>');
        $output->writeln("<info>Finished</info>");

    }

    /**
     * Read CSV file
     *
     * @param $csvFile
     * @param $data
     */
    protected function readCSV($csvFile, OutputInterface $output)
    {

        $row = 0;
        if (($file_handle = fopen($csvFile, 'r')) !== FALSE) {
            while (($data = fgetcsv($file_handle)) !== FALSE) {
                $row++;
                if($row < 2) continue;

                if (!feof($file_handle)) {
                    if ($data[0] != '') {
                        $email = $data[0];

                        if (is_null($email)) {
                             $output->writeln('<error>Argument: ' . $email . ' is missing.</error>');
                             continue;
                        }
                        if (!\Zend_Validate::is($email, 'EmailAddress')) {
                             $output->writeln('<error>Argument: ' . $email . ' is not valid email.</error>');
                             continue;
                        }

                         try {
                            $this->customerAccountManagement->initiatePasswordReset(
                                $email,
                                AccountManagement::EMAIL_RESET
                            );
                        } catch (NoSuchEntityException $exception) {
                            // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
                            $output->writeln('<error>No Such email: ' . $email . ' in the system.</error>');
                            continue;
                        } catch (SecurityViolationException $exception) {
                            $output->writeln('<error>Argument ' . self::PATH_ARGUMENT . $exception->getMessage(). ' .</error>');
                            continue;
                        } catch (\Exception $exception) {
                            $output->writeln('<error>Argument ' . self::PATH_ARGUMENT . ' We\'re unable to send the password reset email.' . $exception->getMessage(). ' .</error>');
                            continue;
                        }

                        $output->writeln('<info>Reset Email successfully sent to: ' . $email . '.</info>');

                    }
                }

            }

            fclose($file_handle);
        }



    }
}
