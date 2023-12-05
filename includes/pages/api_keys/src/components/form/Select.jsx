import React from 'react';

function Select(props) {
    return (
        <div>
            <select className='tada-select' id={ props.id } name={ props.name }>
                { props.options }
            </select>
        </div>
    );
}

export default Select;