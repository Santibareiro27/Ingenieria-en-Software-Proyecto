<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Cors.php';
require_once __DIR__ . '/../src/ProyectoRepositoryInterface.php';
require_once __DIR__ . '/../src/JsonProyectoRepository.php';
require_once __DIR__ . '/../src/ProyectoController.php';

Cors::enviarHeaders();

// El navegador manda un OPTIONS antes del POST/PUT/DELETE real
// (preflight). Hay que responderlo vacío y cortar ahí.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

/**
 * Rutas soportadas (todas bajo /api, aunque el prefijo es opcional):
 *   GET    /api/proyectos          -> listar      (?q=texto para buscar)
 *   GET    /api/proyectos/{id}     -> mostrar
 *   POST   /api/proyectos          -> registrar   (CU1 / RF01)
 *   PUT    /api/proyectos/{id}     -> modificar   (CU2)
 *   DELETE /api/proyectos/{id}     -> eliminar    (CU3)
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$uri = rtrim($uri, '/');
$ruta = preg_replace('#^/api#', '', $uri);

$segmentos = array_values(array_filter(explode('/', $ruta)));
$metodoHttp = $_SERVER['REQUEST_METHOD'];

$rutaArchivoDatos = __DIR__ . '/../data/proyectos.json';
$repositorio = new JsonProyectoRepository($rutaArchivoDatos);
$controlador = new ProyectoController($repositorio);

if (($segmentos[0] ?? null) !== 'proyectos') {
    http_response_code(404);
    echo json_encode(['error' => 'Recurso no encontrado']);
    exit;
}

$id = $segmentos[1] ?? null;

switch (true) {
    case $metodoHttp === 'GET' && $id === null:
        $controlador->listar($_GET['q'] ?? null);
        break;

    case $metodoHttp === 'GET' && $id !== null:
        $controlador->mostrar($id);
        break;

    case $metodoHttp === 'POST' && $id === null:
        $controlador->registrar(leerCuerpoJson());
        break;

    case $metodoHttp === 'PUT' && $id !== null:
        $controlador->modificar($id, leerCuerpoJson());
        break;

    case $metodoHttp === 'DELETE' && $id !== null:
        $controlador->eliminar($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido para esta ruta']);
}

/** @return array<string, mixed> */
function leerCuerpoJson(): array
{
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    return is_array($datos) ? $datos : [];
}
