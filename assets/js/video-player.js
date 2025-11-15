class VideoPlayer {
    constructor(container) {
        this.container = container;
        this.video = container.querySelector('video');
        this.thumbnail = container.querySelector('.video-thumbnail');
        this.controls = container.querySelector('.video-controls');
        this.playButton = container.querySelector('.play-button');
        this.progressBar = container.querySelector('.video-progress-bar');
        this.timeDisplay = container.querySelector('.video-time');
        
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Play/Pause
        this.playButton.addEventListener('click', () => this.togglePlay());
        this.video.addEventListener('click', () => this.togglePlay());
        
        // Progress bar
        const progress = this.container.querySelector('.video-progress');
        progress.addEventListener('click', (e) => this.seek(e));
        
        // Time updates
        this.video.addEventListener('timeupdate', () => this.updateProgress());
        
        // Video ended
        this.video.addEventListener('ended', () => this.resetPlayer());
        
        // Fullscreen
        this.video.addEventListener('dblclick', () => this.toggleFullscreen());
    }

    togglePlay() {
        if (this.video.paused) {
            this.video.play();
            this.playButton.innerHTML = '<i class="fas fa-pause"></i>';
            this.thumbnail.style.display = 'none';
        } else {
            this.video.pause();
            this.playButton.innerHTML = '<i class="fas fa-play"></i>';
        }
    }

    seek(e) {
        const progress = this.container.querySelector('.video-progress');
        const pos = (e.pageX - progress.offsetLeft) / progress.offsetWidth;
        this.video.currentTime = pos * this.video.duration;
    }

    updateProgress() {
        const percent = (this.video.currentTime / this.video.duration) * 100;
        this.progressBar.style.width = percent + '%';
        this.updateTimeDisplay();
    }

    updateTimeDisplay() {
        const minutes = Math.floor(this.video.currentTime / 60);
        const seconds = Math.floor(this.video.currentTime % 60);
        const durationMinutes = Math.floor(this.video.duration / 60);
        const durationSeconds = Math.floor(this.video.duration % 60);
        
        this.timeDisplay.textContent = 
            `${minutes}:${seconds.toString().padStart(2, '0')} / ${durationMinutes}:${durationSeconds.toString().padStart(2, '0')}`;
    }

    resetPlayer() {
        this.video.currentTime = 0;
        this.playButton.innerHTML = '<i class="fas fa-play"></i>';
        this.thumbnail.style.display = 'block';
    }

    toggleFullscreen() {
        if (!document.fullscreenElement) {
            this.container.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }
}

// Initialize all video players on the page
document.addEventListener('DOMContentLoaded', () => {
    const videoContainers = document.querySelectorAll('.video-container');
    videoContainers.forEach(container => new VideoPlayer(container));
}); 