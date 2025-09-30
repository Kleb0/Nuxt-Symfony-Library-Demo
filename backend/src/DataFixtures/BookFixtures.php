<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // One Piece Tome 1
        $book1 = new Book();
        $book1->setTitre('One Piece - Tome 1: À l\'aube d\'une grande aventure');
        $book1->setAuteur('Eiichiro Oda');
        $book1->setUnitPrice('6.90');
        $book1->setResume('Le premier tome du manga One Piece où nous découvrons Monkey D. Luffy et le début de son aventure pour devenir le Roi des Pirates. Luffy mange le fruit du démon et devient élastique.');
        $book1->setImage('/images/One_Piece_1.jpg');
        $book1->setCategorie('Shonen');

        // One Piece Tome 2
        $book2 = new Book();
        $book2->setTitre('One Piece - Tome 2: Celui qu\'ils appellent le démon');
        $book2->setAuteur('Eiichiro Oda');
        $book2->setUnitPrice('6.90');
        $book2->setResume('Le deuxième tome de One Piece où Luffy rencontre Roronoa Zoro, le chasseur de pirates qui deviendra son premier nakama.');
        $book2->setImage('/images/One_Piece_2.jpg');
        $book2->setCategorie('Shonen');

        // Naruto Tome 1
        $book3 = new Book();
        $book3->setTitre('Naruto - Tome 1: Naruto Uzumaki');
        $book3->setAuteur('Masashi Kishimoto');
        $book3->setUnitPrice('6.95');
        $book3->setResume('L\'histoire de Naruto Uzumaki, un jeune ninja qui rêve de devenir Hokage de son village. Il porte en lui le démon renard à neuf queues.');
        $book3->setImage('/images/Naruto_1.jpg');
        $book3->setCategorie('Shonen');

        // My Hero Academia
        $book4 = new Book();
        $book4->setTitre('My Hero Academia - Tome 1: Izuku Midoriya');
        $book4->setAuteur('Kohei Horikoshi');
        $book4->setUnitPrice('6.99');
        $book4->setResume('Dans un monde où 80% de la population possède un super-pouvoir, Izuku Midoriya rêve de devenir un héros malgré son absence d\'alter.');
        $book4->setImage('/images/My_Hero_Academia_.jpg');
        $book4->setCategorie('Shonen');

        // Vinland Saga Tome 1
        $book5 = new Book();
        $book5->setTitre('Vinland Saga - Tome 1: Thorfinn');
        $book5->setAuteur('Makoto Yukimura');
        $book5->setUnitPrice('7.95');
        $book5->setResume('L\'épopée viking de Thorfinn, un jeune guerrier en quête de vengeance contre celui qui a tué son père. Une saga historique épique.');
        $book5->setImage('/images/Vinland_Saga_1.jpg');
        $book5->setCategorie('Seinen');

        // Vinland Saga Tome 2
        $book6 = new Book();
        $book6->setTitre('Vinland Saga - Tome 2: La lame du guerrier');
        $book6->setAuteur('Makoto Yukimura');
        $book6->setUnitPrice('7.95');
        $book6->setResume('Thorfinn continue sa quête de vengeance et s\'endurcit dans les combats aux côtés des vikings d\'Askeladd.');
        $book6->setImage('/images/Vinland_Saga_2.jpg');
        $book6->setCategorie('Seinen');

        // Vinland Saga Tome 3
        $book7 = new Book();
        $book7->setTitre('Vinland Saga - Tome 3: L\'honneur du guerrier');
        $book7->setAuteur('Makoto Yukimura');
        $book7->setUnitPrice('7.95');
        $book7->setResume('Les relations entre Thorfinn et Askeladd se complexifient alors que leur groupe de mercenaires vikings continue de semer la terreur.');
        $book7->setImage('/images/Vinland_Saga_3.jpg');
        $book7->setCategorie('Seinen');

        // Moi, quand je me réincarne en slime Tome 1
        $book8 = new Book();
        $book8->setTitre('Moi, quand je me réincarne en slime - Tome 1');
        $book8->setAuteur('Fuse / Taiki Kawakami');
        $book8->setUnitPrice('7.50');
        $book8->setResume('L\'histoire de Satoru Mikami qui se réincarne en slime dans un monde fantastique et devient progressivement l\'un des êtres les plus puissants.');
        $book8->setImage('/images/Me_Slime_1_.jpg');
        $book8->setCategorie('Isekai');

        // Moi, quand je me réincarne en slime Tome 2
        $book9 = new Book();
        $book9->setTitre('Moi, quand je me réincarne en slime - Tome 2');
        $book9->setAuteur('Fuse / Taiki Kawakami');
        $book9->setUnitPrice('7.50');
        $book9->setResume('Rimuru continue d\'explorer son nouveau monde et de développer ses capacités uniques de slime, tout en aidant d\'autres créatures.');
        $book9->setImage('/images/Me_Slime_2_.jpg');
        $book9->setCategorie('Isekai');

        $manager->persist($book1);
        $manager->persist($book2);
        $manager->persist($book3);
        $manager->persist($book4);
        $manager->persist($book5);
        $manager->persist($book6);
        $manager->persist($book7);
        $manager->persist($book8);
        $manager->persist($book9);

        $manager->flush();
    }
}