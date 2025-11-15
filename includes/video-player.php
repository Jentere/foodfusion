<?php
function renderVideoPlayer($videoData) {
    $videoPath = url($videoData['video_path']);
    $thumbnailPath = url($videoData['thumbnail_path'] ?? '');
    $title = htmlspecialchars($videoData['title']);
    $duration = formatDuration($videoData['duration'] ?? 0);
    
    // Determine video type based on file extension
    $videoType = strtolower(pathinfo($videoData['video_path'], PATHINFO_EXTENSION));
    
    // Map file extensions to proper MIME types
    $mimeTypes = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogg' => 'video/ogg',
        'ogv' => 'video/ogg',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        'm4v' => 'video/mp4'
    ];
    
    $mimeType = $mimeTypes[$videoType] ?? 'video/mp4';
    
    // Escape for HTML output
    $videoPathEscaped = htmlspecialchars($videoPath);
    $thumbnailPathEscaped = htmlspecialchars($thumbnailPath);
    $titleEscaped = htmlspecialchars($title);
    
    return <<<HTML
    <div class="video-container">
        <video class="video-player" preload="metadata" controls>
            <source src="{$videoPathEscaped}" type="{$mimeType}">
            Your browser does not support the video tag.
        </video>
        <img src="{$thumbnailPathEscaped}" alt="{$titleEscaped}" class="video-thumbnail">
        <div class="video-controls">
            <button class="play-button">
                <i class="fas fa-play"></i>
            </button>
            <div class="video-progress">
                <div class="video-progress-bar"></div>
            </div>
            <div class="video-time">0:00 / {$duration}</div>
        </div>
    </div>
HTML;
}

function formatDuration($seconds) {
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    return sprintf("%d:%02d", $minutes, $seconds);
}
?> 