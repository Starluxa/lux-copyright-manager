# How to Deploy Your Plugin to WordPress.org

## Current Structure

Your plugin is now organized in the standard SVN repository structure:

```
.
├── assets/
│   ├── screenshot-1.png
│   ├── screenshot-2.png
│   ├── screenshot-3.png
│   ├── screenshot-4.png
│   ├── screenshot-5.png
│   └── screenshot-6.png
├── tags/
└── trunk/
    ├── lux-copyright-manager.php
    ├── package.json
    ├── README.md
    ├── readme.txt
    ├── includes/
    ├── languages/
    ├── src/
    └── build/
```

## Creating a WordPress-Ready ZIP File

To create a ZIP file ready for WordPress.org submission, run:

```bash
npm install
npm run build
npm run plugin-zip
```

This will create a ZIP file named `lux-copyright-date-block.zip` that contains all the necessary plugin files without development files.

**Note**: The `plugin-zip` command uses the `files` field from `package.json` to detect which files to include in the ZIP archive.

## SVN Repository Structure Explained

- **trunk/**: Contains the current development version of your plugin
- **tags/**: Will contain subdirectories for each release version (e.g., tags/1.0.0/, tags/1.1.0/)
- **assets/**: Contains images for the readme.txt file (screenshots, plugin headers, icons)

## Steps for WordPress.org Submission

1. **Test the ZIP file**:
   - Download the ZIP file 
   - Install it on a fresh WordPress installation
   - Verify all features work as described

2. **Create a WordPress.org account** (if you don't have one):
   - Go to https://wordpress.org/support/register/

3. **Submit your plugin**:
   - Visit https://wordpress.org/plugins/developers/add/
   - Upload your ZIP file
   - Fill in the required information
   - Submit for review

## Making Updates

When you make changes to your plugin:

1. Update the version number in `lux-copyright-manager.php`
2. Update the Stable Tag in `readme.txt`
3. Run the build and zip commands:
   ```bash
   npm install
   npm run build
   npm run plugin-zip
   ```
4. For SVN users: Commit changes to trunk and create a new tag for the release

## Development Workflow

For ongoing development:

1. Make changes in the plugin directory
2. Run `npm run build` to compile your block
3. Test your changes locally
4. When ready to release, update version numbers and run the build and zip commands