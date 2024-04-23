import { createRoot } from '@wordpress/element';
import { App } from '@products/App';

const root = createRoot(document.getElementById('products-root'));
root.render(<App />);
