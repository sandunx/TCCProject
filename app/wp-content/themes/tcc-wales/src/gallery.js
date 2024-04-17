import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipe from 'photoswipe';
import '../src/photoswipe.css';


window.addEventListener('DOMContentLoaded', function() {
    lightbox.init();
})


const lightbox = new PhotoSwipeLightbox({
    gallery: '#drh-gallery',
    children: 'a',
    pswpModule: PhotoSwipe,
    wheelToZoom: true,
    bgOpacity: 0.9,
    spacing: 10,
    mainClass: "pswp--custom-icon-colors",
    showHideAnimationType: "fade"
});





