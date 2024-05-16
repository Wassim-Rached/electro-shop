<?php

namespace App\Test\Controller;

use App\Entity\ApplicationUser;
use App\Entity\Command;
use App\Entity\Product;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();

        $this->manager->getConnection()->beginTransaction();
        $this->manager->getConnection()->setAutoCommit(false);

        $this->repository = $this->manager->getRepository(Command::class);

        $user = new ApplicationUser();
        $user->setUsername('test');
        $user->setPassword('test');
        $this->user = $user;

        $this->manager->persist($user);

        $this->manager->flush();
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        $this->manager->getConnection()->rollBack();
        parent::tearDown();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', '/command');

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');
    }

    public function testNewProductOrderSubmission()
    {
        // given
        $crawler = $this->client->request('POST', '/command/new');
        $product = $this->createAndPersistProduct($this->client->getContainer()->get('doctrine')->getManager());
        $this->client->loginUser($this->user);

        $crawler = $this->client->request('GET', '/product/' . $product->getId() . '/new');

        $form = $crawler->selectButton('submit')->form([
            'command[quantity]' => 3,
            'command[address][address_line1]' => '123 Main St',
            'command[address][address_line2]' => 'Apt 1',
            'command[address][city]' => 'Springfield',
            'command[address][postalCode]' => '12345',
            'command[address][phoneNumber]' => '555-555-5555',
        ]);

        // when
        $this->client->submit($form);

        // then
        $this->assertResponseRedirects();
        $commandRepository = $this->client->getContainer()->get('doctrine')->getRepository(Command::class);
        $command = $commandRepository->findOneBy(['product' => $product]);
        $this->assertNotNull($command);
        $this->assertEquals(3, $command->getQuantity());
        $this->assertEquals('pending', $command->getStatus());
    }

    private function createAndPersistProduct($entityManager): Product
    {
        $product = new Product();
        $product->setName('Example Product');
        $product->setPrice(100);
        $product->setQuantity(10);
        $product->setIsUsed(false);
        $product->setDescription('Example Description');
        $product->setPhoto('example.jpg');
        $product->setCreatedBy($this->user);
        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }
}
