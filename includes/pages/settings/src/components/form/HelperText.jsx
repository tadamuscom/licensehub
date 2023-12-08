import React from 'react';

function HelperText( props ) {
    return (
        <div>
            <p className="tada-helper-text">{ props.content }</p>
        </div>
    );
}

export default HelperText;