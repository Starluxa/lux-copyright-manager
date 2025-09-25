const fs = require('fs');
const path = require('path');
const archiver = require('archiver');
const { execSync } = require('child_process');

// Ensure fresh build before creating ZIP
console.log('🔨 Building fresh assets...');
try {
  execSync('npm run build', { stdio: 'inherit' });
  console.log('✅ Build completed successfully\n');
} catch (error) {
  console.error('❌ Build failed:', error.message);
  process.exit(1);
}

// Create a file to stream archive data to
const output = fs.createWriteStream('lux-copyright-manager.zip');
const archive = archiver('zip', {
  zlib: { level: 9 } // Sets the compression level
});

// Listen for all archive data to be written
output.on('close', function() {
  console.log(`ZIP file created: ${archive.pointer()} total bytes`);
  console.log('WordPress-ready ZIP file is ready! 🎉');
});

// Catch warnings during archiving
archive.on('warning', function(err) {
  if (err.code === 'ENOENT') {
    // Log warning
    console.log('Warning:', err);
  } else {
    // Throw error
    throw err;
  }
});

// Catch errors during archiving
archive.on('error', function(err) {
  throw err;
});

// Pipe archive data to the file
archive.pipe(output);

// List of files and directories to include in the ZIP
const filesToInclude = [
  'build/',
  'includes/',
  'languages/',
  'lux-copyright-manager.php',
  'readme.txt'
];

// Add files to the archive
filesToInclude.forEach(file => {
  if (fs.existsSync(file)) {
    if (fs.lstatSync(file).isDirectory()) {
      // Add directory
      archive.directory(file, file);
    } else {
      // Add file
      archive.file(file, { name: file });
    }
  } else {
    console.log(`Warning: ${file} does not exist`);
  }
});

// Finalize the archive
archive.finalize();