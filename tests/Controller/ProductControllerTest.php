<?php

namespace App\Test\Controller;

use App\Entity\Product;
use Doctrine\DBAL\Exception;
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

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();

        if (empty($this->manager->getConnection()->createSchemaManager()->listTableNames())) {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->manager);
            $metadata = $this->manager->getMetadataFactory()->getAllMetadata();
            $schemaTool->updateSchema($metadata);
        }

        $this->manager->getConnection()->beginTransaction();
        $this->manager->getConnection()->setAutoCommit(false);

        $this->repository = $this->manager->getRepository(Product::class);
        foreach ($this->repository->findAll() as $entity) {
            $this->manager->remove($entity);
        }
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
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');
    }
}
