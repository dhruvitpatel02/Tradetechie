<?php

require_once __DIR__ . '/config/config.php';

$conn = db();
if (!$conn) {
    die('Database connection failed. Cannot import content.');
}

$learning_data = require __DIR__ . '/data/learning_content.php';

$imported = 0;
$skipped = 0;

foreach ($learning_data as $item) {
    $slug = createSlug($item['title']);
    
    $stmt = $conn->prepare("SELECT content_id FROM educational_content WHERE slug = ?");
    $stmt->execute([$slug]);
    
    if ($stmt->rowCount() > 0) {
        $skipped++;
        continue;
    }
    
    $full_content = "<h3>Explanation</h3><p>{$item['content']}</p>";
    $full_content .= "<h3>Example</h3><p>{$item['example']}</p>";
    $full_content .= "<h3>Risk Note</h3><p class='text-danger'>{$item['risk_note']}</p>";
    
    $stmt = $conn->prepare("
        INSERT INTO educational_content 
        (title, slug, category, content, meta_description, order_position, status, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, 'published', 1)
    ");
    
    $meta = substr($item['content'], 0, 200);
    
    if ($stmt->execute([
        $item['title'],
        $slug,
        $item['category'],
        $full_content,
        $meta,
        $item['order_position']
    ])) {
        $imported++;
    }
}

echo "Import completed!\n";
echo "Imported: $imported\n";
echo "Skipped: $skipped\n";
echo "\nYou can now delete this file (import_learning_content.php).\n";
