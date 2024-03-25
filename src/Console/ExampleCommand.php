<?php

namespace App\Console;

use App\Database\CreateDb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Database\Db;
use GuzzleHttp\Client;

final class ExampleCommand extends Command
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('example');
        $this->setDescription('A sample command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $db = new Db();
        $pdo = $db->connect();
        try {
            $createDb = new CreateDb();
            $createDb= $createDb->createDb($pdo);

            $client = new Client();
            $postsResponse = $client->get('https://jsonplaceholder.typicode.com/posts');
            $posts = json_decode($postsResponse->getBody(), true);

            foreach ($posts as $post) {
                $stmt = $pdo->prepare("INSERT INTO posts (userId, id, title, body) VALUES (?, ?, ?, ?)");
                $stmt->execute([$post['userId'], $post['id'], $post['title'], $post['body']]);
            }

            $commentsResponse = $client->get('https://jsonplaceholder.typicode.com/comments');
            $comments = json_decode($commentsResponse->getBody(), true);

            foreach ($comments as $comment) {
                $stmt = $pdo->prepare("INSERT INTO comments (postId, id, name, email, body) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$comment['postId'], $comment['id'], $comment['name'], $comment['email'], $comment['body']]);
            }

            $output->writeln(sprintf('<info> Data created successfully </info>'));

        }catch (\Exception $exception){
            $output->writeln($exception->getMessage());
        }

        return 0;
    }
}