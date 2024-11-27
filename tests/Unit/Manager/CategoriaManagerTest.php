<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Manager\CategoriaManager;
use App\Manager\ValidadorManager;
use App\Repository\CategoriaRepository;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\exactly;

class CategoriaManagerTest extends TestCase
{
    public function testObtenerCategorias(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $this->assertIsArray($categoriaManager->obtenerCategorias());
        $this->assertEquals([], $categoriaManager->obtenerCategorias());
    }

    public function testObtenerCategoriaOk(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $categoria = $this->createMock(Categoria::class);
        $categoriaRepository->method('find')->willReturn($categoria);

        $this->assertEquals($categoria, $categoriaManager->obtenerCategoria(1));
    }

    public function testObtenerCategoriaNoEncontrada(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $this->assertNull($categoriaManager->obtenerCategoria(1));
    }

    public function testObtenerCategoriasPorTorneo(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $torneo = new Torneo();
        $categoriaRepository->method('findBy')
            ->with(['torneo' => $torneo])
            ->willReturn([$this->createMock(Categoria::class)]);

        $this->assertIsArray($categoriaManager->obtenerCategoriasPorTorneo($torneo));
    }

    public function testCrearCategoriaOk(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $torneo = new Torneo();

        $categoriaRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);

        $validadorManager->expects($this->once())
            ->method('validarCategoria');

        $categoriaRepository->expects($this->once())
            ->method('guardar');

        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }

    public function testCrearCategoriaYaExisteNombreGenero(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $torneo = new Torneo();

        $categoriaRepository->method('findOneBy')
            ->with(['torneo' => $torneo, 'genero' => 'Masculino', 'nombre' => 'nombre'])
            ->willReturn($this->createMock(Categoria::class));

        $this->expectExceptionMessage('Ya existe una categoría con ese nombre y genero');
        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }

    public function testCrearCategoriaYaExisteNombreCorto(): void
    {
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $categoriaManager = new CategoriaManager(
            $categoriaRepository,
            $validadorManager,
        );

        $torneo = new Torneo();

        $categoriaRepository->expects(exactly(2))
            ->method('findOneBy')
            ->willReturn(null, $this->createMock(Categoria::class));

        $this->expectExceptionMessage('Ya existe una categoría con ese nombre corto');
        $categoriaManager->crearCategoria($torneo, 'Masculino', 'nombre', 'nc');
    }
}
