import React from 'react';

function FormGroup( props ) {
    return (
        <div className={ ( props.extraClass ) ? 'tada-form-group ' + props.extraClass : 'tada-form-group ' + '' }>
            { props.children }
        </div>
    );
}

export default FormGroup;