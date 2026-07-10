<?php
namespace App\Helpers;

/**
 * Helper para upload e conversão de imagens para WebP.
 * Compatível com InfinityFree (usa apenas extensão GD, sem Imagick).
 */
class ImageHelper
{
    private const MAX_SIZE = 3 * 1024 * 1024; // 3MB
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Faz upload, valida e converte para WebP.
     * Retorna o nome do arquivo salvo ou null em caso de erro.
     */
    public static function upload(array $file, string $destinationFolder, int $maxWidth = 800): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new \RuntimeException('Imagem muito grande. Máximo 3MB.');
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, self::ALLOWED_TYPES, true)) {
            throw new \RuntimeException('Formato de imagem inválido. Use JPG, PNG ou WebP.');
        }

        $uploadDir = ROOT_PATH . '/uploads/' . trim($destinationFolder, '/') . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid('img_', true) . '.webp';
        $destinationPath = $uploadDir . $filename;

        $sourceImage = self::createImageFromFile($file['tmp_name'], $mimeType);
        if (!$sourceImage) {
            throw new \RuntimeException('Erro ao processar imagem.');
        }

        // Redimensiona mantendo proporção, se necessário
        $sourceImage = self::resize($sourceImage, $maxWidth);

        // Salva como WebP (economiza espaço e carrega mais rápido)
        if (function_exists('imagewebp')) {
            imagewebp($sourceImage, $destinationPath, 82);
        } else {
            // Fallback: salva como JPEG se o servidor não suportar WebP
            $filename = uniqid('img_', true) . '.jpg';
            $destinationPath = $uploadDir . $filename;
            imagejpeg($sourceImage, $destinationPath, 85);
        }

        imagedestroy($sourceImage);

        return trim($destinationFolder, '/') . '/' . $filename;
    }

    private static function createImageFromFile(string $path, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png'  => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default      => null,
        };
    }

    private static function resize($image, int $maxWidth)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth) {
            return $image;
        }

        $ratio = $maxWidth / $width;
        $newWidth = $maxWidth;
        $newHeight = (int) ($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserva transparência para PNGs
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    public static function delete(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $fullPath = ROOT_PATH . '/uploads/' . $relativePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}