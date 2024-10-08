<?php
// Başlangıç dizini
$startDir = __DIR__;

// İzinleri ayarlamak istediğin mod (tüm izinler için 0777 kullanılıyor)
$permissions = 0777;

// Dosya ve klasörlerin izinlerini değiştiren bir fonksiyon
function recursiveChmod($dir, $permissions) {
    // Şu anki dizin içindeki tüm dosyaları ve klasörleri al
    $files = array_diff(scandir($dir), array('.', '..'));
    
    foreach ($files as $file) {
        $fullPath = "$dir/$file";
        
        // Eğer bu bir dizinse, önce içine girip onun içindekileri işlemeliyiz
        if (is_dir($fullPath)) {
            // Klasörün izinlerini değiştir
            chmod($fullPath, $permissions);
            // İçindeki dosya ve klasörleri de işlemek için recursive çağrı yap
            recursiveChmod($fullPath, $permissions);
        } else {
            // Bu bir dosya ise, izinlerini değiştir
            chmod($fullPath, $permissions);
        }
    }
    
    // Son olarak dizinin kendisinin izinlerini değiştir
    chmod($dir, $permissions);
}

// Üst dizinlere de çıkmak için bir fonksiyon
function chmodParentDirs($dir, $permissions) {
    $parentDir = dirname($dir);
    if ($parentDir && $parentDir !== '/' && $parentDir !== '.') {
        chmod($parentDir, $permissions);
        // Üst dizini işlemeye devam et
        chmodParentDirs($parentDir, $permissions);
    }
}

// Site dizini içindeki dosya ve klasörlerin izinlerini değiştir
recursiveChmod($startDir, $permissions);

// Üst dizinlerdeki izinleri de değiştir
chmodParentDirs($startDir, $permissions);

echo "İzinler başarıyla değiştirildi.";
?>
