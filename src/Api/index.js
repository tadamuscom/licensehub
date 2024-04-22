import React from "react";
import { createRoot } from 'react-dom/client';
import App from './App'

const root = createRoot( document.getElementById( 'api-keys-root' ) );
root.render( <App /> );