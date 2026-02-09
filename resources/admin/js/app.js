import.meta.glob(
  [
    '../assets/**/*.{png,jpg,jpeg,gif,svg,webp,woff,woff2,ttf,eot,otf,mp4,webm,ogg,mp3,ico}',
    '!../assets/**/*.map',
  ],
  { eager: true }
);
import './legacy.js';
