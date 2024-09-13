<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\NotificationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Notifier\Notification\Notification;

#[AsCommand(
    name: 'app:send-update-notification',
    description: 'Sends a notification to all users to let them know that Boustigraille has been updated.',
)]
class SendUpdateNotificationCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private NotificationService $notificationService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $notification = new Notification(
            'Mise à jour de Boustigraille',
            ['browser']
        );
        $notification->content('**Boustigraille a été mis à jour !**

Profitez des nouvelles *fonctionnalités*. Vous pouvez également aller sur le [GitHub du projet](https://github.com/LucileDT/boustigraille/issues) pour toute suggestion d\'amélioration ou rapport de bug.

Merci d\'utiliser Boustigraille !');

        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $this->notificationService->sendNotification($notification, $user);
        }

        $io->success('Notifications sent successfully');

        return Command::SUCCESS;
    }
}
