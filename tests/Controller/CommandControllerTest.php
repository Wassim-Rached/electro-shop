<?php

namespace App\Test\Controller;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/command/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Command::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Command index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'command[status]' => 'Testing',
            'command[created_at]' => 'Testing',
            'command[delivred_at]' => 'Testing',
            'command[quantity]' => 'Testing',
            'command[total]' => 'Testing',
            'command[product]' => 'Testing',
            'command[for_user]' => 'Testing',
            'command[address]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Command();
        $fixture->setStatus('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setDelivred_at('My Title');
        $fixture->setQuantity('My Title');
        $fixture->setTotal('My Title');
        $fixture->setProduct('My Title');
        $fixture->setFor_user('My Title');
        $fixture->setAddress('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Command');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Command();
        $fixture->setStatus('Value');
        $fixture->setCreated_at('Value');
        $fixture->setDelivred_at('Value');
        $fixture->setQuantity('Value');
        $fixture->setTotal('Value');
        $fixture->setProduct('Value');
        $fixture->setFor_user('Value');
        $fixture->setAddress('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'command[status]' => 'Something New',
            'command[created_at]' => 'Something New',
            'command[delivred_at]' => 'Something New',
            'command[quantity]' => 'Something New',
            'command[total]' => 'Something New',
            'command[product]' => 'Something New',
            'command[for_user]' => 'Something New',
            'command[address]' => 'Something New',
        ]);

        self::assertResponseRedirects('/command/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getDelivred_at());
        self::assertSame('Something New', $fixture[0]->getQuantity());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getProduct());
        self::assertSame('Something New', $fixture[0]->getFor_user());
        self::assertSame('Something New', $fixture[0]->getAddress());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Command();
        $fixture->setStatus('Value');
        $fixture->setCreated_at('Value');
        $fixture->setDelivred_at('Value');
        $fixture->setQuantity('Value');
        $fixture->setTotal('Value');
        $fixture->setProduct('Value');
        $fixture->setFor_user('Value');
        $fixture->setAddress('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/command/');
        self::assertSame(0, $this->repository->count([]));
    }
}
