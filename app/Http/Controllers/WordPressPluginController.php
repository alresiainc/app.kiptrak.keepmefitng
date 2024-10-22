<?php

namespace App\Http\Controllers;

use Exception;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class WordPressPluginController extends Controller
{
    public function serveUpdated(Request $request, $plugin)
    {
        Log::info('Received request for plugin update', ['plugin' => $plugin]);

        try {
            // Secure the endpoint with an API key
            $apiKey = $request->query('api_key');
            if ($apiKey !== config('wordpress.plugin_api_key')) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Define the path to the folder containing the plugin update files
            $folderPath = public_path('wordpress-plugins/' . $plugin);

            // Check if the folder exists
            if (!File::exists($folderPath)) {
                return response()->json(['message' => 'Plugin not found'], 404);
            }

            // Create a temporary directory for packaging files
            $tempDir = public_path('wordpress-plugins/temp_' . uniqid());
            File::makeDirectory($tempDir);


            // Copy the plugin files into the temporary directory
            $files = File::allFiles($folderPath);
            foreach ($files as $file) {
                // Skip the JSON file to avoid including it in the zip archive
                if ($file->getFilename() === 'update.json') {
                    continue;
                }

                // Create the subdirectory structure in the temporary directory
                $relativePath = $file->getRelativePathname(); // Get the relative path from the original file
                $destinationPath = $tempDir . '/' . $relativePath; // Destination path in the temporary directory

                // Ensure the subdirectory exists
                File::makeDirectory(dirname($destinationPath), 0755, true);
                // Copy the file to the appropriate directory in the temporary directory
                File::copy($file->getRealPath(), $destinationPath);
            }

            // Generate a unique temporary filename for the zip file
            $zipFileName = public_path('wordpress-plugins/' . $plugin . '_' . uniqid() . '.zip');

            // Create a new zip archive
            $zip = new ZipArchive;

            // Open the zip file for writing
            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Add the temporary directory to the zip file
                $filesInTempDir = File::allFiles($tempDir);
                foreach ($filesInTempDir as $tempFile) {
                    // Get the relative path for the zip file to preserve structure
                    $relativePath = $tempFile->getRelativePathname(); // Get the relative path
                    $zip->addFile($tempFile->getRealPath(), $relativePath);
                }

                // Close the zip archive
                $zip->close();
            } else {
                return response()->json(['message' => 'Could not create zip file'], 500);
            }

            // Clean up: delete the temporary directory
            File::deleteDirectory($tempDir);

            // Serve the zip file for download and delete it after the response is sent
            return Response::download($zipFileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            Log::error('Error serving update for plugin: ' . $plugin . ' - ' . $e->getMessage());
            return response()->json(['message' => 'Error serving update for plugin'], 500);
        }
    }
    public function serveUpdate(Request $request, $plugin)
    {
        // Secure the endpoint with an API key
        $apiKey = $request->query('api_key');
        if ($apiKey !== config('wordpress.plugin_api_key')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Define the path to the folder containing the plugin update files
        $folderPath = public_path('wordpress-plugins/' . $plugin);

        // Check if the folder exists
        if (!File::exists($folderPath)) {
            return response()->json(['message' => 'Plugin not found'], 404);
        }

        // Create a temporary directory for packaging files
        $tempDir = public_path('wordpress-plugins/temp_' . uniqid());
        if (!File::makeDirectory($tempDir, 0755, true)) {
            return response()->json(['message' => 'Could not create temporary directory'], 500);
        }

        // Copy the plugin folder to the temporary directory
        if (!File::copyDirectory($folderPath, $tempDir . '/' . $plugin)) {
            return response()->json(['message' => 'Could not copy plugin folder'], 500);
        }

        $zipFileName = public_path('wordpress-plugins/' . $plugin . '_' . uniqid() . '.zip');

        // Create a new zip archive
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Get all files and directories from the copied folder
            $files = File::allFiles($tempDir . '/' . $plugin);

            foreach ($files as $file) {
                // Add each file to the zip archive, maintaining the original structure
                $relativePath = $plugin . '/' . $file->getRelativePathname();

                // Ignore specific file (e.g., "ignore-this-file.php")
                if ($file->getFilename() === 'update.json') {
                    continue; // Skip this file
                }

                // Add the file to the zip archive
                $zip->addFile($file->getRealPath(), $relativePath);
            }

            // Close the zip archive
            $zip->close();
        } else {
            return response()->json(['message' => 'Could not create zip file'], 500);
        }

        // Clean up: delete the temporary directory
        // File::deleteDirectory($tempDir);

        // Serve the zip file for download
        return Response::download($zipFileName)->deleteFileAfterSend(true);
    }




    public function check(Request $request, $plugin)
    {
        try {
            // Secure the endpoint with an API key
            // $apiKey = $request->query('api_key');
            // if ($apiKey !== config('wordpress.plugin_api_key')) {
            //     Log::info('Serving update for plugin: ' . $plugin);
            //     return response()->json(['message' => 'Unauthorized'], 401);
            // }

            // Define the path to the folder containing the plugin update files
            $folderPath = public_path('wordpress-plugins/' . $plugin);

            // Check if the folder exists
            if (!File::exists($folderPath)) {
                Log::info('Serving update for plugin: ' . $plugin);
                return response()->json(['message' => 'Plugin not found'], 404);
            }

            // Default values (in case the details file doesn't exist)
            $newVersion = 'unknown';
            $requires = 'unknown';
            $tested = 'unknown';
            $changelog = 'No changelog available';

            // Check for a details.json file in the plugin directory for version info
            $detailsFilePath = $folderPath . '/update.json';
            if (File::exists($detailsFilePath)) {
                $details = json_decode(File::get($detailsFilePath), true);

                // Retrieve details if available
                $newVersion = $details['new_version'] ?? $newVersion;
                $requires = $details['requires'] ?? $requires;
                $tested = $details['tested'] ?? $tested;
                $changelog = $details['changelog'] ?? $changelog;
            }

            // Build the download URL dynamically
            // $downloadUrl = route('wordpress.plugin.download', ['plugin' => $plugin, 'api_key' => $apiKey]);
            $downloadUrl = route('wordpress.plugin.download', ['plugin' => $plugin, 'api_key' => $apiKey], true);

            // Return JSON response with plugin details
            return response()->json([
                'new_version' => $newVersion,
                'requires' => $requires,
                'tested' => $tested,
                'download_url' => $downloadUrl,
                'changelog' => $changelog,
            ]);
        } catch (Exception $e) {
            Log::error('Error serving update for plugin: ' . $plugin . ' - ' . $e->getMessage());
            return response()->json(['message' => 'Error serving update for plugin'], 500);
        }
    }
}