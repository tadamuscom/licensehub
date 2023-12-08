import React from 'react';

function LinkButton( props ) {
    return (
        <div style={ {
            marginTop: '20px',
            marginBottom: '20px'
        } }>
            <a onClick={ props.click } className={ 'tada-button ' + props.extraClass } disabled={ props.disabled }> { props.label } </a>
        </div>
    );
}

export default LinkButton;