import { createRoot } from '@wordpress/element';
import { App } from '@settings/App';

const root = createRoot(document.getElementById('settings-root'));
root.render(<App />);
