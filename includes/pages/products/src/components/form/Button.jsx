import React from 'react';
function Button( props ) {
    return (
        <div>
            <input type="submit" className="tada-button" value={ props.label } />
        </div>
    );
}

export default Button;