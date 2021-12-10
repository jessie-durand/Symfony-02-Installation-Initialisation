<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\DataFixtures\_00SeasonFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        'episode 1',
        'episode 2',
        'episode 3',
        'episode 4',
        'episode 5 ',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::EPISODES as $key => $episodeName) {
            $episode = new Episode();

            $episode->setTitle($episodeName);
            $episode->setNumber(intval($key));
            $episode->setSynopsis("les épisodes");
            $episode->setSeason($this->getReference('season'));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
