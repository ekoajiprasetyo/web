$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css' -Raw

# Fix dropdown hover and focus-within for better accessibility and stability
$oldDropdownHover = @'
.nav-item:hover .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
'@

$newDropdownHover = @'
.nav-item:hover > .dropdown-menu,
.nav-item:focus-within > .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
'@

$content = $content -replace [regex]::Escape($oldDropdownHover), $newDropdownHover

$content | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css'
Write-Host "Dropdown CSS Fixed"