<?php

namespace App\Tests\Controller;

use App\Entity\Contest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ContestControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $contestRepository;
    private string $path = '/contest/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->contestRepository = $this->manager->getRepository(Contest::class);

        foreach ($this->contestRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contest index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'contest[code]' => 'Testing',
            'contest[name]' => 'Testing',
            'contest[description]' => 'Testing',
            'contest[beginAt]' => 'Testing',
            'contest[finishAt]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->contestRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contest();
        $fixture->setCode('My Title');
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setBeginAt('My Title');
        $fixture->setFinishAt('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contest');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contest();
        $fixture->setCode('Value');
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setBeginAt('Value');
        $fixture->setFinishAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'contest[code]' => 'Something New',
            'contest[name]' => 'Something New',
            'contest[description]' => 'Something New',
            'contest[beginAt]' => 'Something New',
            'contest[finishAt]' => 'Something New',
        ]);

        self::assertResponseRedirects('/contest/');

        $fixture = $this->contestRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getBeginAt());
        self::assertSame('Something New', $fixture[0]->getFinishAt());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contest();
        $fixture->setCode('Value');
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setBeginAt('Value');
        $fixture->setFinishAt('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/contest/');
        self::assertSame(0, $this->contestRepository->count([]));
    }
}
