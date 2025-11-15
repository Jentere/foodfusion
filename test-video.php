<?php
require_once('includes/paths.php');

// Test video files
$testVideos = [
    'video1.mp4',
    'video2.mp4',
    'video3.mp4',
    'video4.mp4',
    'video5.mp4',
    'video6.mp4',
    'HOW_TO_MAKE_MEAT_PIE.mp4',
    'AIR FRYER CHICKEN TIKKA RESTAURANT STYLE CHICKEN TIKKA IN AIR FRYER.mp4'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Test - FoodFusion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #e76f51;
            text-align: center;
        }
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .video-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .video-item h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 1rem;
        }
        video {
            width: 100%;
            border-radius: 8px;
            background: #000;
        }
        .status {
            margin-top: 10px;
            padding: 8px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Video MIME Type Test</h1>
    <p style="text-align: center; color: #666;">Testing video playback for all videos in resources folder</p>
    
    <div class="video-grid">
        <?php foreach ($testVideos as $video): 
            $videoPath = url('resources/' . $video);
            $fileExists = file_exists(__DIR__ . '/resources/' . $video);
        ?>
        <div class="video-item">
            <h3><?php echo htmlspecialchars($video); ?></h3>
            <?php if ($fileExists): ?>
                <video controls preload="metadata">
                    <source src="<?php echo $videoPath; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="status success">✓ File exists</div>
            <?php else: ?>
                <div class="status error">✗ File not found</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        // Monitor video loading errors
        document.querySelectorAll('video').forEach(video => {
            video.addEventListener('error', function(e) {
                const statusDiv = this.parentElement.querySelector('.status');
                statusDiv.className = 'status error';
                statusDiv.textContent = '✗ Error loading video: ' + (this.error ? this.error.message : 'Unknown error');
            });
            
            video.addEventListener('loadedmetadata', function() {
                const statusDiv = this.parentElement.querySelector('.status');
                statusDiv.className = 'status success';
                statusDiv.textContent = '✓ Video loaded successfully';
            });
        });
    </script>
</body>
</html>
