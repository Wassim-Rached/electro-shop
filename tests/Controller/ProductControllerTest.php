<?php

namespace App\Test\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/product/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Product::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'product[status]' => 'Testing',
            'product[price]' => 'Testing',
            'product[quantity]' => 'Testing',
            'product[description]' => 'Testing',
            'product[name]' => 'Testing',
            'product[isUsed]' => 'Testing',
            'product[created_by]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setStatus('My Title');
        $fixture->setPrice('My Title');
        $fixture->setQuantity('My Title');
        $fixture->setDescription('My Title');
        $fixture->setName('My Title');
        $fixture->setIsUsed('My Title');
        $fixture->setCreated_by('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setStatus('Value');
        $fixture->setPrice('Value');
        $fixture->setQuantity('Value');
        $fixture->setDescription('Value');
        $fixture->setName('Value');
        $fixture->setIsUsed('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'product[status]' => 'Something New',
            'product[price]' => 'Something New',
            'product[quantity]' => 'Something New',
            'product[description]' => 'Something New',
            'product[name]' => 'Something New',
            'product[isUsed]' => 'Something New',
            'product[created_by]' => 'Something New',
        ]);

        self::assertResponseRedirects('/product/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getQuantity());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getIsUsed());
        self::assertSame('Something New', $fixture[0]->getCreated_by());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setStatus('Value');
        $fixture->setPrice('Value');
        $fixture->setQuantity('Value');
        $fixture->setDescription('Value');
        $fixture->setName('Value');
        $fixture->setIsUsed('Value');
        $fixture->setCreated_by('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/product/');
        self::assertSame(0, $this->repository->count([]));
    }
}
