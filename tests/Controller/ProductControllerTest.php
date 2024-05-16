<?php

namespace App\Test\Controller;

use App\Entity\ApplicationUser;
use App\Entity\Product;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private ApplicationUser $user;

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

        $this->repository = $this->manager->getRepository(Product::class);

        $user = new ApplicationUser();
        $user->setUsername('test');
        $user->setPassword('test');
        $this->manager->persist($user);
        $this->user = $user;
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
        $crawler = $this->client->request('GET', '/product');

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');
    }

    public function testNewProductSubmission()
    {
        // given
        $crawler = $this->client->request('POST', '/product/new');
        $this->client->loginUser($this->user);
        $photoFile = new UploadedFile(
            __DIR__.'/../../public/uploads/test.jpeg',
            'test.jpeg',
            'image/jpeg'
        );
        $form = $crawler->selectButton('Save')->form();
        $form['product1[name]'] = 'Product Name';
        $form['product1[description]'] = 'Product Description';
        $form['product1[quantity]'] = 10;
        $form['product1[price]'] = 100;
        $form['product1[isUsed]'] = false;
        $form['product1[photo]']->upload($photoFile);

        // when
        $this->client->submit($form);

        // then
        $prod = $this->repository->findOneBy(['name' => 'Product Name']);
        $this->assertNotNull($prod);
    }

}
