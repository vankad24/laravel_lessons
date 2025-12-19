import './bootstrap';

import Alpine from 'alpinejs';
import postCard from './alpine/postCard'

window.Alpine = Alpine;

Alpine.data('postCard', postCard)

Alpine.start();
