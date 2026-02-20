$plugins = @(
    "advanced-custom-fields",
    "seo-by-rank-math",
    "litespeed-cache",
    "contact-form-7",
    "wordfence"
)

$pluginDir = "d:\xampp\htdocs\web\wp-content\plugins"
Set-Location $pluginDir

foreach ($slug in $plugins) {
    $url = "https://downloads.wordpress.org/plugin/$slug.latest-stable.zip"
    $zipFile = "$slug.zip"
    
    Write-Host "Downloading $slug..."
    Invoke-WebRequest -Uri $url -OutFile $zipFile
    
    if (Test-Path $zipFile) {
        Write-Host "Extracting $slug..."
        Expand-Archive -Path $zipFile -DestinationPath . -Force
        Remove-Item $zipFile
        Write-Host "$slug installed successfully."
    } else {
        Write-Host "Failed to download $slug."
    }
}

# Create missing images folder in theme
$themeDir = "d:\xampp\htdocs\web\wp-content\themes\sman1-material"
if (!(Test-Path "$themeDir\images")) {
    New-Item -ItemType Directory -Path "$themeDir\images" -Force
    Write-Host "Created images directory in theme."
}
