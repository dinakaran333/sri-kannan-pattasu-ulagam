<?php
$images = [
    'sparklers_10cm.jpg' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&q=80',
    'sparklers_30cm.jpg' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&q=80',
    'chakkar_big.jpg' => 'https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?w=600&q=80',
    'chakkar_deluxe.jpg' => 'https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?w=600&q=80',
    'flowerpot_special.jpg' => 'https://images.unsplash.com/photo-1498931299472-f7a63a5a1cfa?w=600&q=80',
    'fountain_deluxe.jpg' => 'https://images.unsplash.com/photo-1498931299472-f7a63a5a1cfa?w=600&q=80',
    'rocket_whistling.jpg' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?w=600&q=80',
    'skyshot_12shot.jpg' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?w=600&q=80',
    'sound_28chora.jpg' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=600&q=80',
    'garland_1000.jpg' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=600&q=80',
    'giftbox_family.jpg' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80',
    'giftbox_kids.jpg' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80',
    'sparklers.jpg' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&q=80',
    'chakkars.jpg' => 'https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?w=600&q=80',
    'flower-pots.jpg' => 'https://images.unsplash.com/photo-1498931299472-f7a63a5a1cfa?w=600&q=80',
    'rockets.jpg' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?w=600&q=80',
    'sound-crackers.jpg' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?w=600&q=80',
    'gift-boxes.jpg' => 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80'
];

$dirs = [
    'C:/xampp/htdocs/cracker-shop/assets/images/uploads/',
    'd:/web projects/cracker-shop/assets/images/uploads/'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

foreach ($images as $filename => $url) {
    $content = file_get_contents($url);
    if ($content !== false) {
        foreach ($dirs as $dir) {
            file_put_contents($dir . $filename, $content);
        }
        echo "Downloaded: $filename\n";
    }
}
echo "All real cracker images downloaded successfully!\n";
