$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css' -Raw
$newcontent = $content -replace 'z-index: 1000;', 'z-index: 9999;'
$newcontent | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css'
Write-Host "Done"