<?php

namespace App\DataFixtures;

use App\Entity\Priority;
use App\Entity\Todo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $properties = ['Criticial', 'High', 'Medium', 'Low'];

        foreach ($properties as $key => $value) {
            $priority = new Priority();
            $priority->setLevel($key +1)->setName($value);
            $manager->persist($priority);
            $this->addReference('Priority_'.$key, $priority);
        }

        for ($i = 0; $i < 20; $i++) {
            $rand = random_int(0, 100);
            $rand_level = random_int(0, 3);
            $todo = new Todo();
            $todo->setName('Faire des choses '.$rand);
            $todo->setDescription('Je sais pas wallah');
            $todo->setDone(rand(0, 1) > 0.5);
            $todo->setPriority($this->getReference('Priority_'.$rand_level));
            $manager->persist($todo);
        }
        $manager->flush();
    }
}
