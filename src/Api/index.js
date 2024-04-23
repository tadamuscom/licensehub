import { createRoot } from '@wordpress/element';
import { App } from './App';

const root = createRoot(document.getElementById('api-keys-root'));
root.render(<App />);
