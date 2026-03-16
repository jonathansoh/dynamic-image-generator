<?php
/**
 * Save/Load Configuration API
 * Handles saving and loading text positioning and style configurations
 */

header('Content-Type: application/json');

$configFile = __DIR__ . '/configs.json';
$method = $_SERVER['REQUEST_METHOD'];

// Load existing configs
if (file_exists($configFile)) {
    $configs = json_decode(file_get_contents($configFile), true);
} else {
    $configs = [];
}

try {
    switch ($method) {
        case 'GET':
            // Get all configs or specific config by image ID
            if (isset($_GET['id'])) {
                $imageId = $_GET['id'];
                echo json_encode([
                    'success' => true,
                    'config' => $configs[$imageId] ?? null
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'configs' => $configs
                ]);
            }
            break;

        case 'POST':
            // Save new config
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['imageId'])) {
                throw new Exception('Image ID is required');
            }

            $imageId = $input['imageId'];
            $configs[$imageId] = [
                'imageId' => $imageId,
                'fontFamily' => $input['fontFamily'] ?? 'Arial',
                'fontSize' => (int)($input['fontSize'] ?? 40),
                'fontWeight' => $input['fontWeight'] ?? 'normal',
                'fontColor' => $input['fontColor'] ?? '#FFFFFF',
                'shadowColor' => $input['shadowColor'] ?? '#000000',
                'posX' => (int)($input['posX'] ?? 400),
                'posY' => (int)($input['posY'] ?? 200),
                'posXRel' => (float)($input['posXRel'] ?? 50),
                'posYRel' => (float)($input['posYRel'] ?? 50),
                'canvasWidth' => (int)($input['canvasWidth'] ?? 800),
                'canvasHeight' => (int)($input['canvasHeight'] ?? 400),
                'updatedAt' => date('Y-m-d H:i:s')
            ];

            // Save to file
            file_put_contents($configFile, json_encode($configs, JSON_PRETTY_PRINT));

            echo json_encode([
                'success' => true,
                'message' => 'Configuration saved successfully',
                'config' => $configs[$imageId]
            ]);
            break;

        case 'DELETE':
            // Delete config
            if (!isset($_GET['id'])) {
                throw new Exception('Image ID is required');
            }

            $imageId = $_GET['id'];
            if (isset($configs[$imageId])) {
                unset($configs[$imageId]);
                file_put_contents($configFile, json_encode($configs, JSON_PRETTY_PRINT));

                echo json_encode([
                    'success' => true,
                    'message' => 'Configuration deleted successfully'
                ]);
            } else {
                throw new Exception('Configuration not found');
            }
            break;

        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
