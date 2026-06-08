<?php
// ========== admin/news-delete.php - Удаление новости ==========
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        // Удаляем файл изображения, если он есть
        $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetchColumn();
        if ($image) {
            $path = '../' . ltrim($image, '/');
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
    } catch (Exception $e) {
        error_log('News delete error: ' . $e->getMessage());
    }
}

header('Location: news.php?success=deleted');
exit;
