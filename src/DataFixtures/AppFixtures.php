<?php

namespace App\DataFixtures;

use App\Entity\Scale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 1000; $i++) {
            $scale = new Scale();
            $scale->setLabel('label'.$i);
            $manager->persist($scale);
        }

        $manager->flush();
    }
}
