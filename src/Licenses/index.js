import { createRoot } from '@wordpress/element';
import { App } from '@licenses/App';

const root = createRoot(document.getElementById('license-keys-root'));
root.render(<App />);
