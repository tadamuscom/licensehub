import { createRoot } from '@wordpress/element';
import { App } from '@releases/App';

const root = createRoot(document.getElementById('releases-root'));
root.render(<App />);
