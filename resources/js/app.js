import './bootstrap';

import Alpine from 'alpinejs';
import postCard from './alpine/postCard'

import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token
}

window.Alpine = Alpine;

Alpine.data('postCard', postCard)

Alpine.start();
