# --- STEP 1: GATHER INFORMATION ---
Write-Host "INFO: Gathering plugin and repository information..."

# Extracts the version number directly from the main plugin PHP file.
$pluginFile = Get-Content "lux-copyright-manager.php"
$versionLine = $pluginFile | Where-Object { $_ -match '^\s*\*\s*Version:' }
$PLUGIN_VERSION = ($versionLine -split ' ')[-1].Trim()

# Extracts the repository slug (e.g., starlux/lux-copyright-manager) from the git remote URL.
$remoteUrl = git remote get-url origin
$REPO_SLUG = $remoteUrl -replace '.*github\.com[/:](.*)\.git', '$1'

if ([string]::IsNullOrEmpty($PLUGIN_VERSION) -or [string]::IsNullOrEmpty($REPO_SLUG)) {
    Write-Host "ERROR: Could not determine plugin version or repository slug. Please check files." -ForegroundColor Red
    exit 1
}

Write-Host "  - Detected Plugin Version: $PLUGIN_VERSION"
Write-Host "  - Detected Repository Slug: $REPO_SLUG"

# --- STEP 2: CREATE AND PUSH GIT TAG ---
# Create the tag name by prefixing the version with 'v'.
$TAG = "v$PLUGIN_VERSION"

Write-Host "INFO: Preparing to create and push tag: $TAG"

# Check if the tag already exists locally. If it does, we'll stop to avoid errors.
$tagExists = git rev-parse --verify "refs/tags/$TAG" 2>$null

if ($tagExists) {
    Write-Host "WARNING: Tag '$TAG' already exists locally. Skipping tag creation and push." -ForegroundColor Yellow
} else {
    Write-Host "  - Creating annotated git tag..."
    git tag -a "$TAG" -m "Release version $PLUGIN_VERSION"
    
    Write-Host "  - Pushing tag to remote repository (origin)..."
    git push origin "$TAG"
}

# --- STEP 3: CREATE GITHUB RELEASE ---
Write-Host "INFO: Creating GitHub Release for tag '$TAG'..."

# Use the GitHub CLI to create a release from the tag we just pushed.
gh release create "$TAG" `
    --repo "$REPO_SLUG" `
    --title "Version $PLUGIN_VERSION" `
    --notes "Initial release for WordPress.org review. This release corresponds to the code submitted for version $PLUGIN_VERSION."

Write-Host "SUCCESS: GitHub Release has been created successfully." -ForegroundColor Green

# --- STEP 4: UPDATE README.TXT FILE ---
Write-Host "INFO: Updating readme.txt with the correct release link..."

# Construct the full URL to the release we just created.
$RELEASE_URL = "https://github.com/$REPO_SLUG/releases/tag/$TAG"

# Read the readme.txt file
$readmeContent = Get-Content "readme.txt" -Raw

# This command finds the line starting with "Development of this plugin..." and replaces the entire line
# with the new, correct text including the precise URL.
# Using regex to match the line and replace it
$updatedContent = $readmeContent -replace '^Development of this plugin.*', "Development of this plugin takes place on GitHub. You can find the full, un-minified source code for this release here: $RELEASE_URL"

# Write the updated content back to readme.txt
Set-Content -Path "readme.txt" -Value $updatedContent

Write-Host "SUCCESS: readme.txt has been updated." -ForegroundColor Green

# --- STEP 5: COMMIT AND PUSH THE README UPDATE ---
Write-Host "INFO: Committing and pushing the readme.txt update..."
git add readme.txt
git commit -m "docs: Update readme with correct v$PLUGIN_VERSION release link"
git push origin main

Write-Host "--------------------------------------------------" -ForegroundColor Cyan
Write-Host "ALL TASKS COMPLETED SUCCESSFULLY!" -ForegroundColor Cyan
Write-Host "The GitHub Release is live and readme.txt is updated." -ForegroundColor Cyan
Write-Host "You are now ready to ZIP and submit your plugin." -ForegroundColor Cyan
Write-Host "--------------------------------------------------" -ForegroundColor Cyan