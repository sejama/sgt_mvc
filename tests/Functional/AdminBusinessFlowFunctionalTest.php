<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoPartido;
use App\Enum\Genero;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AdminBusinessFlowFunctionalTest extends AdminBusinessFlowFunctionalTestCase
{
    public function testAdminAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-gestion', $suffix);

        $admin = $this->crearUsuario('ft_admin_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($statusCategoria, [401, 403]);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($statusSede, [401, 403]);
    }

    public function testUsuarioSinRolAdminNoAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-gestion', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        if ($statusCategoria === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusCategoria, [401, 403]);
        }

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        if ($statusSede === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusSede, [401, 403]);
        }
    }

    public function testAnonimoNoAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-gestion', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        if ($statusCategoria === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusCategoria, [401, 403]);
        }

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        if ($statusSede === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusSede, [401, 403]);
        }
    }

    public function testAdminAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-partidos', $suffix);

        $admin = $this->crearUsuario('ft_admin_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');

        $status = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($status, [401, 403]);
    }

    public function testAdminVeSedeYCanchaEnIndiceDePartidosSinAsignar(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-partidos-cancha', $suffix);

        $admin = $this->crearUsuario('ft_admin_partidos_cancha_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'icl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'icv');
        $partido = $this->crearPartido($categoria, 654);
        $partido->setGrupo($grupo)->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $cancha = $this->crearCancha($torneo, $suffix);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');

        self::assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        self::assertStringContainsString((string) $partidoNumero, $content);
        self::assertStringContainsString($cancha->getSede()->getNombre(), $content);
        self::assertStringContainsString($cancha->getNombre(), $content);
    }

    public function testUsuarioSinRolAdminNoAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-partidos', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_partidos_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-partidos', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAdminCreaCategoriaPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat ' . $suffix,
            'nombreCorto' => 'FC' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $categoria = $this->entityManager->getRepository(Categoria::class)->findOneBy([
            'nombre' => 'FT Cat ' . $suffix,
            'torneo' => $torneo,
        ]);

        self::assertInstanceOf(Categoria::class, $categoria);
        self::assertSame($torneo->getId(), $categoria->getTorneo()?->getId());
    }

    public function testAdminNoCreaCategoriaConNombreInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT',
            'nombreCorto' => 'FC' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Nombre debe tener entre 3 y 128 caracteres', $this->client->getResponse()->getContent());

        $categorias = $this->entityManager->getRepository(Categoria::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT',
        ]);

        self::assertCount(0, $categorias);
    }

    public function testAdminCreaSedePorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede ' . $suffix,
            'sedeDireccion' => 'Calle Functional 123',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $sede = $this->entityManager->getRepository(Sede::class)->findOneBy([
            'nombre' => 'FT Sede ' . $suffix,
            'torneo' => $torneo,
        ]);

        self::assertInstanceOf(Sede::class, $sede);
        self::assertSame($torneo->getId(), $sede->getTorneo()?->getId());
    }

    public function testAdminNoCreaSedeConDireccionInvalidaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede Inv ' . $suffix,
            'sedeDireccion' => 'Corta',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Dirección debe tener entre 8 y 128 caracteres', $this->client->getResponse()->getContent());

        $sedes = $this->entityManager->getRepository(Sede::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT Sede Inv ' . $suffix,
        ]);

        self::assertCount(0, $sedes);
    }

    public function testAdminNoCreaCategoriaDuplicadaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $this->crearCategoria($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat Base ' . $suffix,
            'nombreCorto' => 'FB' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseStatusCodeSame(200);

        $categorias = $this->entityManager->getRepository(Categoria::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT Cat Base ' . $suffix,
        ]);

        self::assertCount(1, $categorias);
    }

    public function testAdminNoCreaSedeInvalidaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede Corta ' . $suffix,
            'sedeDireccion' => 'Corta',
        ]);

        self::assertResponseStatusCodeSame(200);

        $sede = $this->entityManager->getRepository(Sede::class)->findOneBy([
            'torneo' => $torneo,
            'nombre' => 'FT Sede Corta ' . $suffix,
        ]);

        self::assertNull($sede);
    }

    public function testAdminEditaCategoriaPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/editar/', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat Edit ' . $suffix,
            'nombreCorto' => 'FE' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $editada = $this->entityManager->getRepository(Categoria::class)->find($categoria->getId());

        self::assertInstanceOf(Categoria::class, $editada);
        self::assertSame('FT Cat Edit ' . $suffix, $editada->getNombre());
        self::assertSame('FE' . strtoupper(substr($suffix, 0, 4)), $editada->getNombreCorto());
        self::assertSame('Masculino', $editada->getGenero()?->value);
    }

    public function testAdminAccedeAFormularioEditarCategoriaPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-edit-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_edit_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/editar/');
        self::assertResponseIsSuccessful();
    }

    public function testAdminAccedeAFormularioEditarDisputaPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-disputa-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_disputa_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/editar/disputa/');
        self::assertResponseIsSuccessful();
    }

    public function testAdminEditaSedePorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/editar', [
            'sedeNombre' => 'FT Sede Edit ' . $suffix,
            'sedeDireccion' => 'Calle Editada 999',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $editada = $this->entityManager->getRepository(Sede::class)->find($sede->getId());

        self::assertInstanceOf(Sede::class, $editada);
        self::assertSame('FT Sede Edit ' . $suffix, $editada->getNombre());
        self::assertSame('Calle Editada 999', $editada->getDomicilio());
    }

    public function testAdminEliminaCategoriaPorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $eliminada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertNull($eliminada);
    }

    public function testAdminEliminarCategoriaInexistenteRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-cat-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_cat_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/999999/eliminar');
        self::assertResponseRedirects('/login');
    }

    public function testAdminCerrarCategoriaInexistenteRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-close-cat-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_close_cat_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/999999/cerrar');
        self::assertResponseRedirects('/login');
    }

    public function testAdminEliminaSedePorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $eliminada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertNull($eliminada);
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarSede(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-sede', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_edit_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $usuario = $this->crearUsuario('ft_user_edit_sede_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/editar');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAEditarSede(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-sede', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_edit_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/editar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoAccedeAEliminarSede(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-del-sede', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_del_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        $usuario = $this->crearUsuario('ft_user_del_sede_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/eliminar');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);

        $this->entityManager->clear();
        $sedePersistida = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $sedePersistida);
    }

    public function testAnonimoNoAccedeAEliminarSede(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-del-sede', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_del_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/eliminar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }

        $this->entityManager->clear();
        $sedePersistida = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $sedePersistida);
    }

    public function testAdminCreaEquipoPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/nuevo', [
            'nombre' => 'FT Equipo ' . $suffix,
            'nombreCorto' => 'EQ' . strtoupper(substr($suffix, 0, 4)),
            'pais' => 'Argentina',
            'provincia' => 'Mendoza',
            'localidad' => 'Capital',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'delegado+' . $suffix . '@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $equipo = $this->entityManager->getRepository(Equipo::class)->findOneBy([
            'nombre' => 'FT Equipo ' . $suffix,
            'categoria' => $categoria,
        ]);

        self::assertInstanceOf(Equipo::class, $equipo);
        self::assertSame($categoriaId, $equipo->getCategoria()?->getId());
    }

    public function testAdminNoCreaEquipoInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-equipo-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_equipo_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/nuevo', [
            'nombre' => 'FT Equipo Inv ' . $suffix,
            'nombreCorto' => 'X',
            'pais' => 'Argentina',
            'provincia' => 'Mendoza',
            'localidad' => 'Capital',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'delegado-inv+' . $suffix . '@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        self::assertResponseStatusCodeSame(200);

        $equipo = $this->entityManager->getRepository(Equipo::class)->findOneBy([
            'nombre' => 'FT Equipo Inv ' . $suffix,
            'categoria' => $categoria,
        ]);

        self::assertNull($equipo);
    }

    public function testAdminEditaEquipoPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/editar', [
            'nombre' => 'FT Equipo Edit ' . $suffix,
            'nombreCorto' => 'EE' . strtoupper(substr($suffix, 0, 4)),
            'pais' => 'Argentina',
            'provincia' => 'Cordoba',
            'localidad' => 'Centro',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $this->entityManager->clear();
        $editado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);

        self::assertInstanceOf(Equipo::class, $editado);
        self::assertSame('FT Equipo Edit ' . $suffix, $editado->getNombre());
        self::assertSame('EE' . strtoupper(substr($suffix, 0, 4)), $editado->getNombreCorto());
    }

    public function testAdminEliminaEquipoPorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $this->entityManager->clear();
        $eliminado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertNull($eliminado);
    }

    public function testAdminAccedeAIndiceDeEquipos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-index-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_index_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($torneo->getNombre(), $this->client->getResponse()->getContent());
        self::assertStringContainsString($equipo->getNombre(), $this->client->getResponse()->getContent());
    }

    public function testAdminBajaEquipoYCancelaPartidosAsociados(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-baja-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_baja_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'l');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'v');
        $partido = $this->crearPartido($categoria, 501);
        $equipoLocal->addPartidosLocal($partido);
        $equipoVisitante->addPartidosVisitante($partido);
        $this->entityManager->flush();

        $equipoId = $equipoLocal->getId();
        self::assertNotNull($equipoId);
        $partidoId = $partido->getId();
        self::assertNotNull($partidoId);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipoId . '/bajar');

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/');

        $this->entityManager->clear();
        $equipoBajado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertInstanceOf(Equipo::class, $equipoBajado);
        self::assertSame('No_participa', $equipoBajado->getEstado());

        $partidoCancelado = $this->entityManager->getRepository(Partido::class)->find($partidoId);
        self::assertInstanceOf(Partido::class, $partidoCancelado);
        self::assertSame('Cancelado', $partidoCancelado->getEstado());
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarEquipo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-equipo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_edit_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $usuario = $this->crearUsuario('ft_user_edit_equipo_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/editar');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAEditarEquipo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-equipo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_edit_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/editar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoAccedeAEliminarEquipo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-del-equipo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_del_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $usuario = $this->crearUsuario('ft_user_del_equipo_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipoId . '/eliminar');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);

        $this->entityManager->clear();
        $equipoPersistido = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertInstanceOf(Equipo::class, $equipoPersistido);
    }

    public function testAnonimoNoAccedeAEliminarEquipo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-del-equipo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_del_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipoId . '/eliminar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }

        $this->entityManager->clear();
        $equipoPersistido = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertInstanceOf(Equipo::class, $equipoPersistido);
    }

}
