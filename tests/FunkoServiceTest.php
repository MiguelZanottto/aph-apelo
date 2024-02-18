<?php

use PHPUnit\Framework\TestCase;
use models\Funko;
use services\FunkosService;

require_once __DIR__ . '/../src/services/FunkosService.php';

class FunkoServiceTest extends TestCase{

    private $pdo;
    private $funkosService;

    protected function setUp(): void{
        $this->pdo = $this->createMock(PDO::class);
        $this->funkosService = new FunkosService($this->pdo);
    }

    public function testFindAllWithCategoryName()
    {
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->atLeastOnce())
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                [
                    'id' => '1',
                    'nombre' => 'Funko 1',
                    'precio' => '19.99',
                    'stock' => 10,
                    'imagen' => 'imagen1.jpg',
                    'created_at' => '2022-01-01 12:00:00',
                    'updated_at' => '2022-01-02 14:30:00',
                    'categoria_id' => '1',
                    'categoria_nombre' => 'Categoria 1',
                    'is_deleted' => false,
                ],
                [
                    'id' => '2',
                    'nombre' => 'Funko 2',
                    'precio' => '24.99',
                    'stock' => 5,
                    'imagen' => 'imagen2.jpg',
                    'created_at' => '2022-02-01 10:30:00',
                    'updated_at' => '2022-02-05 16:45:00',
                    'categoria_id' => '2',
                    'categoria_nombre' => 'Categoria 2',
                    'is_deleted' => false,
                ]
            );

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->funkosService->findAllWithCategoryName();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testFindById()
    {
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => '1',
                'nombre' => 'Funko 1',
                'precio' => '19.99',
                'stock' => 10,
                'imagen' => 'imagen1.jpg',
                'created_at' => '2022-01-01 12:00:00',
                'updated_at' => '2022-01-02 14:30:00',
                'categoria_id' => '1',
                'categoria_nombre' => 'Categoria 1',
                'is_deleted' => false,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->funkosService->findById(1);

        $this->assertInstanceOf(Funko::class, $result);
        $this->assertEquals('Funko 1', $result->nombre);
    }


    public function testDeleteById()
    {
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->funkosService->deleteById(1);

        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        $existingFunko = new Funko("1", "Funko Existente", "19.99", 10, "imagen.jpg", "2022-01-01 12:00:00", "2022-01-02 14:30:00", "1", "Categoria 1", false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->funkosService->update($existingFunko);

        $this->assertTrue($result);
    }

    public function testSave()
    {
        $newFunko = new Funko(null, "Nuevo Funko", "24.99", 5, "nueva_imagen.jpg", null, null, "2", "Categoria 2", false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->funkosService->save($newFunko);

        $this->assertTrue($result);
    }
}