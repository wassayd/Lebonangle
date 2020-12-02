<?php


namespace App\Tests\Unit;


use App\Entity\Advert;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class AdvertTest extends TestCase
{
    private Advert $advert;

    protected function setUp()
    {
        parent::setUp();
        $this->advert = new Advert();
    }

    public function testGetEmail(): void {
        $value = 'test@test.com';

        $response = $this->advert->setEmail($value);
        $getEmail = $this->advert->getEmail();

        self::assertInstanceOf(Advert::class,$response);
        self::assertEquals($value,$getEmail);

    }

    public function testGetTitle(): void {
        $value = 'titre advert';

        $response = $this->advert->setTitle($value);
        $getTitle = $this->advert->getTitle();

        self::assertInstanceOf(Advert::class,$response);
        self::assertEquals($value,$getTitle);

    }

    public function testGetContent(): void {
        $value = 'advert';

        $response = $this->advert->setContent($value);
        $getContent = $this->advert->getContent();

        self::assertInstanceOf(Advert::class,$response);
        self::assertEquals($value,$getContent);
    }

    public function testGetAuthor(): void {
        $value = 'advert';

        $response = $this->advert->setAuthor($value);
        $getAuthor = $this->advert->getAuthor();

        self::assertInstanceOf(Advert::class,$response);
        self::assertEquals($value,$getAuthor);
    }

    public function testGetCategory(): void {
        $value = new Category();

        $response = $this->advert->setCategory($value);
        $getCategory = $this->advert->getCategory();

        self::assertInstanceOf(Advert::class,$response);
        self::assertTrue($this->advert->getCategory() === $getCategory);

    }

    public function testGetPrice(): void {
        $value = 0;

        $response = $this->advert->setPrice($value);
        $getPrice = $this->advert->getPrice();

        self::assertInstanceOf(Advert::class,$response);
        self::assertEquals($value,$getPrice);

    }
}
