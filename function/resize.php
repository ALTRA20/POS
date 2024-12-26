<?php
function resize($imagePath) {
    // Load the image
    $image = imagecreatefromjpeg($imagePath);

    // Get image dimensions
    $width = imagesx($image);
    $height = imagesy($image);

    // Calculate the crop dimensions
    $cropWidth = $width;
    $cropHeight = $width; // Crop height is the same as width

    // Calculate the crop position (center)
    $cropX = 0; // Start from the left
    $cropY = ($height - $cropHeight) / 2; // Center vertically

    // Create a new image for the cropped portion
    $croppedImage = imagecrop($image, ['x' => $cropX, 'y' => $cropY, 'width' => $cropWidth, 'height' => $cropHeight]);

    // Save the cropped image
    imagejpeg($croppedImage, $imagePath);

    // Free up memory
    imagedestroy($image);
    imagedestroy($croppedImage);
}
?>