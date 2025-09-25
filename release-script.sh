#!/bin/bash
set -e

# --- STEP 1: GATHER INFORMATION ---
echo "INFO: Gathering plugin and repository information..."

# Extracts the version number directly from the main plugin PHP file.
# It looks for the line starting with "Version:", then extracts the number.
PLUGIN_VERSION=$(grep -i '^ \* Version:' lux-copyright-manager.php | awk -F' ' '{print $NF}')

# Extracts the repository slug (e.g., starlux/lux-copyright-manager) from the git remote URL.
REPO_SLUG=$(git remote get-url origin | sed 's/.*github.com[:\/]\(.*\)\.git/\1/')

if [ -z "$PLUGIN_VERSION" ] || [ -z "$REPO_SLUG" ]; then
  echo "ERROR: Could not determine plugin version or repository slug. Please check files."
  exit 1
fi

echo " - Detected Plugin Version: $PLUGIN_VERSION"
echo "  - Detected Repository Slug: $REPO_SLUG"

# --- STEP 2: CREATE AND PUSH GIT TAG ---
# Create the tag name by prefixing the version with 'v'.
TAG="v$PLUGIN_VERSION"

echo "INFO: Preparing to create and push tag: $TAG"

# Check if the tag already exists locally. If it does, we'll stop to avoid errors.
if git rev-parse -q --verify "refs/tags/$TAG" >/dev/null; then
    echo "WARNING: Tag '$TAG' already exists locally. Skipping tag creation and push."
else
    echo "  - Creating annotated git tag..."
    git tag -a "$TAG" -m "Release version $PLUGIN_VERSION"
    
    echo "  - Pushing tag to remote repository (origin)..."
    git push origin "$TAG"
fi

# --- STEP 3: CREATE GITHUB RELEASE ---
echo "INFO: Creating GitHub Release for tag '$TAG'..."

# Use the GitHub CLI to create a release from the tag we just pushed.
gh release create "$TAG" \
    --repo "$REPO_SLUG" \
    --title "Version $PLUGIN_VERSION" \
    --notes "Initial release for WordPress.org review. This release corresponds to the code submitted for version $PLUGIN_VERSION."

echo "SUCCESS: GitHub Release has been created successfully."

# --- STEP 4: UPDATE README.TXT FILE ---
echo "INFO: Updating readme.txt with the correct release link..."

# Construct the full URL to the release we just created.
RELEASE_URL="https://github.com/$REPO_SLUG/releases/tag/$TAG"

# This command finds the line starting with "Development of this plugin..." and replaces the entire line
# with the new, correct text including the precise URL.
# The '|' is used as a separator for sed to avoid conflicts with the '/' in the URL.
sed -i.bak "s|^Development of this plugin.*|Development of this plugin takes place on GitHub. You can find the full, un-minified source code for this release here: $RELEASE_URL|" readme.txt

# Remove the backup file created by sed.
rm readme.txt.bak

echo "SUCCESS: readme.txt has been updated."

# --- STEP 5: COMMIT AND PUSH THE README UPDATE ---
echo "INFO: Committing and pushing the readme.txt update..."
git add readme.txt
git commit -m "docs: Update readme with correct v$PLUGIN_VERSION release link"
git push origin main

echo "--------------------------------------------------"
echo "ALL TASKS COMPLETED SUCCESSFULLY!"
echo "The GitHub Release is live and readme.txt is updated."
echo "You are now ready to ZIP and submit your plugin."
echo "--------------------------------------------------"