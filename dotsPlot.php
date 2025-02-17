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
    $backgroundColor = imagecolorallocate($image, 255, 255, 255); // White background
    $axisColor = imagecolorallocate($image, 0, 0, 0); // Black for axes
    $gridColor = imagecolorallocate($image, 200, 200, 200); // Light gray for grid
    $dotColor = imagecolorallocate($image, 255, 0, 0); // Red for dots

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

    // Draw grid lines
    $gridSpacingX = ($maxX - $minX) / 10; // 10 vertical grid lines
    $gridSpacingY = ($maxY - $minY) / 10; // 10 horizontal grid lines
    for ($i = 0; $i <= 10; $i++) {
        // Vertical grid lines
        $x = $padding + $i * $gridSpacingX * $scaleX;
        imageline($image, $x, $padding, $x, $height - $padding, $gridColor);

        // Horizontal grid lines
        $y = $height - $padding - $i * $gridSpacingY * $scaleY;
        imageline($image, $padding, $y, $width - $padding, $y, $gridColor);
    }

    // Draw axes
    imageline($image, $padding, $height - $padding, $width - $padding, $height - $padding, $axisColor); // X-axis
    imageline($image, $padding, $padding, $padding, $height - $padding, $axisColor); // Y-axis

    // Plot the dots
    foreach ($rows as $row) {
        $x = $padding + ($row[0] - $minX) * $scaleX;
        $y = $height - $padding - ($row[1] - $minY) * $scaleY;
        imagefilledellipse($image, $x, $y, 8, 8, $dotColor); // Draw a dot (8x8 pixels)
    }

    // create and save the image
    imagepng($image, 'plot_dots.png');

    // Free up memory
    imagedestroy($image);
}

// Usage
plotCSV('out.csv');
?>