$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css' -Raw
# Fix navbar z-index
$content = $content -replace 'z-index: 1000;', 'z-index: 10000;'
# Fix duplicate header comment
$content = $content -replace '\/\* =+ HEADER \/ NAVIGATION =\+ \*\/\r\n\/\* =+ HEADER \/ NAVIGATION =\+ \*\/\r\n', '/* ==================== HEADER / NAVIGATION ==================== */
'
$newcontent = $content
$newcontent | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css'
Write-Host "CSS Fixed"