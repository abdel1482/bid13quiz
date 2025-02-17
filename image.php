<?php
function plotCSV($csvFile) {
    // Check if the file exists
    if (!file_exists($csvFile)) {
        die("CSV file not found.");
    }

    // Read the CSV file
    $rows = array_map('str_getcsv', file($csvFile));
    $header = array_shift($rows); // Remove the header

    // Initialize image dimensions
    $width = 800;
    $height = 600;
    $padding = 50;

    // Create a blank image
    $image = imagecreatetruecolor($width, $height);
    if (!$image) {
        die('Failed to create image.');
    }

    // Allocate colors
    $backgroundColor = imagecolorallocate($image, 255, 255, 255);
    $lineColor = imagecolorallocate($image, 0, 0, 0);

    // Fill the background
    imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

    // Find the min and max values for x and y
    $xValues = array_column($rows, 0);
    $yValues = array_column($rows, 1);
    $minX = min($xValues);
    $maxX = max($xValues);
    $minY = min($yValues);
    $maxY = max($yValues);

    // Scale the data to fit the image
    $scaleX = ($width - 2 * $padding) / ($maxX - $minX);
    $scaleY = ($height - 2 * $padding) / ($maxY - $minY);

    // Plot the points
    $prevX = null;
    $prevY = null;
    foreach ($rows as $row) {
        $x = $padding + ($row[0] - $minX) * $scaleX;
        $y = $height - $padding - ($row[1] - $minY) * $scaleY;

        if ($prevX !== null && $prevY !== null) {
            imageline($image, $prevX, $prevY, $x, $y, $lineColor);
        }

        $prevX = $x;
        $prevY = $y;
    }

    // Output the image directly to the browser
    imagepng($image, 'plot.png');

    // Free up memory
    imagedestroy($image);
}

// Usage
plotCSV('out.csv');
?>