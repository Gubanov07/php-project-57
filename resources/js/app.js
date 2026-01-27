import './bootstrap';

import Rails from '@rails/ujs';
import Alpine from 'alpinejs';

Rails.start();

window.Alpine = Alpine;

Alpine.start();
