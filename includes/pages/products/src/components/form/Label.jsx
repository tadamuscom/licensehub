import React from 'react';

function Label( props ) {
    return (
        <div>
            <label htmlFor={ props.htmlFor } className={ props.classes }>{ props.label }</label>
        </div>
    );
}

export default Label;