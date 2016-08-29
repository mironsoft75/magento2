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
    const EMAIL_ARGUMENT = 'email';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('user:resetpass')
            ->setDescription('User reset password')
            ->setDefinition([
                new InputArgument(
                    self::EMAIL_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'Email'
                ),
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument(self::EMAIL_ARGUMENT);
        if (is_null($email)) {
            throw new \InvalidArgumentException('Argument ' . self::EMAIL_ARGUMENT . ' is missing.');
        }
        if (!\Zend_Validate::is($email, 'EmailAddress')) {
            throw new \InvalidArgumentException('Argument ' . self::EMAIL_ARGUMENT . ' is not valid email.');
        }

/*
        $this->customerAccountManagement->initiatePasswordReset(
            $email,
            AccountManagement::EMAIL_RESET
        );
*/
           try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET
                );
            } catch (NoSuchEntityException $exception) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
            } catch (SecurityViolationException $exception) {
                throw new \InvalidArgumentException('Argument ' . self::EMAIL_ARGUMENT . $exception->getMessage());
            } catch (\Exception $exception) {
                throw new \InvalidArgumentException('Argument ' . self::EMAIL_ARGUMENT . ' We\'re unable to send the password reset email.' . $exception->getMessage());
            }


        $output->writeln('<info>We are going to send reset password email to: ' . $email . '!</info>');
    }
}
