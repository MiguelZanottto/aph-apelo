<?php
use PHPUnit\Framework\TestCase;
use services\CategoriasService;
use models\Categoria;
require_once __DIR__ . '/../src/services/CategoriasService.php';
class CategoriaServiceTest extends TestCase
{
    private $pdo;
    private $categoriasService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->categoriasService = new CategoriasService($this->pdo);
    }

    public function testFindAll()
    {
        $categoria1 = new Categoria("1", "Categoria 1", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);
        $categoria2 = new Categoria("2", "Categoria 2", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->exactly(3))
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['id' => '1', 'nombre' => 'Categoria 1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'is_deleted' => false],
                ['id' => '2', 'nombre' => 'Categoria 2', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'is_deleted' => false],
                false
            );

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $categorias = $this->categoriasService->findAll();

        $this->assertIsArray($categorias);

        foreach ($categorias as $categoria) {
            $this->assertInstanceOf(Categoria::class, $categoria);
        }

        $this->assertEquals($categoria1->getId(), $categorias[0]->getId());
        $this->assertEquals($categoria1->nombre, $categorias[0]->nombre);
        $this->assertEquals($categoria1->isDeleted, $categorias[0]->isDeleted);

        $this->assertEquals($categoria2->getId(), $categorias[1]->getId());
        $this->assertEquals($categoria2->nombre, $categorias[1]->nombre);
        $this->assertEquals($categoria2->isDeleted, $categorias[1]->isDeleted);
    }

    public function testFindById()
    {
        $categoria = new Categoria("1", "Categoria 1", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => $categoria->getId(),
                'nombre' => $categoria->nombre,
                'created_at' => $categoria->createdAt,
                'updated_at' => $categoria->updatedAt,
                'is_deleted' => $categoria->isDeleted,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findById("1");

        $this->assertInstanceOf(Categoria::class, $resultCategoria);
        $this->assertEquals($categoria->getId(), $resultCategoria->getId());
        $this->assertEquals($categoria->nombre, $resultCategoria->nombre);
    }

    public function testFindByIdNotFound()
    {
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findById("1");

        $this->assertNull($resultCategoria);
    }

    public function testFindByName()
    {
        $categoriaName = "Categoria 1";

        $categoria = new Categoria("1", $categoriaName, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => $categoria->getId(),
                'nombre' => $categoria->nombre,
                'created_at' => $categoria->createdAt,
                'updated_at' => $categoria->updatedAt,
                'is_deleted' => $categoria->isDeleted,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findByName($categoriaName);

        $this->assertInstanceOf(Categoria::class, $resultCategoria);
        $this->assertEquals($categoria->getId(), $resultCategoria->getId());
        $this->assertEquals($categoria->nombre, $resultCategoria->nombre);
    }

    public function testFindByNameNotFound()
    {
        $categoriaName = "Categoria Inexistente";

        // Mock de PDOStatement
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn([]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $resultCategoria = $this->categoriasService->findByName($categoriaName);

        $this->assertFalse($resultCategoria);
    }


    public function testDeleteByIdSuccess()
    {
        $categoriaId = "1";

        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $stmt1->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($stmt1);


        $result = $this->categoriasService->deleteById($categoriaId);

        $this->assertTrue($result);
    }

    public function testDeleteByIdWithAssociatedFunkos()
    {
        $categoriaId = "1";

        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt1->expects($this->once())
            ->method('fetch')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt1);

        $result = $this->categoriasService->deleteById($categoriaId);

        $this->assertNull($result);
    }


    public function testUpdateWithExistingCategoryName()
    {
        $existingCategory = new Categoria("existing_id", "Categoria Existente", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $stmt1 = $this->createMock(PDOStatement::class);

        $stmt1->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt1->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => $existingCategory->getId(),
                'nombre' => $existingCategory->nombre,
                'created_at' => $existingCategory->createdAt,
                'updated_at' => $existingCategory->updatedAt,
                'is_deleted' => $existingCategory->isDeleted,
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt1);

        $categoriaToUpdate = new Categoria("update_id", "Categoria Existente", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), false);

        $result = $this->categoriasService->update($categoriaToUpdate);

        $this->assertNull($result);
    }

    public function testSaveWithNonExistingCategoryName()
    {
        $categoriaToSave = new Categoria(null, "Nueva Categoria", null, null, false);

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($stmt);

        $result = $this->categoriasService->save($categoriaToSave);

        $this->assertTrue($result);
    }


}