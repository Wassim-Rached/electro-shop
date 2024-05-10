<?php

namespace App\Test\Controller;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/address/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Address::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'address[address_line1]' => 'Testing',
            'address[address_line2]' => 'Testing',
            'address[postalCode]' => 'Testing',
            'address[city]' => 'Testing',
            'address[phoneNumber]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setAddress_line1('My Title');
        $fixture->setAddress_line2('My Title');
        $fixture->setPostalCode('My Title');
        $fixture->setCity('My Title');
        $fixture->setPhoneNumber('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setAddress_line1('Value');
        $fixture->setAddress_line2('Value');
        $fixture->setPostalCode('Value');
        $fixture->setCity('Value');
        $fixture->setPhoneNumber('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'address[address_line1]' => 'Something New',
            'address[address_line2]' => 'Something New',
            'address[postalCode]' => 'Something New',
            'address[city]' => 'Something New',
            'address[phoneNumber]' => 'Something New',
        ]);

        self::assertResponseRedirects('/address/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getAddress_line1());
        self::assertSame('Something New', $fixture[0]->getAddress_line2());
        self::assertSame('Something New', $fixture[0]->getPostalCode());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setAddress_line1('Value');
        $fixture->setAddress_line2('Value');
        $fixture->setPostalCode('Value');
        $fixture->setCity('Value');
        $fixture->setPhoneNumber('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/address/');
        self::assertSame(0, $this->repository->count([]));
    }
}
